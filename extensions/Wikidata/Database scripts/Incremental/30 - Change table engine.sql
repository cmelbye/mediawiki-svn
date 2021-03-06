-- These tables can exist within each dataset.
ALTER TABLE `archive` ENGINE = MyISAM;
ALTER TABLE `categorylinks` ENGINE = MyISAM;
ALTER TABLE `externallinks` ENGINE = MyISAM;
ALTER TABLE `filearchive` ENGINE = MyISAM;
ALTER TABLE `image` ENGINE = MyISAM;
ALTER TABLE `imagelinks` ENGINE = MyISAM;
ALTER TABLE `interwiki` ENGINE = MyISAM;
ALTER TABLE `ipblocks` ENGINE = MyISAM;
ALTER TABLE `job` ENGINE = MyISAM;
ALTER TABLE `langlinks` ENGINE = MyISAM;
ALTER TABLE `language` ENGINE = MyISAM;
ALTER TABLE `language_names` ENGINE = MyISAM;
ALTER TABLE `logging` ENGINE = MyISAM;
ALTER TABLE `math` ENGINE = MyISAM;
ALTER TABLE `namespace` ENGINE = MyISAM;
ALTER TABLE `namespace_names` ENGINE = MyISAM;
ALTER TABLE `objectcache` ENGINE = MyISAM;
ALTER TABLE `objects` ENGINE = MyISAM;
ALTER TABLE `oldimage` ENGINE = MyISAM;
ALTER TABLE `page` ENGINE = MyISAM;
ALTER TABLE `page_restrictions` ENGINE = MyISAM;
ALTER TABLE `pagelinks` ENGINE = MyISAM;
ALTER TABLE `querycache` ENGINE = MyISAM;
ALTER TABLE `querycache_info` ENGINE = MyISAM;
ALTER TABLE `querycachetwo` ENGINE = MyISAM;
ALTER TABLE `recentchanges` ENGINE = MyISAM;
ALTER TABLE `redirect` ENGINE = MyISAM;
ALTER TABLE `revision` ENGINE = MyISAM;
ALTER TABLE `script_log` ENGINE = MyISAM;
ALTER TABLE `site_stats` ENGINE = MyISAM;
ALTER TABLE `templatelinks` ENGINE = MyISAM;
ALTER TABLE `text` ENGINE = MyISAM;
ALTER TABLE `trackbacks` ENGINE = MyISAM;
ALTER TABLE `transcache` ENGINE = MyISAM;
ALTER TABLE `user` ENGINE = MyISAM;
ALTER TABLE `user_groups` ENGINE = MyISAM;
ALTER TABLE `user_newtalk` ENGINE = MyISAM;
ALTER TABLE `watchlist` ENGINE = MyISAM;
ALTER TABLE `wikidata_sets` ENGINE = MyISAM;

INSERT INTO `script_log` (`time`, `script_name`, `comment`) VALUES (NOW(), '30 - Change table engine.sql', 'change the engine from MyISAM to MyISAM for Wikidata tables ');