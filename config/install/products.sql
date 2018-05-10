
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clients` int(10) NOT NULL,
  `companies` int(10) NOT NULL,
  `categories` int(10) NOT NULL,
  `date` date NOT NULL,
  `year` int(10) DEFAULT NULL,
  `month` int(10) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `price` decimal(50,2) DEFAULT '0.00',
  `renewable` varchar(10) DEFAULT 'false',
  `period` int(11) DEFAULT NULL,
  `canceled` varchar(10) DEFAULT 'false',
  `canceled_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
