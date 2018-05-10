
CREATE TABLE IF NOT EXISTS `template_quotations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `scope` longtext,
  `content` longtext,
  `signature` longtext,
  `annexure` longtext,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `canceled` varchar(10) DEFAULT 'false',
  `date_canceled` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
