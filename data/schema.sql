-- Create syntax for TABLE 'pages'
CREATE TABLE `docs_pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `index` varchar(100) NOT NULL DEFAULT '',
  `parent` varchar(200) DEFAULT NULL,
  `name` varchar(200) NOT NULL DEFAULT '',
  `file` varchar(200) NOT NULL DEFAULT '',
  `info` text,
  `content` text,
  PRIMARY KEY (`id`),
  KEY `index` (`index`),
  KEY `parent` (`parent`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9483 DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'symbols'
CREATE TABLE `docs_symbols` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `index` varchar(100) NOT NULL DEFAULT '',
  `parent` varchar(200) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `type` varchar(50) NOT NULL,
  `docblock` text,
  `source` text,
  `file` varchar(200) DEFAULT '',
  `visibility` varchar(20) DEFAULT NULL,
  `inherited` varchar(200) DEFAULT NULL,
  `extends` varchar(200) DEFAULT NULL,
  `overrides` varchar(200) DEFAULT NULL,
  `interfaces` text,
  `traits` text,
  `is_deprecated` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_static` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_abstract` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_magic` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_external` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `index` (`index`),
  KEY `parent` (`parent`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=112021 DEFAULT CHARSET=utf8;
