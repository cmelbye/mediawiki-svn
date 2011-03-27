#!/usr/bin/python
# -*- coding: utf-8 -*-
'''
Copyright (C) 2010 by Diederik van Liere (dvanliere@gmail.com)
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License version 2
as published by the Free Software Foundation.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
See the GNU General Public License for more details, at
http://www.fsf.org/licenses/gpl.html
'''

__author__ = '''\n'''.join(['Diederik van Liere (dvanliere@gmail.com)', ])
__email__ = 'dvanliere at gmail dot com'
__date__ = '2011-02-06'
__version__ = '0.1'


import bz2
import os
import hashlib
import codecs
import sys
import datetime
import progressbar
from multiprocessing import JoinableQueue, Process, cpu_count, current_process
from xml.etree.cElementTree import iterparse, dump
from collections import deque

if '..' not in sys.path:
    sys.path.append('..')


try:
    from database import cassandra
    import pycassa

except ImportError:
    print 'I am not going to use Cassandra today, it\'s my off day.'


from database import db
from bots import detector
from utils import file_utils
import extracter

NAMESPACE = {
    #0:'Main',    
    #1:'Talk',
    #2:'User',
    #3:'User talk',
    4:'Wikipedia',
    #5:'Wikipedia talk',
    6:'File',
    #7:'File talk',
    8:'MediaWiki',
    #9:'MediaWiki talk',
    10:'Template',
    #11:'Template talk',
    12:'Help',
    #13:'Help talk',
    14:'Category',
    #15:'Category talk',
    90:'Thread',
    #91:'Thread talk',
    92:'Summary',
    #93:'Summary talk',
    100:'Portal',
    #101:'Portal talk',
    108:'Book',
    #109:'Book talk'
}

class Statistics:
    def __init__(self):
        self.count_articles = 0
        self.count_revisions = 0

    def summary(self):
        print 'Number of articles: %s' % self.count_articles
        print 'Number of revisions: %s' % self.count_revisions


class Buffer:
    def __init__(self, storage, id):
        assert storage == 'cassandra' or storage == 'mongo' or storage == 'csv', \
            'Valid storage options are cassandra and mongo.'
        self.storage = storage
        self.revisions = {}
        self.comments = {}
        self.id = id
        self.keyspace_name = 'enwiki'
        self.keys = ['revision_id', 'article_id', 'id', 'namespace',
                     'title', 'timestamp', 'hash', 'revert', 'bot', 'prev_size',
                     'cur_size', 'delta']
        self.setup_storage()
        self.stats = Statistics()

    def setup_storage(self):
        if self.storage == 'cassandra':
            self.db = pycassa.connect(self.keyspace_name)
            self.collection = pycassa.ColumnFamily(self.db, 'revisions')

        elif self.storage == 'mongo':
            self.db = db.init_mongo_db(self.keyspace_name)
            self.collection = self.db['kaggle']

        else:
            kaggle_file = 'kaggle_%s.csv' % self.id
            comment_file = 'kaggle_comments_%s.csv' % self.id
            file_utils.delete_file('', kaggle_file, directory=False)
            file_utils.delete_file('', comment_file, directory=False)
            self.fh_main = codecs.open(kaggle_file, 'a', 'utf-8')
            self.fh_extra = codecs.open(comment_file, 'a', 'utf-8')

    def add(self, revision):
        self.stringify(revision)
        id = revision['revision_id']
        self.revisions[id] = revision
        if len(self.revisions) > 10000:
            print '%s: Emptying buffer %s - buffer size %s' % (datetime.datetime.now(), self.id, len(self.revisions))
            self.store()
            self.clear()

    def stringify(self, revision):
        for key, value in revision.iteritems():
            try:
                value = str(value)
            except UnicodeEncodeError:
                value = value.encode('utf-8')
            revision[key] = value

    def empty(self):
        self.store()
        self.clear()
        if self.storage == 'csv':
            self.fh_main.close()
            self.fh_extra.close()

    def clear(self):
        self.revisions = {}
        self.comments = {}

    def store(self):
        if self.storage == 'cassandra':
            self.collection.batch_insert(self.revisions)
        elif self.storage == 'mongo':
            print 'insert into mongo'
        else:
            for revision in self.revisions.itervalues():
                values = []
                for key in self.keys:
                    values.append(revision[key].decode('utf-8'))

                value = '\t'.join(values) + '\n'
                row = '\t'.join([key, value])
                self.fh_main.write(row)

            for revision_id, comment in self.comments.iteritems():
                comment = comment.decode('utf-8')
                row = '\t'.join([revision_id, comment]) + '\n'
                self.fh_extra.write(row)


