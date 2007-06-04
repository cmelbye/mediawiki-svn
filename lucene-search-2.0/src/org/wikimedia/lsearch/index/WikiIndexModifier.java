/*
 * Created on Feb 11, 2007
 *
 */
package org.wikimedia.lsearch.index;

import java.io.File;
import java.io.IOException;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collection;
import java.util.Collections;
import java.util.HashSet;
import java.util.Hashtable;
import java.util.Set;

import org.apache.log4j.Logger;
import org.apache.lucene.analysis.Analyzer;
import org.apache.lucene.analysis.PerFieldAnalyzerWrapper;
import org.apache.lucene.analysis.SimpleAnalyzer;
import org.apache.lucene.document.Document;
import org.apache.lucene.document.Field;
import org.apache.lucene.index.IndexReader;
import org.apache.lucene.index.IndexWriter;
import org.apache.lucene.index.Term;
import org.apache.lucene.store.Directory;
import org.apache.lucene.store.FSDirectory;
import org.wikimedia.lsearch.analyzers.Analyzers;
import org.wikimedia.lsearch.analyzers.FastWikiTokenizerEngine;
import org.wikimedia.lsearch.analyzers.FilterFactory;
import org.wikimedia.lsearch.analyzers.WikiTokenizer;
import org.wikimedia.lsearch.beans.Article;
import org.wikimedia.lsearch.beans.IndexReportCard;
import org.wikimedia.lsearch.beans.Redirect;
import org.wikimedia.lsearch.config.GlobalConfiguration;
import org.wikimedia.lsearch.config.IndexId;
import org.wikimedia.lsearch.interoperability.RMIMessengerClient;
import org.wikimedia.lsearch.util.Localization;

/**
 * IndexModifier for batch update of local lucene index. 
 * 
 * @author rainman
 *
 */
public class WikiIndexModifier {
	/** simple wrapper for int for the words hashtable */
	class SimpleInt{
		public int i;
		
		SimpleInt(int value){
			i = value;
		}
	}

	static public final int MAX_FIELD_LENGTH = 100000;
	/** Simple implementation of batch addition and deletion */
	class SimpleIndexModifier {
		protected IndexId iid;
		protected IndexReader reader;
		protected IndexWriter writer;
		protected boolean rewrite;		
		protected String langCode;
		
		protected HashSet<IndexUpdateRecord> nonDeleteDocuments;
		
		/** All reports to be sent back to main indexer host */
		Hashtable<IndexUpdateRecord,IndexReportCard> reportQueue;
				
		// TODO : synchronize multiple threads
		
		/**
		 * SimpleIndexModifier
		 * 
		 * @param iid 
		 * @param analyzer
		 * @param rewrite - if true, will create new index
		 */
		SimpleIndexModifier(IndexId iid, String langCode, boolean rewrite){
			this.iid = iid;
			this.rewrite = rewrite;
			this.langCode = langCode;
			reportQueue = new Hashtable<IndexUpdateRecord,IndexReportCard>();
		}

		protected IndexReportCard getReportCard(IndexUpdateRecord rec){
			if(!rec.isReportBack())
				return null;
			IndexReportCard card = reportQueue.get(rec);
			if(card == null){
				card = new IndexReportCard(rec.getReportId(),global.getLocalhost(),iid.toString());
				reportQueue.put(rec,card);
			}
			return card;
		}
		
		/** Batch-delete documents, returns true if successfull */
		boolean deleteDocuments(Collection<IndexUpdateRecord> records){
			nonDeleteDocuments = new HashSet<IndexUpdateRecord>();
			try {
				try{
					reader = IndexReader.open(iid.getIndexPath());
				} catch(IOException e){
					if(IndexReader.isLocked(iid.getIndexPath())){
						// unlock the index, and then retry
						unlockIndex(iid.getIndexPath());
						reader = IndexReader.open(iid.getIndexPath());
					} else
						throw e;
				}
				for(IndexUpdateRecord rec : records){								
					if(rec.doDelete()){
						int count = reader.deleteDocuments(new Term("key", rec.getKey()));
						if(count == 0)
							nonDeleteDocuments.add(rec);
						IndexReportCard card = getReportCard(rec);
						if(card!=null){
							if(count == 0 && rec.isReportBack())
								card.setFailedDelete();
							else 
								card.setSuccessfulDelete();
						}
						log.debug(iid+": Deleting document "+rec.getKey()+" "+rec.getArticle());
					}
				}
				reader.close();
			} catch (IOException e) {
				log.warn("I/O Error: could not open/read "+iid.getIndexPath()+" while deleting document.");
				for(IndexUpdateRecord rec : records){
					nonDeleteDocuments.add(rec);
					IndexReportCard card = getReportCard(rec);
					if(card!=null)
						card.setFailedDelete();
				}
				return false;
			}
			return true;
		}
		
