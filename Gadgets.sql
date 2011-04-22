-- Gadgets 3.0 database schema shared between MySQL and SQLite

-- Gadget definitions
CREATE TABLE /*_*/gadgets (
	-- Gadget id. Not shown anywhere in UI.
	ga_id int PRIMARY KEY AUTO_INCRMENT NOT NULL,

	-- Gadget internal name. Length restricted by user_properties.up_property length (32) - length( 'gadget-' ) (7)
	ga_name varchar(25) binary NOT NULL,

	-- If true, gadget's scripts don't support ResourceLoader
	ga_legacy bool default FALSE,

	-- Whether this gadget is enabled by default for everyone
	ga_default bool default FALSE,

	-- If true, gadget cannot be used by end-users directly, it just creates
	-- a ResourceLoader module that can be reused by other modules
	ga_resource_only bool default FALSE
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/ga_names ON /*_*/gadgets;

-- Tracks what resources our gadget uses
CREATE TABLE /*_*/gadget_resources (
	-- References gadgets.ga_id
	gr_gadget int PRIMARY KEY NOT NULL,

	-- Resource type ('script' or 'style')
	gr_type varchar(16) binary NOT NULL,

	-- On-wiki path to resource
	gr_path varchar(255) binary NOT NULL
) /*$wgDBTableOptions*/;

-- Tracks dependencies between gadgets and ResourceLoader modules
CREATE TABLE /*_*/gadget_dependencies (
	-- References gadgets.ga_id
	gd_gadget int PRIMARY KEY NOT NULL,

	-- Module name
	gd_module varbinary(255) NOT NULL
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/gd_modules ON /*_*/gadget_dependencies;

-- Tracks user permissions required by gadgets
CREATE TABLE /*_*/gadget_rights (
	-- References gadgets.ga_id
	grt_gadget int PRIMARY KEY NOT NULL,

	-- Permission
	grt_right varchar(64) binary NOT NULL
) /*$wgDBTableOptions*/;

