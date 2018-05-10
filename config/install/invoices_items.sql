
CREATE TABLE IF NOT EXISTS `invoices_items` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `invoices` int(11) NOT NULL,
  `products` int(10) NOT NULL,
  `categories` int(11) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
