
CREATE TABLE IF NOT EXISTS `invoices` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `clients` int(10) NOT NULL,
  `companies` int(10) DEFAULT NULL,
  `creation_date` date DEFAULT NULL,
  `canceled_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `paid_date` date DEFAULT NULL,
  `invoice_total` decimal(50,2) DEFAULT '0.00',
  `deposit` decimal(50,2) DEFAULT NULL,
  `vat` int(10) DEFAULT '0',
  `notes` text,
  `paid` varchar(10) DEFAULT 'false',
  `canceled` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
