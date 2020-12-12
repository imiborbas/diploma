CREATE TABLE `session` (
  `id` varchar(32) NOT NULL default '',
  `access` int(10) unsigned default NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;