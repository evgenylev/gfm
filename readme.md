п.1:
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `pass_hash` varchar(32) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `email` varchar(32) NOT NULL,
  `phone` varchar(16) NOT NULL,
  `birthdate` date NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `midlle_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status_email_phone` (`status`,`email`,`phone`),
  KEY `birthdate` (`birthdate`),
  KEY `last_name` (`last_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

п.2: 
этот репозиторий
не успел - логирование, тесты и php-doc комментарии