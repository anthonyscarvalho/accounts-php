
CREATE TABLE IF NOT EXISTS `ad_logs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `campaigns` int(10) DEFAULT NULL,
  `users` int(10) NOT NULL,
  `affected_table` varchar(30) NOT NULL,
  `action` varchar(30) NOT NULL,
  `date` datetime NOT NULL,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
