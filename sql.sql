-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `dataObjectField`;
CREATE TABLE `dataObjectField` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) unsigned NOT NULL,
  `propertyName` varchar(255) NOT NULL,
  `fieldName` varchar(255) NOT NULL,
  `dataType` int(10) unsigned NOT NULL,
  `defaultValue` varchar(255) NOT NULL,
  `localName` varchar(255) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `dataObjectField` (`id`, `deleted`, `propertyName`, `fieldName`, `dataType`, `defaultValue`, `localName`, `type`) VALUES
  (1,	0,	'email',	'email',	1,	'0',	'a:6:{s:10:\"nominative\";s:5:\"email\";s:8:\"genitive\";s:5:\"email\";s:6:\"dative\";s:5:\"email\";s:8:\"accusive\";s:5:\"email\";s:8:\"creative\";s:5:\"email\";s:13:\"prepositional\";s:5:\"email\";}',	1),
  (2,	0,	'password',	'password',	6,	'0',	'a:6:{s:10:\"nominative\";s:8:\"password\";s:8:\"genitive\";s:8:\"password\";s:6:\"dative\";s:8:\"password\";s:8:\"accusive\";s:8:\"password\";s:8:\"creative\";s:8:\"password\";s:13:\"prepositional\";s:8:\"password\";}',	1),
  (3,	0,	'type',	'type',	2,	'0',	'a:6:{s:10:\"nominative\";s:4:\"type\";s:8:\"genitive\";s:4:\"type\";s:6:\"dative\";s:4:\"type\";s:8:\"accusive\";s:4:\"type\";s:8:\"creative\";s:4:\"type\";s:13:\"prepositional\";s:4:\"type\";}',	1),
  (4,	0,	'title',	'title',	1,	'',	'a:6:{s:10:\"nominative\";s:5:\"title\";s:8:\"genitive\";s:5:\"title\";s:6:\"dative\";s:5:\"title\";s:8:\"accusive\";s:5:\"title\";s:8:\"creative\";s:5:\"title\";s:13:\"prepositional\";s:5:\"title\";}',	1),
  (5,	0,	'keywords',	'keywords',	9,	'',	'a:6:{s:10:\"nominative\";s:8:\"keywords\";s:8:\"genitive\";s:8:\"keywords\";s:6:\"dative\";s:8:\"keywords\";s:8:\"accusive\";s:8:\"keywords\";s:8:\"creative\";s:8:\"keywords\";s:13:\"prepositional\";s:8:\"keywords\";}',	1),
  (6,	0,	'description',	'description',	9,	'',	'a:6:{s:10:\"nominative\";s:11:\"description\";s:8:\"genitive\";s:11:\"description\";s:6:\"dative\";s:11:\"description\";s:8:\"accusive\";s:11:\"description\";s:8:\"creative\";s:11:\"description\";s:13:\"prepositional\";s:11:\"description\";}',	1),
  (7,	0,	'content',	'content',	9,	'',	'a:6:{s:10:\"nominative\";s:7:\"content\";s:8:\"genitive\";s:7:\"content\";s:6:\"dative\";s:7:\"content\";s:8:\"accusive\";s:7:\"content\";s:8:\"creative\";s:7:\"content\";s:13:\"prepositional\";s:7:\"content\";}',	1),
  (8,	0,	'url',	'url',	1,	'',	'a:6:{s:10:\"nominative\";s:3:\"url\";s:8:\"genitive\";s:3:\"url\";s:6:\"dative\";s:3:\"url\";s:8:\"accusive\";s:3:\"url\";s:8:\"creative\";s:3:\"url\";s:13:\"prepositional\";s:3:\"url\";}',	1),
  (9,	0,	'status',	'status',	2,	'0',	'a:6:{s:10:\"nominative\";s:6:\"status\";s:8:\"genitive\";s:6:\"status\";s:6:\"dative\";s:6:\"status\";s:8:\"accusive\";s:6:\"status\";s:8:\"creative\";s:6:\"status\";s:13:\"prepositional\";s:6:\"status\";}',	1),
  (40,	0,	'name',	'name',	1,	'',	'a:6:{s:10:\"nominative\";s:4:\"name\";s:8:\"genitive\";s:4:\"name\";s:6:\"dative\";s:4:\"name\";s:8:\"accusive\";s:4:\"name\";s:8:\"creative\";s:4:\"name\";s:13:\"prepositional\";s:4:\"name\";}',	0),
  (41,	0,	'fileName',	'fileName',	1,	'',	'a:6:{s:10:\"nominative\";s:8:\"fileName\";s:8:\"genitive\";s:8:\"fileName\";s:6:\"dative\";s:8:\"fileName\";s:8:\"accusive\";s:8:\"fileName\";s:8:\"creative\";s:8:\"fileName\";s:13:\"prepositional\";s:8:\"fileName\";}',	0),
  (42,	0,	'size',	'size',	2,	'0',	'a:6:{s:10:\"nominative\";s:4:\"size\";s:8:\"genitive\";s:4:\"size\";s:6:\"dative\";s:4:\"size\";s:8:\"accusive\";s:4:\"size\";s:8:\"creative\";s:4:\"size\";s:13:\"prepositional\";s:4:\"size\";}',	0),
  (43,	0,	'width',	'width',	2,	'0',	'a:6:{s:10:\"nominative\";s:5:\"width\";s:8:\"genitive\";s:5:\"width\";s:6:\"dative\";s:5:\"width\";s:8:\"accusive\";s:5:\"width\";s:8:\"creative\";s:5:\"width\";s:13:\"prepositional\";s:5:\"width\";}',	0),
  (44,	0,	'height',	'height',	2,	'0',	'a:6:{s:10:\"nominative\";s:6:\"height\";s:8:\"genitive\";s:6:\"height\";s:6:\"dative\";s:6:\"height\";s:8:\"accusive\";s:6:\"height\";s:8:\"creative\";s:6:\"height\";s:13:\"prepositional\";s:6:\"height\";}',	0),
  (45,	0,	'caption',	'caption',	1,	'',	'a:6:{s:10:\"nominative\";s:7:\"caption\";s:8:\"genitive\";s:7:\"caption\";s:6:\"dative\";s:7:\"caption\";s:8:\"accusive\";s:7:\"caption\";s:8:\"creative\";s:7:\"caption\";s:13:\"prepositional\";s:7:\"caption\";}',	0),
  (46,	0,	'name',	'name',	1,	'',	'a:6:{s:10:\"nominative\";s:4:\"name\";s:8:\"genitive\";s:4:\"name\";s:6:\"dative\";s:4:\"name\";s:8:\"accusive\";s:4:\"name\";s:8:\"creative\";s:4:\"name\";s:13:\"prepositional\";s:4:\"name\";}',	0),
  (47,	0,	'fileName',	'fileName',	1,	'',	'a:6:{s:10:\"nominative\";s:8:\"fileName\";s:8:\"genitive\";s:8:\"fileName\";s:6:\"dative\";s:8:\"fileName\";s:8:\"accusive\";s:8:\"fileName\";s:8:\"creative\";s:8:\"fileName\";s:13:\"prepositional\";s:8:\"fileName\";}',	0),
  (48,	0,	'size',	'size',	2,	'0',	'a:6:{s:10:\"nominative\";s:4:\"size\";s:8:\"genitive\";s:4:\"size\";s:6:\"dative\";s:4:\"size\";s:8:\"accusive\";s:4:\"size\";s:8:\"creative\";s:4:\"size\";s:13:\"prepositional\";s:4:\"size\";}',	0),
  (49,	0,	'caption',	'caption',	1,	'',	'a:6:{s:10:\"nominative\";s:7:\"caption\";s:8:\"genitive\";s:7:\"caption\";s:6:\"dative\";s:7:\"caption\";s:8:\"accusive\";s:7:\"caption\";s:8:\"creative\";s:7:\"caption\";s:13:\"prepositional\";s:7:\"caption\";}',	0);

