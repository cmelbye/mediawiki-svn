-- patch-rd_interwiki.sql
ALTER TABLE /*$wgDBprefix*/redirect
	ADD rd_interwiki varchar(32) default NULL,
	ADD rd_fragment varchar(255) binary default NULL;

-- patch-categorylinks-better-collation.sql
ALTER TABLE /*$wgDBprefix*/categorylinks
	CHANGE COLUMN cl_sortkey cl_sortkey varbinary(230) NOT NULL default '',
	ADD COLUMN cl_sortkey_prefix varchar(255) binary NOT NULL default '',
	ADD COLUMN cl_collation varbinary(32) NOT NULL default '',
	ADD COLUMN cl_type ENUM('page', 'subcat', 'file') NOT NULL default 'page',
	ADD INDEX (cl_collation),
	DROP INDEX cl_sortkey,
	ADD INDEX cl_sortkey (cl_to, cl_type, cl_sortkey, cl_from);
INSERT IGNORE INTO /*$wgDBprefix*/updatelog (ul_key) VALUES ('cl_fields_update');

