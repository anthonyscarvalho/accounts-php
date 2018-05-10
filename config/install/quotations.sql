
CREATE TABLE IF NOT EXISTS `quotations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `clients` int(10) NOT NULL,
  `companies` int(10) DEFAULT NULL,
  `deposit` decimal(50,2) DEFAULT NULL,
  `scope` longtext NOT NULL,
  `content` longtext,
  `signature` longtext NOT NULL,
  `annexure` longtext NOT NULL,
  `products` longtext NOT NULL,
  `creation_date` date DEFAULT NULL,
  `canceled_date` date DEFAULT NULL,
  `accepted_date` datetime DEFAULT NULL,
  `notes` text,
  `accepted` varchar(10) DEFAULT 'false',
  `canceled` varchar(10) DEFAULT NULL,
  `link` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
