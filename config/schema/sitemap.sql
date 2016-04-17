CREATE TABLE `sitemap_urls` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `scope` varchar(255) DEFAULT NULL,
  `model` varchar(255) NOT NULL,
  `foreignKey` int(10) unsigned NOT NULL,
  `loc` varchar(255) DEFAULT NULL,
  `changefreq` varchar(20) DEFAULT NULL,
  `priority` int(4) DEFAULT NULL,
  `lastmod` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`model`,`foreignKey`,`scope`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
