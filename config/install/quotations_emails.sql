
CREATE TABLE IF NOT EXISTS `quotations_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quote` int(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