		/** Batch-add documents, returns true if successfull */
		boolean addDocuments(Collection<IndexUpdateRecord> records){
			boolean succ = true;
			String path = iid.getIndexPath();
			try {
				writer = new IndexWriter(path,null,rewrite);
			} catch (IOException e) {				
				try {
					// unlock, retry
					if(!new File(path).exists()){
						// try to make brand new index
						makeDBPath(path); // ensure all directories are made
						log.info("Making new index at path "+path);
						writer = new IndexWriter(path,null,true);
					} else if(IndexReader.isLocked(path)){
						unlockIndex(path);
						writer = new IndexWriter(path,null,rewrite); 					
					} else
						throw e;
				} catch (IOException e1) {
					e1.printStackTrace();
					log.error("I/O error openning index for addition of documents at "+path+" : "+e.getMessage());
					return false;
				}				
			}
			writer.setSimilarity(new WikiSimilarity());
			int mergeFactor = iid.getIntParam("mergeFactor",2);
			int maxBufDocs = iid.getIntParam("maxBufDocs",10);
			writer.setMergeFactor(mergeFactor);
			writer.setMaxBufferedDocs(maxBufDocs);
			writer.setUseCompoundFile(true);
			writer.setMaxFieldLength(MAX_FIELD_LENGTH);
			
			FilterFactory filters = new FilterFactory(langCode);

			for(IndexUpdateRecord rec : records){								
				if(rec.doAdd()){
					if(!rec.isAlwaysAdd() && nonDeleteDocuments.contains(rec))
						continue; // don't add if delete/add are paired operations
					if(!checkPreconditions(rec))
						continue; // article shoouldn't be added for some (heuristic) reason					
					IndexReportCard card = getReportCard(rec);
					Object[] ret = makeDocumentAndAnalyzer(rec.getArticle(),filters);
					Document doc = (Document) ret[0];
					Analyzer analyzer = (Analyzer) ret[1];
					try {
						writer.addDocument(doc,analyzer);
						log.debug(iid+": Adding document "+rec.getKey()+" "+rec.getArticle());
						if(card != null)
							card.setSuccessfulAdd();
					} catch (IOException e) {
						log.error("Error writing  document "+rec+" to index "+path);
						if(card != null)
							card.setFailedAdd();
						succ = false; // report unsucc, but still continue, to process all cards 
					} catch(Exception e){
						e.printStackTrace();
						log.error("Error adding document "+rec.getKey()+" with message: "+e.getMessage());
						if(card != null)
							card.setFailedAdd();
						succ = false; // report unsucc, but still continue, to process all cards
					}
				}
			}
			try {
				writer.close();					
			} catch (IOException e) {
				log.error("Error closing index "+path);
				return false;
			}
			return succ;
		}

		public boolean checkPreconditions(IndexUpdateRecord rec){
			return checkAddPreconditions(rec.getArticle(),langCode);
		}
	}
	
	/**
	 * Check if the article should be added. For instance, we don't want
	 * useless redirect in our index, i.e. Robin hood -> Robin Hood
	 * @param rec
	 * @param langCode
	 * @return
	 */	
	public static boolean checkAddPreconditions(Article ar, String langCode){
		if(ar.getNamespace().equals("0")){
			String redirect = Localization.getRedirectTarget(ar.getContents(),langCode);
			if(redirect != null)
				return false; // don't add redirects
			/*if(redirect != null && redirect.toLowerCase().equals(ar.getTitle().toLowerCase())){
				log.debug("Not adding "+ar+" into index: "+ar.getContents());
				return false;
			} */
		}
		return true;
	}
	
