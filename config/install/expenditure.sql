
CREATE TABLE IF NOT EXISTS `expenditure` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `categories` int(10) NOT NULL,
  `companies` int(10) NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(50,2) NOT NULL,
  `description` text,
  `type` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
