

"""

mine_landing_pages.py

wikimediafoundation.org
Ryan Faulkner
October 30th, 2010

"""

# =====================================
# Script to mine landing page data from squid logs
# =====================================

import sys
import urlparse as up
import httpagentparser
import math

import cgi	# web queries
import re		# regular expression matching
import gzip	# process gzipped logs

import MySQLdb # db access

import miner_help as mh

# import urllib - python 3+

def mine_landing_pages(run_id, logFileName, db, cur):

	count_parse = 0
	
	# open the file
	# logFileName = sys.argv[1]
	if (re.search('\.gz',logFileName)):
		logFile = gzip.open(logFileName, 'r')
	else:
		logFile = open(logFileName, 'r')


	# Initialization
	hostIndex = 1;
	queryIndex = 4;
	pathIndex = 2;

	""" SQL Statements """

	insertStmt_lp = 'INSERT INTO landing_page (utm_source, utm_campaign, utm_medium, landing_page,' + \
	'page_url, referrer_url, browser, lang, country, project, ip, request_time) values '

	""" Clear the records for hour ahead of adding """
	time_stamps = mh.get_timestamps(logFileName)
	
	start = time_stamps[0]
	end = time_stamps[1]
	
	# Ensure that the range is correct; otherwise abort - critical that outside records are not deleted
	time_diff = mh.get_timestamps_diff(start, end) 
		
	if math.fabs(time_diff) <= 1.0:
		deleteStmnt = 'delete from landing_page where request_time >= \'' + start + '\' and request_time < \'' + end + '\';'
		
		try:
			# cur.execute(deleteStmnt)
			print >> sys.stdout, "Executed delete from landing page: " + deleteStmnt
		except:
			print >> sys.stderr, "Could not execute delete:\n" + deleteStmnt + "\nResuming insert ..."
			pass
	else:
		print >> sys.stdout, "Could not execute delete statement, DIFF too large\ndiff = " + str(time_diff) + "\ntime_start = " + start + "\ntime_end = " + end + "\nResuming insert ..."
	
	
	count_correct = 0
	count_total = 0
	
	# PROCESS LOG FILE
	# ================
	line = logFile.readline()
	while (line != ''):
		lineArgs = line.split()

		# Get the IP Address of the donor
		ip_add = lineArgs[4];


		# Process Timestamp
		# ================
		# 2010-10-21T23:55:01.431
		#  SELECT CAST('20070529 00:00:00' AS datetime)


		datetime = lineArgs[2];

		date_string = datetime.split('-')
		time_string = datetime.split(':')

		# if the date is not logged ignoere the record
		try:
			year = date_string[0]
			month = date_string[1]
			day = date_string[2][:2]
			hour = time_string[0][-2:]
			min = time_string[1]
			sec = time_string[2][:2]
		except:
			line = logFile.readline()
			continue

		timestamp_string = year + '-' + month + '-' + day + " " + hour + ":" + min + ":" + sec


		# Process referrer URL
		# ================

		try:
			referrer_url = lineArgs[11]
		except IndexError:
			referrer_url  = 'Unavailable'
			
		parsed_referrer_url = up.urlparse(referrer_url)

		if (parsed_referrer_url[hostIndex] == None):
			project = 'NONE';
			source_lang = 'NONE';
		else:
			hostname = parsed_referrer_url[hostIndex].split('.')
			
			if ( len( hostname[0] ) <= 2 ) :
				# referrer_path = parsed_referrer_url[pathIndex].split('/')
				project = hostname[0]  				# wikimediafoundation.org
				source_lang = hostname[0]
			else:
				try:
					project = hostname[0] if ( hostname[1] == 'wikimedia' ) else hostname[1]  # species.wikimedia vs en.wikinews
					source_lang = hostname[0] if ( len(hostname[1]) < 5 ) else 'en'  # pl.wikipedia vs commons.wikimedia
				except:
					project = ''
					source_lang = 'en'
			
		# Process User agent string
		# =====================
		
		try:
			user_agent_string = lineArgs[13]
		except IndexError:
			user_agent_string = ''
			
		user_agent_fields = httpagentparser.detect(user_agent_string)
		browser = 'NONE'

		# Check to make sure fields exist
		if len(user_agent_fields['browser']) != 0:
			if len(user_agent_fields['browser']['name']) != 0:
				browser = user_agent_fields['browser']['name']


		# Process landing URL
		# ================

		try:
			landing_url = lineArgs[8]
		except IndexError:
			landing_url = 'Unavailable'

		
		parsed_landing_url = up.urlparse(landing_url)
		query_fields = cgi.parse_qs(parsed_landing_url[queryIndex]) # Get the banner name and lang
		
		
		# Filter the landing URLs
		#
		# /wikimediafoundation.org/wiki/WMF/
		# /wikimediafoundation.org/w/index.php?title=WMF/
		path_pieces = parsed_landing_url[pathIndex].split('/')
		try:
			
			c1 = re.search('WMF', path_pieces[2] )  != None
			c2 = re.search('Hear_from_Kartika', path_pieces[2])   != None
			cond1 = parsed_landing_url[hostIndex] == 'wikimediafoundation.org' and path_pieces[1] == 'wiki' and (c1 or c2)
			
			c1 = re.search('index.php', path_pieces[2] )  != None
			index_str_flag = c1
			try:
				c2 = re.search('WMF', query_fields['title'][0] ) != None
			except KeyError:
				c2 = 0
			cond2 = (parsed_landing_url[hostIndex] == 'wikimediafoundation.org' and path_pieces[1] == 'w' and c1 and c2)
			
			if cond2:
				count_parse = count_parse + 1
				
			regexp_res = re.search('Special:LandingCheck',landing_url)
			cond3 = (regexp_res == None)
			
			include_request = (cond1 or cond2) and cond3
			 

		except: 
			include_request = 0
		
		if include_request:
			
			# Address cases where the query string contains the landing page - ...wikimediafoundation.org/w/index.php?...
			if index_str_flag:
				try:
					# URLs of the form ...?title=<lp_name>
					lp_country = query_fields['title'][0].split('/')
					landing_page = lp_country[0]
					
					# URLs of the form ...?county_code=<iso_code>
					try:
						country = query_fields['country_code'][0]
						
					# URLs of the form ...?title=<lp_name>/<lang>/<iso_code>
					except:
						if len(lp_country) == 3:
							country = lp_country[2]
						else:
							country = lp_country[1]
						
				except:
					landing_page = 'NONE'
					country = mh.localize_IP(cur, ip_add)
					
			else: # ...wikimediafoundation.org/wiki/...
				
				landing_path = parsed_landing_url[pathIndex].split('/')
				landing_page = landing_path[2];
				
				# URLs of the form ...?county_code=<iso_code>
				try:
					country = query_fields['country_code'][0]
				
				# URLs of the form ...<path>/ <lp_name>/<lang>/<iso_code>
				except:
					try:
						if len(landing_path) == 5:
							country = landing_path[4] 
							# source_lang = landing_path[3] 							
						else:
							country = landing_path[3]
							
					except:
						country =  mh.localize_IP(cur, ip_add) 
			
			# If country is confused with the language use the ip
			if country == country.lower():
				country = mh.localize_IP(cur, ip_add) 
							
			# ensure fields exist
			try:
				utm_source = query_fields['utm_source'][0]
				utm_campaign = query_fields['utm_campaign'][0]
				utm_medium = query_fields['utm_medium'][0];

			except KeyError:
				utm_source = 'NONE'
				utm_campaign = 'NONE'
				utm_medium = 'NONE'
				
			# INSERT INTO landing_page ('utm_source', 'utm_campaign', 'utm_medium', 'landing_page', 'page_url', 'lang', 'project', 'ip')  values ()
			try:
				val = '(\'' + utm_source + '\',\'' + utm_campaign + '\',\'' + utm_medium + '\',\'' + landing_page + \
				'\',\'' + landing_url + '\',\'' + referrer_url + '\',\'' + browser + '\',\'' + source_lang + '\',\'' + country + '\',\''  \
				+ project + '\',\'' +  ip_add + '\',' + 'convert(\'' + timestamp_string + '\', datetime)' + ');'
				
				#print insertStmt + val
				cur.execute(insertStmt_lp + val)

			except:
				print "Could not insert:\n" + insertStmt_lp + val
				pass
				
		line = logFile.readline()

	

	