def extract_categories():
    '''
    Field 1: page id
    Field 2: name category
    Field 3: sort key
    Field 4: timestamp last change
    '''
    filename = 'C:\\Users\\diederik.vanliere\\Downloads\\enwiki-latest-categorylinks.sql'
    output = codecs.open('categories.csv', 'w', encoding='utf-8')
    fh = codecs.open(filename, 'r', encoding='utf-8')

    try:
        for line in fh:
            if line.startswith('INSERT INTO `categorylinks` VALUES ('):
                line = line.replace('INSERT INTO `categorylinks` VALUES (', '')
                line = line.replace("'", '')
                categories = line.split('),(')
                for category in categories:
                    category = category.split(',')
                    if len(category) == 4:
                        output.write('%s\t%s\n' % (category[0], category[1]))
    except UnicodeDecodeError, e:
        print e

    output.close()
    fh.close()


def extract_revision_text(revision):
    rev = revision.find('ns0:text')
    if rev != None:
        if rev.text == None:
            rev = fix_revision_text(revision)
        return rev.text.encode('utf-8')
    else:
        return ''


def extract_username(contributor):
    contributor = contributor.find('ns0:username')
    if contributor != None:
        return contributor.text
    else:
        return None


def determine_username_is_bot(contributor, bots):
    '''
    #contributor is an xml element containing the id of the contributor
    @bots should have a dict with all the bot ids and bot names
    @Return False if username id is not in bot dict id or True if username id
    is a bot id.
    '''
    username = contributor.find('ns0:username')
    if username == None:
        return 0
    else:
        if username.text in bots:
            return 1
        else:
            return 0


def extract_contributor_id(contributor):
    '''
    @contributor is the xml contributor node containing a number of attributes
    Currently, we are only interested in registered contributors, hence we
    ignore anonymous editors. 
    '''
    if contributor.get('deleted'):
        # ASK: Not sure if this is the best way to code deleted contributors.
        return None
    elem = contributor.find('ns0:id')
    if elem != None:
        return {'id':elem.text}
    else:
        elem = contributor.find('ns0:ip')
        if elem != None and elem.text != None \
        and validate_ip(elem.text) == False \
        and validate_hostname(elem.text) == False:
            return {'username':elem.text, 'id': elem.text}
        else:
            return None


def fix_revision_text(revision):
    if revision.text == None:
        revision.text = ''
    return revision


def create_md5hash(text):
    hash = {}
    if text != None:
        m = hashlib.md5()
        m.update(text)
        #echo m.digest()
        hash['hash'] = m.hexdigest()
    else:
        hash['hash'] = -1
    return hash


def calculate_delta_article_size(size, text):
    if 'prev_size' not in size:
        size['prev_size'] = 0
        size['cur_size'] = len(text)
        size['delta'] = len(text)
    else:
        size['prev_size'] = size['cur_size']
        delta = len(text) - size['prev_size']
        size['cur_size'] = len(text)
        size['delta'] = delta
    return size


def parse_contributor(contributor, bots):
    username = extract_username(contributor)
    user_id = extract_contributor_id(contributor)
    bot = determine_username_is_bot(contributor, bots)
    contributor.clear()
    editor = {}
    editor['username'] = username
    editor['bot'] = bot
    if user_id != None:
        editor.update(user_id)
    else:
        editor = False
    return editor


def determine_namespace(title):
    namespaces = {'User': 2,
                  'Talk': 1,
                  'User Talk': 3,
                  }
    ns = {}
    if title != None:
        for namespace in namespaces:
            if title.startswith(namespace):
                ns['namespace'] = namespaces[namespace]
        if ns == {}:
            for namespace in NAMESPACE.values():
                if title.startswith(namespace):
                    '''
                    article does not belong to either the main namespace, user, 
                    talk or user talk namespace.
                    '''
                    ns = False
                    return ns
            ns['namespace'] = 0
    else:
        ns = False
    return ns


def prefill_row(title, article_id, namespace):
    row = {}
    row['title'] = title
    row['article_id'] = article_id
    row.update(namespace)
    return row


def is_revision_reverted(hash_cur, hashes):
    revert = {}
    if hash_cur in hashes and hash_cur != -1:
        revert['revert'] = 1
    else:
        revert['revert'] = 0
    return revert


def extract_comment_text(revision_id, revision):
    comment = {}
    text = revision.find('comment')
    if text != None and text.text != None:
        comment[revision_id] = text.text.encode('utf-8')
    return comment


def count_edits(article, counts, bots):
    title = article['title'].text
    namespace = determine_namespace(title)
    xml_namespace = 'http://www.mediawiki.org/xml/export-0.4/'
    if namespace != False:
        article_id = article['id'].text
        revisions = article['revisions']
        for revision in revisions:
            if revision == None:
                #the entire revision is empty, weird. 
                continue
            dump(revision)
            contributor = revision.find('%s:contributor' % xml_namespace)
            contributor = parse_contributor(contributor, bots)
            if not contributor:
                #editor is anonymous, ignore
                continue
            counts.setdefault(contributor['username'], 0)
            counts[contributor['username']] += 1
            revision.clear()

    article = None
    return counts


