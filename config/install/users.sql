
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(250) NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `email_address` varchar(100) DEFAULT NULL,
  `last_login` datetime NOT NULL,
  `roles` int(10) DEFAULT '1',
  `access` text NOT NULL,
  `canceled` varchar(10) DEFAULT 'false'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
