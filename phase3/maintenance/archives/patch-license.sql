CREATE TABLE /*_*/license (
  lic_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  lic_name varbinary(255) NOT NULL,
  lic_url varbinary(255) NOT NULL,
  lic_count int signed NOT NULL DEFAULT 0
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/lic_name ON /*_*/license (lic_name);
CREATE INDEX /*i*/lic_count ON /*_*/license (lic_count);
