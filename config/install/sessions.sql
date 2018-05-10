
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session` varchar(50) DEFAULT NULL,
  `logged_in` varchar(10) DEFAULT 'false',
  `time` datetime DEFAULT NULL,
  `user` int(10) DEFAULT '0',
  `last_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
