-- -------------------------------------------------------------------- --
-- Phoca Maps Download manual installation                                   --
-- -------------------------------------------------------------------- --
-- See documentation on http://www.phoca.cz/                            --
--                                                                      --
-- Change all prefixes #__ to prefix which is set in your Joomla! site  --
-- (e.g. from #__phocamaps to jos_phocamaps)                    --
-- Run this SQL queries in your database tool, e.g. in phpMyAdmin       --
-- If you have questions, just ask in Phoca Forum                       --
-- http://www.phoca.cz/forum/                                           --
-- -------------------------------------------------------------------- --

CREATE TABLE IF NOT EXISTS `#__phocamaps_map` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `alias` varchar(255) NOT NULL default '',
  `width` int(5) NOT NULL default '0',
  `height` int(5) NOT NULL default '0',
  `latitude` varchar(20) NOT NULL default '',
  `longitude` varchar(20) NOT NULL default '',
  `zoom` int(3) NOT NULL default '0',
  `lang` varchar(6) NOT NULL default '',
  `border` tinyint(1) NOT NULL default '0',
  `continuouszoom` tinyint(1) NOT NULL default '0',
  `doubleclickzoom` tinyint(1) NOT NULL default '0',
  `scrollwheelzoom` tinyint(1) NOT NULL default '0',
  `zoomcontrol` tinyint(1) NOT NULL default '0',
  `scalecontrol` tinyint(1) NOT NULL default '0',
  `typeid` tinyint(1) NOT NULL default '0',
  `typecontrol` tinyint(1) NOT NULL default '0',
  `typecontrolposition` tinyint(1) NOT NULL default '0',
  `collapsibleoverview` tinyint(1) NOT NULL default '0',
  `dynamiclabel` tinyint(1) NOT NULL default '0',
  `googlebar` tinyint(1) NOT NULL default '0',
  `displayroute` tinyint(1) NOT NULL default '0',
  `kmlfile` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `published` tinyint(1) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL default '0',
  `access` tinyint(3) unsigned NOT NULL default '0',
  `hits` int(11) NOT NULL default '0',
  `params` text NOT NULL,
  `language` char(7) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `cat_idx` (`published`,`access`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`)
) CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__phocamaps_marker` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `catid` int(11) NOT NULL default '0',
  `title` varchar(250) NOT NULL default '',
  `alias` varchar(255) NOT NULL default '',
  `latitude` varchar(20) NOT NULL default '',
  `longitude` varchar(20) NOT NULL default '',
  `gpslatitude` varchar(50) NOT NULL default '',
  `gpslongitude` varchar(50) NOT NULL default '',
  `displaygps` tinyint(1) NOT NULL default '0',
  `icon` tinyint(1) NOT NULL default '0',
  `iconext` int(11) NOT NULL default '0',
  `description` text NOT NULL,
  `contentwidth` varchar(8) NOT NULL default '',
  `contentheight` varchar(8) NOT NULL default '',
  `markerwindow` tinyint(1) NOT NULL default '0',
  `published` tinyint(1) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL default '0',
  `access` tinyint(3) unsigned NOT NULL default '0',
  `hits` int(11) NOT NULL default '0',
  `params` text NOT NULL,
  `language` char(7) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `catid` (`catid`,`published`)
) CHARACTER SET `utf8`;


CREATE TABLE IF NOT EXISTS `#__phocamaps_icon` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(250) NOT NULL default '',
  `alias` varchar(255) NOT NULL default '',
  `url` text NOT NULL,
  `urls` text NOT NULL,
  `object` varchar(255) NOT NULL default '',
  `objects` varchar(255) NOT NULL default '',
  `objectshape` varchar(255) NOT NULL default '',
  `published` tinyint(1) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL default '0',
  `access` tinyint(3) unsigned NOT NULL default '0',
  `hits` int(11) NOT NULL default '0',
  `params` text NOT NULL,
  `language` char(7) NOT NULL default '',
  PRIMARY KEY  (`id`)
) CHARACTER SET `utf8`;

INSERT INTO `#__phocamaps_icon` (`id`, `title`, `alias`, `url`, `urls`, `object`, `objects`, `objectshape`, `published`, `checked_out`, `checked_out_time`, `ordering`, `access`, `hits`, `params`, `language`) VALUES
(1, 'Tree', 'tree', 'http://maps.google.com/mapfiles/ms/icons/tree.png', 'http://maps.google.com/mapfiles/ms/icons/tree.shadow.png', '', '59,32;0,0;16,34', 'rect;0,0,25,30', 1, 0, '0000-00-00 00:00:00', 1, 1, 0, '', ''),
(2, 'Pushpin', 'pushpin', 'http://maps.google.com/mapfiles/ms/icons/red-pushpin.png', '', '', '', 'rect;0,0,25,30', 1, 0, '0000-00-00 00:00:00', 2, 1, 0, '', ''),
(3, 'Blue Dot', 'blue-dot', 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png', '', '', '', 'rect;0,0,25,30', 1, 0, '0000-00-00 00:00:00', 3, 1, 0, '', ''),
(4, 'Flag', 'flag', 'http://maps.google.com/mapfiles/ms/icons/flag.png', 'http://maps.google.com/mapfiles/ms/icons/flag.shadow.png', '', '59,32;0,0;16,34', 'rect;0,0,25,30', 1, 0, '0000-00-00 00:00:00', 3, 1, 0, '', ''),
(5, 'Info', 'info', 'http://maps.google.com/mapfiles/ms/icons/info.png', 'http://maps.google.com/mapfiles/ms/icons/info.shadow.png', '', '59,32;0,0;16,34', 'rect;0,0,25,30', 1, 0, '0000-00-00 00:00:00', 5, 1, 0, '', ''),
(6, 'Snack Bar', 'snack-bar', 'http://maps.google.com/mapfiles/ms/icons/snack_bar.png', 'http://maps.google.com/mapfiles/ms/icons/snack_bar.shadow.png', '', '59,32;0,0;16,34', 'rect;0,0,32,30', 1, 0, '0000-00-00 00:00:00', 6, 1, 0, '', '');


-- UTF-8 test: �,�,