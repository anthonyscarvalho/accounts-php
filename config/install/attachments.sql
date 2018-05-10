
CREATE TABLE IF NOT EXISTS `attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clients` int(10) DEFAULT NULL,
  `description` text,
  `date` date DEFAULT NULL,
  `attachment` mediumblob NOT NULL,
  `accepted` varchar(10) DEFAULT 'false',
  `accepted_date` date DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
