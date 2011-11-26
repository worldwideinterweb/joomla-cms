CREATE TABLE IF NOT EXISTS `#__art_adminer_setting` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `cssfile` varchar(255) NOT NULL,
  `autologin` tinyint(1),
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `#__art_adminer_setting` (`id`, `cssfile`, `autologin`)  VALUES (1, 'adminer2.css', 1) ON DUPLICATE KEY UPDATE id=id;
