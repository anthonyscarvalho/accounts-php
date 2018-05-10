
CREATE TABLE IF NOT EXISTS `user_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(40) DEFAULT NULL,
  `save` varchar(20) NOT NULL DEFAULT 'false',
  `edit` varchar(20) NOT NULL DEFAULT 'false',
  `status` varchar(20) NOT NULL DEFAULT 'false',
  `delete` varchar(20) NOT NULL DEFAULT 'false',
  `create` varchar(20) NOT NULL DEFAULT 'false',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
