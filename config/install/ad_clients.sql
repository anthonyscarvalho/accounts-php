
CREATE TABLE IF NOT EXISTS `ad_clients` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `campaigns` int(10) NOT NULL,
  `clients` int(10) NOT NULL,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