DROP TABLE IF EXISTS `dataObjectMap`;
CREATE TABLE `dataObjectMap` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `dataObjectMap` (`id`, `deleted`, `name`, `type`) VALUES
  (1,	0,	'user',	1),
  (10,	0,	'textPage',	1),
  (22,	0,	'image',	1),
  (23,	0,	'file',	0);

DROP TABLE IF EXISTS `file`;
CREATE TABLE `file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fileName` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `size` int(10) NOT NULL DEFAULT '0',
  `caption` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `image`;
CREATE TABLE `image` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fileName` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `size` int(10) NOT NULL DEFAULT '0',
  `width` int(10) NOT NULL DEFAULT '0',
  `height` int(10) NOT NULL DEFAULT '0',
  `caption` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `link_dataObjectMap_dataObjectField`;
CREATE TABLE `link_dataObjectMap_dataObjectField` (
  `dataObjectMapId` int(10) unsigned NOT NULL,
  `dataObjectFieldId` int(10) unsigned NOT NULL,
  PRIMARY KEY (`dataObjectMapId`,`dataObjectFieldId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `link_dataObjectMap_dataObjectField` (`dataObjectMapId`, `dataObjectFieldId`) VALUES
  (1,	1),
  (1,	2),
  (1,	3),
  (10,	4),
  (10,	5),
  (10,	6),
  (10,	7),
  (10,	8),
  (10,	9),
  (22,	40),
  (22,	41),
  (22,	42),
  (22,	43),
  (22,	44),
  (22,	45),
  (23,	46),
  (23,	47),
  (23,	48),
  (23,	49);

