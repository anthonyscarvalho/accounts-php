
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clients` int(11) NOT NULL,
  `companies` int(10) DEFAULT NULL,
  `invoices` int(10) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `credit` decimal(65,2) DEFAULT NULL,
  `debit` decimal(65,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
