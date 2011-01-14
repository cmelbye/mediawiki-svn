CREATE TABLE /*_*/file_props (
  fp_rev_id int unsigned NOT NULL,
  fp_key varbinary(255) NOT NULL,
  fp_value_int int signed,
  fp_value_text varbinary(255)
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/fp_rev_id_key ON /*_*/file_props (fp_rev_id, fp_key);
CREATE INDEX /*i*/fp_key_value_int ON /*_*/file_props (fp_key, fp_value_int);
CREATE INDEX /*i*/fp_key_value_text ON /*_*/file_props (fp_key, fp_value_text);
