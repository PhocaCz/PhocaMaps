ALTER TABLE `#__phocamaps_map` ADD COLUMN `mapy_mapset` varchar(10) NOT NULL default '';
ALTER TABLE `#__phocamaps_map` ADD COLUMN `mapy_names_overlay` varchar(10) NOT NULL default '';
ALTER TABLE `#__phocamaps_map` ADD COLUMN `mapy_switcher` varchar(10) NOT NULL default '';
ALTER TABLE `#__phocamaps_map` ADD COLUMN `mapy_zoom_control` varchar(10) NOT NULL default '';
ALTER TABLE `#__phocamaps_map` ADD COLUMN `mapy_zoom_wheel` varchar(10) NOT NULL default '';
ALTER TABLE `#__phocamaps_map` ADD COLUMN `mapy_current_position` varchar(10) NOT NULL default '';
ALTER TABLE `#__phocamaps_map` ADD COLUMN `mapy_full_screen` varchar(10) NOT NULL default '';
ALTER TABLE `#__phocamaps_map` ADD COLUMN `mapy_search` varchar(10) NOT NULL default '';
ALTER TABLE `#__phocamaps_map` ADD COLUMN `mapy_suggest` varchar(10) NOT NULL default '';
ALTER TABLE `#__phocamaps_map` ADD COLUMN `mapy_routing` varchar(10) NOT NULL default '';
ALTER TABLE `#__phocamaps_map` ADD COLUMN `mapy_point_info` varchar(10) NOT NULL default '';
ALTER TABLE `#__phocamaps_map` ADD COLUMN `mapy_panorama` varchar(10) NOT NULL default '';
ALTER TABLE `#__phocamaps_map` ADD COLUMN `mapy_static_map` varchar(10) NOT NULL default '';
ALTER TABLE `#__phocamaps_marker` ADD COLUMN `mapy_icon` varchar(100) NOT NULL default '';
ALTER TABLE `#__phocamaps_marker` ADD COLUMN `mapy_marker_color` varchar(20) NOT NULL default '';
ALTER TABLE `#__phocamaps_marker` ADD COLUMN `mapy_icon_color` varchar(20) NOT NULL default '';
ALTER TABLE `#__phocamaps_marker` ADD COLUMN `mapy_icon_prefix` varchar(20) NOT NULL default '';
ALTER TABLE `#__phocamaps_marker` ADD COLUMN `mapy_icon_spin` tinyint(1) NOT NULL default '0';
ALTER TABLE `#__phocamaps_marker` ADD COLUMN `mapy_icon_class` text;

CREATE TABLE IF NOT EXISTS `#__phocamaps_tags` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `alias` varchar(255) NOT NULL default '',
  `description` text,
  `published` tinyint(1) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime,
  `ordering` int(11) NOT NULL default '0',
  `params` text,
  `language` char(7) NOT NULL default '',
  PRIMARY KEY  (`id`)
) CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__phocamaps_tags_ref` (
  `id` SERIAL,
  `markerid` int(11) NOT NULL default 0,
  `tagid` int(11) NOT NULL default 0,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `i_markerid` (`markerid`,`tagid`)
) CHARACTER SET `utf8`;
