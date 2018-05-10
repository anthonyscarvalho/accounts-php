
CREATE TABLE IF NOT EXISTS `quotations_accepted` (
  `id` int(11) NOT NULL,
  `quotations` int(11) NOT NULL,
  `contacts` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