	/**
	 * Generate the articles transient characterstics needed only for indexing, 
	 * i.e. list of redirect keywords and Page Rank. 
	 * 
	 * @param article
	 */
	protected static void transformArticleForIndexing(Article ar) {
		ArrayList<Redirect> redirects = ar.getRedirects();
		int ns = Integer.parseInt(ar.getNamespace());
		ar.setRank(ar.getReferences()); // base rank value
		if(redirects != null){
			ArrayList<String> filtered = new ArrayList<String>();
			// index only redirects from the same namespace
			// to avoid a lot of unusable redirects from/to
			// user namespace, but always index redirect FROM main
			for(Redirect r : redirects){
				if((ns == 0 && r.getNamespace() == 0) || ns != 0){
					filtered.add(r.getTitle());
					ar.addToRank(r.getReferences()+1);
				} else
					log.debug("Ignoring redirect "+r+" to "+ar);
			}
			ar.setRedirectKeywords(filtered);
		}
	}
	
	/**
	 * Create necessary directories for index
	 * @param dbname
	 * @return relative path (to document root) of db within filesystem 
	 */
	public static String makeDBPath(String path){
		File dir = new File(path); 
		if(!dir.exists()){
			boolean succ = dir.mkdirs();
			if(!succ){
				log.error("Could not create directory "+path+", do you have permissions to create it?");
				return null;
			}
		}		
		return path;
	}
	
	/** 
	 * Try unlocking the index. This should only be called on index recovery
	 * 
	 * IMPORTANT: assumes single-threaded application and one indexer !!!! 
	 */
	public static void unlockIndex(String path){
		try{
			if(IndexReader.isLocked(path)){
				Directory dir = FSDirectory.getDirectory(path, false);
				IndexReader.unlock(dir);
				log.info("Unlocked index at "+path);
			}
		} catch(IOException e){
			log.warn("I/O error unlock index at "+path+" : "+e.getMessage());
		}
	}
	
	// ============================================================================
	static org.apache.log4j.Logger log = Logger.getLogger(WikiIndexModifier.class);
	protected static GlobalConfiguration global = null;
	/** iids of modified dbs (needed for snapshots) */
	protected static HashSet<IndexId> modifiedDBs = new HashSet<IndexId>();
	
	
	WikiIndexModifier(){
		if(global == null)
			global = GlobalConfiguration.getInstance();
	}
	
	/**
	 * Update all documents in the collection. If needed the request  
	 * is forwarded to a remote object (i.e. if the part of the split
	 * index is indexed by another host). 
	 * 
	 * @param iid
	 * @param updateRecords
	 */
	public boolean updateDocuments(IndexId iid, Collection<IndexUpdateRecord> updateRecords){
		long now = System.currentTimeMillis();
		log.info("Starting update of "+updateRecords.size()+" records on "+iid+", started at "+now);
			
		SimpleIndexModifier modifier = new SimpleIndexModifier(iid,global.getLanguage(iid.getDBname()),false);
		
		Transaction trans = new Transaction(iid);
		trans.begin();
		boolean succDel = modifier.deleteDocuments(updateRecords);
		boolean succAdd = modifier.addDocuments(updateRecords);
		boolean succ = succAdd; // it's OK if articles cannot be deleted
		trans.commit();
		
		// send reports back to the main indexer host
		RMIMessengerClient messenger = new RMIMessengerClient();
		if(modifier.reportQueue.size() != 0)
			messenger.sendReports(modifier.reportQueue.values().toArray(new IndexReportCard[] {}),
					IndexId.get(iid.getDBname()).getIndexHost());
		
		modifiedDBs.add(iid);
		long delta = System.currentTimeMillis()-now;
		if(succ)
			log.info("Successful update ["+(int)((double)updateRecords.size()/delta*1000)+" articles/s] of "+updateRecords.size()+" records in "+delta+"ms on "+iid);
		else
			log.warn("Failed update of "+updateRecords.size()+" records in "+delta+"ms on "+iid);
		return succ;
	}

