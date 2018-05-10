
CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item` varchar(100) NOT NULL,
  `price` decimal(50,2) NOT NULL DEFAULT '0.00',
  `canceled` varchar(10) NOT NULL DEFAULT 'false',
  `canceled_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
