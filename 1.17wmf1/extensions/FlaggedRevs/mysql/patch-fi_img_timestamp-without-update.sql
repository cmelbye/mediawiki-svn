-- Fix bad fi_img_timestamp definition
ALTER TABLE /*$wgDBprefix*/flaggedimages
    CHANGE fi_img_timestamp fi_img_timestamp varbinary(14) NULL;