	/** Close all IndexModifier instances, and optimize if needed */
	public synchronized static HashSet<IndexId> closeAllModifiers(){
		for(IndexId iid : modifiedDBs){
			if(iid.isLogical()) continue;
			if(iid.getBooleanParam("optimize",true)){
				try {
					log.debug("Optimizing "+iid);
					long start = System.currentTimeMillis();
					IndexWriter writer = new IndexWriter(iid.getIndexPath(),new SimpleAnalyzer(),false);
					writer.optimize();
					writer.close();
					long delta = System.currentTimeMillis() - start;
					log.info("Optimized "+iid+" in "+delta+" ms");
				} catch (IOException e) {
					log.error("Could not optimize index at "+iid.getIndexPath());
				}
			}
		}
		HashSet<IndexId> retVal = modifiedDBs;
		modifiedDBs = new HashSet<IndexId>();
		return retVal;
	}

	/**
	 * Make a document and a corresponding analyzer
	 * @param rec
	 * @param languageAnalyzer
	 * @return array { document, analyzer }
	 */
	public static Object[] makeDocumentAndAnalyzer(Article article, FilterFactory filters){
		PerFieldAnalyzerWrapper perFieldAnalyzer = null;
		WikiTokenizer tokenizer = null;
		Document doc = new Document();

		// tranform record so that unnecessary stuff is deleted, e.g. some redirects
		transformArticleForIndexing(article); 
		
		// This will be used to look up and replace entries on index updates.
		doc.add(new Field("key", article.getKey(), Field.Store.YES, Field.Index.UN_TOKENIZED));
		
		// These fields are returned with results
		doc.add(new Field("namespace", article.getNamespace(), Field.Store.YES, Field.Index.UN_TOKENIZED));
		
		// boost document title with it's article rank
		Field title = new Field("title", article.getTitle(),Field.Store.YES, Field.Index.TOKENIZED);
		//log.info(article.getNamespace()+":"+article.getTitle()+" has rank "+article.getRank()+" and redirect: "+((article.getRedirects()==null)? "" : article.getRedirects().size()));
		float rankBoost = calculateArticleRank(article.getRank()); 
		title.setBoost(rankBoost);
		doc.add(title);
		
		// add titles of redirects, generated from analyzer
		Field redirect = new Field("redirect", "", 
				Field.Store.NO, Field.Index.TOKENIZED);
		redirect.setBoost(rankBoost);
		doc.add(redirect);
		
		// most significat words in the text, gets extra score, from analyzer
		Field keyword = new Field("keyword", "", 
				Field.Store.NO, Field.Index.TOKENIZED);
		keyword.setBoost(rankBoost);
		doc.add(keyword);
		
		// the next fields are generated using wikitokenizer 
		doc.add(new Field("contents", "", 
				Field.Store.NO, Field.Index.TOKENIZED));
		
		// each token is one category (category names themself are not tokenized)
		doc.add(new Field("category", "", 
				Field.Store.NO, Field.Index.TOKENIZED));

		String text = article.getContents();
		if(article.isRedirect())
			text=""; // for redirects index only the title
		Object[] ret = Analyzers.getIndexerAnalyzer(text,filters,article.getRedirectKeywords());
		perFieldAnalyzer = (PerFieldAnalyzerWrapper) ret[0];
		
		// set boost for keyword field
		// tokenizer = (WikiTokenizer) ret[1];
		// keyword.setBoost(calculateKeywordsBoost(tokenizer.getTokens().size()));

		return new Object[] { doc, perFieldAnalyzer };
	}
	
	/** 
	 * 
	 * Calculate document boost (article rank) from number of
	 * pages that link this page.
	 * 
	 * @param rank
	 * @return
	 */
	public static float calculateArticleRank(int rank){
		if(rank == 0)
			return 1;
		else 
			return (float) (1 + rank/15.0);
	}
	
	/**
	 * We currently don't penalize short articles on keywords.
	 * 
	 * 
	 * @param numTokens
	 * @return
	 */
	public static float calculateKeywordsBoost(int numTokens){
		return 1;
		/*
		if(numTokens > 2 * FastWikiTokenizerEngine.KEYWORD_TOKEN_LIMIT)
			return 1;
		else
			return ((float)numTokens)/FastWikiTokenizerEngine.KEYWORD_TOKEN_LIMIT/2;
		*/
	}

}
