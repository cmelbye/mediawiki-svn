-- MySQL version of the database schema for the Semantic Watchlist extension.
-- Licence: GNU GPL v3+
-- Author: Jeroen De Dauw < jeroendedauw@gmail.com >

-- Watchlist groups
CREATE TABLE IF NOT EXISTS /*$wgDBprefix*/swl_groups (
  group_id                 SMALLINT unsigned   NOT NULL auto_increment PRIMARY KEY,
  -- No need to have this stuff relational, so keep it simple.
  -- These fields keep the IDs (or names in case of the properties), | separated.
  group_categories         BLOB                NOT NULL,
  group_namespaces         BLOB                NOT NULL,
  group_properties         BLOB                NOT NULL
) /*$wgDBTableOptions*/;

-- List of all changes made to properties.
CREATE TABLE IF NOT EXISTS /*$wgDBprefix*/swl_changes (
  change_id                INT(10) unsigned    NOT NULL auto_increment PRIMARY KEY,
  change_group_id          INT(10) unsigned    NOT NULL, -- This does NOT refer to the swl_groups table, but rather "groups of changes"
  change_user_name         VARCHAR(255)        NOT NULL, -- The person that made the modification (account name or ip)
  change_page_id           INT(10) unsigned    NOT NULL, -- The id of the page the modification was on
  change_property          VARCHAR(255)        NOT NULL, -- Name of the property of which a value was changed
  change_old_value         BLOB                NULL, -- The old value of the property (null for an adittion)
  change_new_value         BLOB                NULL -- The new value of the property (null for a deletion)
) /*$wgDBTableOptions*/;

-- Links changes to watchlist groups.
CREATE TABLE IF NOT EXISTS /*$wgDBprefix*/swl_changes_per_group (
  cpg_group_id             SMALLINT unsigned   NOT NULL,
  cpg_change_id            INT(10) unsigned    NOT NULL,
  PRIMARY KEY  (cpg_group_id,cpg_change_id)
) /*$wgDBTableOptions*/;

-- Links users to watchlist groups.
CREATE TABLE IF NOT EXISTS /*$wgDBprefix*/swl_users_per_group (
  upg_group_id             SMALLINT unsigned   NOT NULL,
  upg_user_id              INT(10) unsigned    NOT NULL,
  PRIMARY KEY  (upg_group_id,upg_user_id)
) /*$wgDBTableOptions*/;