CREATE TABLE `request` (
  `id` int(11) NOT NULL auto_increment,
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `ip` varchar(15) default NULL,
  `prefix` text,
  `number` text,
  `url_id` text,
  `url_string` text,
  `file` text,
  PRIMARY KEY  (`id`)
);

CREATE TABLE `error` (
  `id` int(11) NOT NULL auto_increment,
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `ip` varchar(15) default NULL,
  `raw_number` text,
  `msg` text,
  `raw_url` text,
  PRIMARY KEY  (`id`)
);