def create_variables(article, cache, bots):
    title = article['title']
    namespace = determine_namespace(title)

    if namespace != False:
        cache.stats.count_articles += 1
        article_id = article['id'].text
        article['id'].clear()
        hashes = deque()
        size = {}
        revisions = article['revisions']
        for revision in revisions:
            cache.stats.count_revisions += 1
            if revision == None:
                #the entire revision is empty, weird. 
                continue
            contributor = revision.find('ns0:contributor')
            contributor = parse_contributor(contributor, bots)
            if not contributor:
                #editor is anonymous, ignore
                continue

            revision_id = revision.find('ns0:id')
            revision_id = extracter.extract_revision_id(revision_id)
            if revision_id == None:
                #revision_id is missing, which is weird
                continue

            row = prefill_row(title, article_id, namespace)
            row['revision_id'] = revision_id
            text = extract_revision_text(revision)
            row.update(contributor)

            comment = extract_comment_text(revision_id, revision)
            cache.comments.update(comment)

            timestamp = revision.find('ns0:timestamp').text
            row['timestamp'] = timestamp

            hash = create_md5hash(text)
            revert = is_revision_reverted(hash['hash'], hashes)
            hashes.append(hash['hash'])
            size = calculate_delta_article_size(size, text)

            row.update(hash)
            row.update(size)
            row.update(revert)
            revision.clear()
            cache.add(row)



def parse_xml(fh):
    context = iterparse(fh, events=('start', 'end'))
    context = iter(context)

    article = {}
    article['revisions'] = []
    id = False
    namespace = '{http://www.mediawiki.org/xml/export-0.4/}'

    for event, elem in context:
        if event == 'end' and elem.tag == '%s%s' % (namespace, 'title'):
            article['title'] = elem
        elif event == 'end' and elem.tag == '%s%s' % (namespace, 'revision'):
            article['revisions'].append(elem)
        elif event == 'end' and elem.tag == '%s%s' % (namespace, 'id') and id == False:
            article['id'] = elem
            id = True
        elif event == 'end' and elem.tag == '%s%s' % (namespace, 'page'):
            yield article
            article = {}
            article['revisions'] = []
            id = False




def stream_raw_xml(input_queue, storage, id, function, dataset):
    bots = detector.retrieve_bots('en')
    t0 = datetime.datetime.now()
    i = 0
    if dataset == 'training':
        cache = Buffer(storage, id)
    else:
        counts = {}

    while True:
        filename = input_queue.get()
        input_queue.task_done()
        if filename == None:
            break

        fh = bz2.BZ2File(filename, 'rb')
        for article in parse_xml(fh):
            if dataset == 'training':
                function(article, cache, bots)
            else:
                counts = function(article, counts, bots)
            i += 1
            if i % 10000 == 0:
                print 'Worker %s parsed %s articles' % (id, i)
        fh.close()

        t1 = datetime.datetime.now()
        print 'Processing took %s' % (t1 - t0)
        t0 = t1

    if dataset == 'training':
        cache.empty()
        cache.stats.summary()
        print 'Finished parsing bz2 archives'
    else:
        location = os.getcwd()
        filename = 'counts_%s.bin' % id
        file_utils.store_object(counts, location, filename)


def setup(storage):
    keyspace_name = 'enwiki'
    if storage == 'cassandra':
        cassandra.install_schema(keyspace_name, drop_first=True)


def launcher(function, path, dataset, storage, processors):
    setup(storage)
    input_queue = JoinableQueue()
    #files = ['C:\\Users\\diederik.vanliere\\Downloads\\enwiki-latest-pages-articles1.xml.bz2']
    #files = ['/home/diederik/kaggle/enwiki-20100904-pages-meta-history2.xml.bz2']

    files = file_utils.retrieve_file_list(path, 'bz2')

    for file in files:
        filename = os.path.join(path, file)
        print filename
        input_queue.put(filename)

    for x in xrange(processors):
        input_queue.put(None)

    extracters = [Process(target=stream_raw_xml, args=[input_queue, storage, id, function, dataset])
                  for id in xrange(processors)]
    for extracter in extracters:
        extracter.start()

    input_queue.join()



def debug():
    path = '/media/wikipedia_dumps/batch2/'
    files = file_utils.retrieve_file_list(path, 'bz2')
    for file in files:
        filename = os.path.join(path, file)
        unzip(filename)


def launcher_training():
    # launcher for creating training data
    path = '/media/wikipedia_dumps/batch2/'
    function = create_variables
    storage = 'csv'
    dataset = 'training'
    processors = cpu_count()
    launcher(function, path, dataset, storage, processors)


def launcher_prediction():
    # launcher for creating test data
    path = '/media/wikipedia_dumps/batch1/'
    function = count_edits
    storage = 'csv'
    dataset = 'prediction'
    processors = 1
    launcher(function, path, dataset, storage, processors)


if __name__ == '__main__':
    #launcher_training()
    launcher_prediction()
