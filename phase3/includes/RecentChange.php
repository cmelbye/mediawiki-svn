<?php
# Utility class for creating new RC entries

define( "RC_EDIT", 0);
define( "RC_NEW", 1);
define( "RC_MOVE", 2);
define( "RC_LOG", 3);
define( "RC_MOVE_OVER_REDIRECT", 4);


/*
mAttributes:
	rc_timestamp    time the entry was made
	rc_cur_time     timestamp on the cur row
	rc_namespace    namespace #
	rc_title        non-prefixed db key
	rc_type          is new entry, used to determine whether updating is necessary
	rc_minor        is minor
	rc_cur_id       id of associated cur entry
	rc_user	        user id who made the entry
	rc_user_text    user name who made the entry
	rc_comment      edit summary
	rc_this_oldid   old_id associated with this entry (or zero)
	rc_last_oldid   old_id associated with the entry before this one (or zero)
	rc_bot          is bot, hidden
	rc_ip           IP address of the user in dotted quad notation
	rc_new          obsolete, use rc_type==RC_NEW

mExtra:
	prefixedDBkey   prefixed db key, used by external app via msg queue
	lastTimestamp   timestamp of previous entry, used in WHERE clause during update
	lang            the interwiki prefix, automatically set in save()
*/

class RecentChange
{
	var $mAttribs = array(), $mExtra = array();
	var $mTitle = false, $mMovedToTitle = false;

	# Factory methods
	
	/* static */ function newFromRow( $row ) 
	{
		$rc = new RecentChange;
		$rc->loadFromRow( $row );
		return $rc;
	}
	
	/* static */ function newFromCurRow( $row )
	{
		$rc = new RecentChange;
		$rc->loadFromCurRow( $row );
		return $rc;
	}

	# Accessors
	
	function setAttribs( $attribs ) 
	{
		$this->mAttribs = $attribs;
	}
	
	function setExtra( $extra )
	{
		$this->mExtra = $extra;
	}
	
	function getTitle()
	{
		if ( $this->mTitle === false ) {
			$this->mTitle = Title::makeTitle( $this->mAttribs['rc_namespace'], $this->mAttribs['rc_title'] );
		}
		return $this->mTitle;
	}

	function getMovedToTitle()
	{
		if ( $this->mMovedToTitle === false ) {
			$this->mMovedToTitle = Title::makeTitle( $this->mAttribs['rc_moved_to_ns'], 
				$this->mAttribs['rc_moved_to_title'] );
		}
		return $this->mMovedToTitle;
	}

	# Writes the data in this object to the database
	function save() 
	{
		global $wgUseRCQueue, $wgRCQueueID, $wgLocalInterwiki, $wgPutIPinRC;
		$fname = "RecentChange::save";
		
		if ( !is_array($this->mExtra) ) {
			$this->mExtra = array();
		}
		$this->mExtra['lang'] = $wgLocalInterwiki;
		
		if ( !$wgPutIPinRC ) {
			$this->mAttribs['rc_ip'] = '';
		}
		
		# Insert new row
		wfInsertArray( "recentchanges", $this->mAttribs, $fname );
		
		# Update old rows, if necessary
		if ( $this->mAttribs['rc_type'] == RC_EDIT ) {
			$oldid = $this->mAttribs['rc_last_oldid'];
			$ns = $this->mAttribs['rc_namespace'];
			$title = wfStrencode($this->mAttribs['rc_title']);
			$lastTime = $this->mExtra['lastTimestamp'];
			$now = $this->mAttribs['rc_timestamp'];
			$curId = $this->mAttribs['rc_cur_id'];
			
			# HACK HACK HACK on Jamesday's insistence
			# http://bugzilla.wikipedia.org/show_bug.cgi?id=730
			$age = time() - wfTimestamp2Unix( $lastTime );
			$maxage = 7 * 24 * 3600; # our one week cutoff
			if( $age < $maxage ) {
				# Update rc_this_oldid for the entries which were current
				$sql = "UPDATE recentchanges SET rc_this_oldid={$oldid} " .
					"WHERE rc_namespace=$ns AND rc_title='$title' AND rc_timestamp='$lastTime'";
				wfQuery( $sql, DB_WRITE, $fname );
			}

			# Update rc_cur_time
			$sql = "UPDATE recentchanges SET rc_cur_time='{$now}' " .
			  "WHERE rc_cur_id=" . $curId;
			wfQuery( $sql, DB_WRITE, $fname );
		}
		
		# Notify external application
		if ( $wgUseRCQueue ) {
			$queue = msg_get_queue( $wgRCQueueID );
			if (!msg_send( $queue, array_merge( $this->mAttribs, 1, $this->mExtra ), 
				true, false, $error )) 
			{
				wfDebug( "Error sending message to RC queue, code $error\n" );
			}
		}
	}
	
