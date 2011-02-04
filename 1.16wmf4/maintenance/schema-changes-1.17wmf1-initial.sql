-- schema-changes-1.17wmf1-initial.sql

-- patch-archive_ar_revid.sql
ALTER TABLE /*$wgDBprefix*/archive
	ADD INDEX ar_revid ( ar_rev_id );

-- patch-iwlinks.sql
CREATE TABLE /*_*/iwlinks (
  -- page_id of the referring page
  iwl_from int unsigned NOT NULL default 0,
  
  -- Interwiki prefix code of the target
  iwl_prefix varbinary(20) NOT NULL default '',

  -- Title of the target, including namespace
  iwl_title varchar(255) binary NOT NULL default ''
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/iwl_from ON /*_*/iwlinks (iwl_from, iwl_prefix, iwl_title);
CREATE UNIQUE INDEX /*i*/iwl_prefix_title_from ON /*_*/iwlinks (iwl_prefix, iwl_title, iwl_from);

-- patch-module_deps.sql
CREATE TABLE /*_*/module_deps (
  -- Module name
  md_module varbinary(255) NOT NULL,
  -- Skin name
  md_skin varbinary(32) NOT NULL,
  -- JSON blob with file dependencies
  md_deps mediumblob NOT NULL
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/md_module_skin ON /*_*/module_deps (md_module, md_skin);

-- patch-msg_resource.sql
CREATE TABLE /*_*/msg_resource (
  -- Resource name
  mr_resource varbinary(255) NOT NULL,
  -- Language code 
  mr_lang varbinary(32) NOT NULL,
  -- JSON blob. This is an incomplete JSON object, i.e. without the wrapping {}
  mr_blob mediumblob NOT NULL,
  -- Timestamp of last update
  mr_timestamp binary(14) NOT NULL
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/mr_resource_lang ON /*_*/msg_resource(mr_resource, mr_lang);

CREATE TABLE /*_*/msg_resource_links (
  mrl_resource varbinary(255) NOT NULL,
  -- Message key
  mrl_message varbinary(255) NOT NULL
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/mrl_message_resource ON /*_*/msg_resource_links (mrl_message, mrl_resource);

-- patch-iw_api_and_wikiid.sql
ALTER TABLE /*_*/interwiki
	ADD iw_api BLOB NOT NULL;
ALTER TABLE /*_*/interwiki
	ADD iw_wikiid varchar(64) NOT NULL;

-- patch-langlinks-ll_lang-20.sql
ALTER TABLE /*$wgDBprefix*/langlinks
	MODIFY `ll_lang`
	VARBINARY(20) NOT NULL DEFAULT '';

-- patch-afl_change_deleted_patrolled.sql
ALTER TABLE /*_*/abuse_filter_log MODIFY afl_deleted tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE /*_*/abuse_filter_log MODIFY afl_patrolled_by int unsigned NOT NULL DEFAULT 0;

