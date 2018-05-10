
CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `clients` int(10) DEFAULT NULL,
  `users` int(10) NOT NULL,
  `date` datetime NOT NULL,
  `action` varchar(30) NOT NULL,
  `affected_table` varchar(30) NOT NULL,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
