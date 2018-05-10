
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business` varchar(300) DEFAULT NULL,
  `vat` varchar(20) DEFAULT NULL,
  `number` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `registration` varchar(20) DEFAULT NULL,
  `billing_address` varchar(500) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `notes` text,
  `canceled` varchar(10) NOT NULL DEFAULT 'false',
  `signup_date` date DEFAULT NULL,
  `canceled_date` date DEFAULT NULL,
  `bad_client` varchar(10) DEFAULT 'false',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
