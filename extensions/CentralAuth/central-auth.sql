
-- -- Some example steps for creating a new database for testing this:
-- CREATE DATABASE centralauth;
-- USE centralauth;
-- GRANT all on centralauth.* to 'wikiuser'@'localhost';
-- source central-auth.sql
-- source sample-data.sql

--
-- Global account data.
--
CREATE TABLE globaluser (
  -- Internal unique ID for the authentication server
  gu_id int auto_increment,
  
  -- Username.
  gu_name varchar(255) binary,

  -- Timestamp and method used to create the global account
  gu_enabled varchar(14) not null,
  gu_enabled_method enum('opt-in', 'batch', 'auto', 'admin'),

  -- Local database name of the user's 'home' wiki.
  -- By default, the 'winner' of a migration check for old accounts
  -- or the account the user was first registered at for new ones.
  -- May be changed over time.
  gu_home_db varchar(255) binary,
  
  -- Registered email address, may be empty.
  gu_email varchar(255) binary,
  
  -- Timestamp when the address was confirmed as belonging to the user.
  -- NULL if not confirmed.
  gu_email_authenticated char(14) binary,
  
  -- Salt and hashed password
  -- For migrated passwords, the salt is the local user_id.
  gu_salt varchar(16) binary,
  gu_password tinyblob,
  
  -- If true, this account cannot be used to log in on any wiki.
  gu_locked bool not null default 0,
  
  -- If true, this account should be hidden from most public user lists.
  -- Used for "deleting" accounts without breaking referential integrity.
  gu_hidden bool not null default 0,
  
  -- Registration time
  gu_registration varchar(14) binary,
  
  -- Random key for password resets
  gu_password_reset_key tinyblob,
  gu_password_reset_expiration varchar(14) binary,
  
  primary key (gu_id),
  unique key (gu_name),
  key (gu_email)
) /*$wgDBTableOptions*/;

--
-- Local linkage info, listing which wikis the username is registered on
-- and whether they've been attached to the global account.
--
-- Email and password information used for migration checks are grabbed
-- from local databases on demand when needed.
--
-- All local DBs will be swept on an opt-in check event.
--
CREATE TABLE localuser (
  lu_dbname varchar(32) binary not null,
  lu_name varchar(255) binary not null,
  lu_attached bool not null default 0,

  -- Migration status/logging information, to help diagnose issues
  lu_attached_timestamp varchar(14) binary,
  lu_attached_method enum (
    'primary',
    'empty',
    'mail',
    'password',
    'admin',
    'new'),

  primary key (lu_dbname, lu_name),
  key (lu_name, lu_dbname)
) /*$wgDBTableOptions*/;
