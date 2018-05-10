
CREATE TABLE IF NOT EXISTS `tickets` (
  `id` int(10) NOT NULL,
  `clients` int(10) NOT NULL,
  `contacts` int(10) NOT NULL,
  `email_from` varchar(200) NOT NULL,
  `email_to` varchar(200) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `content` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