	# Makes an entry in the database corresponding to an edit
	/*static*/ function notifyEdit( $timestamp, &$title, $minor, &$user, $comment, 
		$oldId, $lastTimestamp, $bot = "default", $ip = '' ) 
	{
		if ( $bot == "default " ) {
			$bot = $user->isBot();
		}

		if ( !$ip ) {
			global $wgIP;
			$ip = empty( $wgIP ) ? '' : $wgIP;
		}
		
		$rc = new RecentChange;
		$rc->mAttribs = array(
			'rc_timestamp'	=> $timestamp,
			'rc_cur_time'	=> $timestamp,
			'rc_namespace'	=> $title->getNamespace(),
			'rc_title'	=> $title->getDBkey(),
			'rc_type'	=> RC_EDIT,
			'rc_minor'	=> $minor ? 1 : 0,
			'rc_cur_id'	=> $title->getArticleID(),
			'rc_user'	=> $user->getID(),
			'rc_user_text'	=> $user->getName(),
			'rc_comment'	=> $comment,
			'rc_this_oldid'	=> 0,
			'rc_last_oldid'	=> $oldId,
			'rc_bot'	=> $bot ? 1 : 0,
			'rc_moved_to_ns'	=> 0,
			'rc_moved_to_title'	=> '',
			'rc_ip'	=> $ip,
			'rc_new'	=> 0 # obsolete
		);
		
		$rc->mExtra =  array(
			'prefixedDBkey'	=> $title->getPrefixedDBkey(),
			'lastTimestamp' => $lastTimestamp
		);
		$rc->save();
	}
	
	# Makes an entry in the database corresponding to page creation
	# Note: the title object must be loaded with the new id using resetArticleID()
	/*static*/ function notifyNew( $timestamp, &$title, $minor, &$user, $comment, $bot = "default", $ip='' )
	{
		if ( !$ip ) {
			global $wgIP;
			$ip = empty( $wgIP ) ? '' : $wgIP;
		}
		if ( $bot == "default" ) {
			$bot = $user->isBot();
		}

		$rc = new RecentChange;
		$rc->mAttribs = array(
			'rc_timestamp'      => $timestamp,
			'rc_cur_time'       => $timestamp,
			'rc_namespace'      => $title->getNamespace(),
			'rc_title'          => $title->getDBkey(),
			'rc_type'           => RC_NEW,
			'rc_minor'          => $minor ? 1 : 0,
			'rc_cur_id'         => $title->getArticleID(),
			'rc_user'           => $user->getID(),
			'rc_user_text'      => $user->getName(),
			'rc_comment'        => $comment,
			'rc_this_oldid'     => 0,
			'rc_last_oldid'     => 0,
			'rc_bot'            => $bot ? 1 : 0,
			'rc_moved_to_ns'    => 0,
			'rc_moved_to_title' => '',
			'rc_ip'             => $ip,
			'rc_new'	=> 1 # obsolete
		);
		
		$rc->mExtra =  array(
			'prefixedDBkey'	=> $title->getPrefixedDBkey(),
			'lastTimestamp' => 0
		);
		$rc->save();
	}
	
	# Makes an entry in the database corresponding to a rename
	/*static*/ function notifyMove( $timestamp, &$oldTitle, &$newTitle, &$user, $comment, $ip='', $overRedir = false )
	{
		if ( !$ip ) {
			global $wgIP;
			$ip = empty( $wgIP ) ? '' : $wgIP;
		}
		$rc = new RecentChange;
		$rc->mAttribs = array(
			'rc_timestamp'	=> $timestamp,
			'rc_cur_time'	=> $timestamp,
			'rc_namespace'	=> $oldTitle->getNamespace(),
			'rc_title'	=> $oldTitle->getDBkey(),
			'rc_type'	=> $overRedir ? RC_MOVE_OVER_REDIRECT : RC_MOVE,
			'rc_minor'	=> 0,
			'rc_cur_id'	=> $oldTitle->getArticleID(),
			'rc_user'	=> $user->getID(),
			'rc_user_text'	=> $user->getName(),
			'rc_comment'	=> $comment,
			'rc_this_oldid'	=> 0,
			'rc_last_oldid'	=> 0,
			'rc_bot'	=> $user->isBot() ? 1 : 0,
			'rc_moved_to_ns'	=> $newTitle->getNamespace(),
			'rc_moved_to_title'	=> $newTitle->getDBkey(),
			'rc_ip'		=> $ip,
			'rc_new'	=> 0 # obsolete
		);
		
		$rc->mExtra = array(
			'prefixedDBkey'	=> $oldTitle->getPrefixedDBkey(),
			'lastTimestamp' => 0,
			'prefixedMoveTo'	=> $newTitle->getPrefixedDBkey()
		);
		$rc->save();
	}
	
