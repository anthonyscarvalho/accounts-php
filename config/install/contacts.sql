
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `clients` int(20) NOT NULL,
  `name` varchar(250) NOT NULL,
  `surname` varchar(250) DEFAULT NULL,
  `contact_number_1` varchar(20) DEFAULT NULL,
  `contact_number_2` varchar(20) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `payment` varchar(10) DEFAULT 'false',
  `invoice` varchar(10) DEFAULT 'false',
  `receipt` varchar(10) DEFAULT 'false',
  `suspension` varchar(10) DEFAULT 'false',
  `adwords` varchar(10) DEFAULT 'false',
  `quotes` varchar(10) NOT NULL DEFAULT 'false',
  `creation_date` date NOT NULL,
  `canceled_date` date DEFAULT NULL,
  `canceled` varchar(10) DEFAULT 'false',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
