
CREATE TABLE IF NOT EXISTS `ad_transactions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `campaigns` int(10) NOT NULL,
  `clients` int(10) DEFAULT NULL,
  `date` date NOT NULL,
  `credit` decimal(50,2) DEFAULT NULL,
  `debit` decimal(50,2) DEFAULT NULL,
  `comment` text,
  `commission` varchar(10) DEFAULT 'false',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
