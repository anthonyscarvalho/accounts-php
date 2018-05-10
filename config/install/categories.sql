
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `category` varchar(250) NOT NULL,
  `price` decimal(50,2) DEFAULT '0.00',
  `link` varchar(20) DEFAULT NULL,
  `canceled` varchar(10) DEFAULT 'false',
  `canceled_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
