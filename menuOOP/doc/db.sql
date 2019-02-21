--
-- Struktuur tabelile `session`
--

CREATE TABLE `session` (
  `sid` varchar(32) collate latin1_general_ci NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `user_data` text collate latin1_general_ci NOT NULL,
  `svars` text collate latin1_general_ci NOT NULL,
  `created` datetime NOT NULL,
  `changed` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `login_ip` varchar(255) collate latin1_general_ci NOT NULL,
  UNIQUE KEY `sid` (`sid`)
);

--
-- Struktuur tabelile `user`
--

CREATE TABLE `user` (
  `user_id` bigint(20) unsigned NOT NULL auto_increment,
  `username` varchar(255) collate latin1_general_ci NOT NULL,
  `password` varchar(255) collate latin1_general_ci NOT NULL,
  `first_name` varchar(255) collate latin1_general_ci NOT NULL,
  `last_name` varchar(255) collate latin1_general_ci NOT NULL,
  `email` varchar(255) collate latin1_general_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL default '0',
  `role_id` tinyint(1) NOT NULL default '0',
  `created` datetime NOT NULL,
  `changed` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`user_id`)
);

--
-- Struktuur tabelile `content`
--

CREATE TABLE `content` (
  `content_id` bigint(20) unsigned NOT NULL auto_increment,
  `content` text collate latin1_general_ci NOT NULL,
  `clean_content` text collate latin1_general_ci NOT NULL,
  `title` varchar(255) collate latin1_general_ci NOT NULL,
  `parent_id` bigint(20) unsigned NOT NULL default '0',
  `is_hidden` tinyint(1) NOT NULL default '0',
  `show_in_menu` tinyint(1) NOT NULL default '0',
  `sort` int(10) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `is_first_page` tinyint(1) NOT NULL default '0',
  `lang_id` varchar(2) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`content_id`)
);