	/* static */ function notifyMoveToNew( $timestamp, &$oldTitle, &$newTitle, &$user, $comment, $ip='' ) {
		RecentChange::notifyMove( $timestamp, $oldTitle, $newTitle, $user, $comment, $ip, false );
	}

	/* static */ function notifyMoveOverRedirect( $timestamp, &$oldTitle, &$newTitle, &$user, $comment, $ip='' ) {
		RecentChange::notifyMove( $timestamp, $oldTitle, $newTitle, $user, $comment, $ip='', true );
	}

	# A log entry is different to an edit in that previous revisions are 
	# not kept
	/*static*/ function notifyLog( $timestamp, &$title, &$user, $comment, $ip='' )
	{
		if ( !$ip ) {
			global $wgIP;
			$ip = empty( $wgIP ) ? '' : $wgIP;
		}
		$rc = new RecentChange;
		$rc->mAttribs = array(
			'rc_timestamp'	=> $timestamp,
			'rc_cur_time'	=> $timestamp,
			'rc_namespace'	=> $title->getNamespace(),
			'rc_title'	=> $title->getDBkey(),
			'rc_type'	=> RC_LOG,
			'rc_minor'	=> 0,
			'rc_cur_id'	=> $title->getArticleID(),
			'rc_user'	=> $user->getID(),
			'rc_user_text'	=> $user->getName(),
			'rc_comment'	=> $comment,
			'rc_this_oldid'	=> 0,
			'rc_last_oldid'	=> 0,
			'rc_bot'	=> 0,
			'rc_moved_to_ns'	=> 0,
			'rc_moved_to_title'	=> '',
			'rc_ip'	=> $ip,
			'rc_new'	=> 0 # obsolete
		);
		$rc->mExtra =  array(
			'prefixedDBkey'	=> $title->getPrefixedDBkey(),
			'lastTimestamp' => 0
		);
		$rc->save();
	}

	# Initialises the members of this object from a mysql row object
	function loadFromRow( $row )
	{
		$this->mAttribs = get_object_vars( $row );
		$this->mExtra = array();
	}
	
	# Makes a pseudo-RC entry from a cur row, for watchlists and things
	function loadFromCurRow( $row )
	{
		$this->mAttribs = array(
			"rc_timestamp" => $row->cur_timestamp,
			"rc_cur_time" => $row->cur_timestamp,
			"rc_user" => $row->cur_user,
			"rc_user_text" => $row->cur_user_text,
			"rc_namespace" => $row->cur_namespace,
			"rc_title" => $row->cur_title,
			"rc_comment" => $row->cur_comment,
			"rc_minor" => !!$row->cur_minor_edit,
			"rc_type" => $row->cur_is_new ? RC_NEW : RC_EDIT,
			"rc_cur_id" => $row->cur_id,
			'rc_this_oldid'	=> 0,
			'rc_last_oldid'	=> 0,
			'rc_bot'	=> 0,
			'rc_moved_to_ns'	=> 0,
			'rc_moved_to_title'	=> '',
			'rc_ip' => '',
			'rc_new' => $row->cur_is_new # obsolete
		);

		$this->mExtra = array();
	}


	# Gets the end part of the diff URL assoicated with this object
	# Blank if no diff link should be displayed
	function diffLinkTrail( $forceCur )
	{
		if ( $this->mAttribs['rc_type'] == RC_EDIT ) {
			$trail = "curid=" . (int)($this->mAttribs['rc_cur_id']) .
				"&oldid=" . (int)($this->mAttribs['rc_last_oldid']);
			if ( $forceCur ) {
				$trail .= "&diff=0" ;
			} else {
				$trail .= "&diff=" . (int)($this->mAttribs['rc_this_oldid']);
			}
		} else {
			$trail = "";
		}
		return $trail;
	}
}
?>