DROP TABLE IF EXISTS `link_file_object`;
CREATE TABLE `link_file_object` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fileId` int(10) NOT NULL DEFAULT '0',
  `objectId` int(10) NOT NULL DEFAULT '0',
  `object` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `group` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `fileId` (`fileId`),
  KEY `object` (`object`),
  KEY `objectId` (`objectId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `link_image_object`;
CREATE TABLE `link_image_object` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `imageId` int(10) NOT NULL DEFAULT '0',
  `objectId` int(10) NOT NULL DEFAULT '0',
  `object` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `group` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `imageId` (`imageId`),
  KEY `object` (`object`),
  KEY `objectId` (`objectId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `link_referenceField_dataObjectMap`;
CREATE TABLE `link_referenceField_dataObjectMap` (
  `dataObjectMapId` int(10) unsigned NOT NULL,
  `referenceFieldId` int(10) unsigned NOT NULL,
  UNIQUE KEY `referenceFieldId` (`referenceFieldId`,`dataObjectMapId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `link_referenceMap_referenceField`;
CREATE TABLE `link_referenceMap_referenceField` (
  `referenceMapId` int(10) unsigned NOT NULL,
  `referenceFieldId` int(10) unsigned NOT NULL,
  `referenceFieldType` int(11) NOT NULL COMMENT '1 - основной объект, 2 - зависимый объект, 3 - дополнительный объект; 4 - свойство.',
  PRIMARY KEY (`referenceMapId`,`referenceFieldId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `referenceField`;
CREATE TABLE `referenceField` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) unsigned NOT NULL,
  `propertyName` varchar(255) NOT NULL,
  `fieldName` varchar(255) NOT NULL,
  `dataType` int(10) unsigned NOT NULL,
  `defaultValue` varchar(255) NOT NULL,
  `localName` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `referenceMap`;
CREATE TABLE `referenceMap` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `textPage`;
CREATE TABLE `textPage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) unsigned NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `keywords` text NOT NULL,
  `description` text NOT NULL,
  `content` text NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT '',
  `status` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) unsigned NOT NULL,
  `password` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `type` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user` (`id`, `deleted`, `password`, `email`, `type`) VALUES
  (1, 0, '', 'guest', 0),
  (2,	0,	'$2y$10$35H0uSXy4UPx9nxScyoHbOtijxHUKeWYJN7KnexK8hngt1AMxTmUe',	'xpundel@gmail.com',	100);


