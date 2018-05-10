
CREATE TABLE IF NOT EXISTS `ad_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaigns` int(10) NOT NULL,
  `users` int(11) NOT NULL,
  `contacts` int(11) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `body` longtext NOT NULL,
  `date` datetime NOT NULL,
  `attachment` mediumblob,
  `status` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
