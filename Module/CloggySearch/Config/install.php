<?php

$config = array(
    'cloggy_search_fulltext' => "CREATE TABLE IF NOT EXISTS `cloggy_search_fulltext` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `source_table_name` varchar(255) NOT NULL,
        `source_table_key` bigint(150) unsigned NOT NULL,
        `source_table_field` varchar(255) NOT NULL,
        `source_sentences` varchar(255) DEFAULT NULL,
        `source_text` text,
        `source_created` datetime NOT NULL,
        `source_updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `source_table_name` (`source_table_name`,`source_table_key`,`source_table_field`),
        FULLTEXT KEY `source_sentences` (`source_sentences`),
        FULLTEXT KEY `source_text` (`source_text`)
      ) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;",
    'cloggy_search_last_update' => "CREATE TABLE IF NOT EXISTS `cloggy_search_last_update` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `engine` varchar(255) NOT NULL,
        `object_name` varchar(255) NOT NULL,
        `object_type` varchar(255) NOT NULL,
        `object_id` bigint(150) unsigned NOT NULL,
        `created` datetime NOT NULL,
        `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `engine` (`engine`,`object_name`),
        KEY `object_id` (`object_id`),
        KEY `object_type` (`object_type`)
      ) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;",
    'cloggy_search_logs' => "CREATE TABLE IF NOT EXISTS `cloggy_search_logs` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `ip` varchar(255) NOT NULL,
        `engine` varchar(255) NOT NULL,
        `activities` text NOT NULL,
        `created` datetime NOT NULL,
        `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `ip` (`ip`,`engine`)
      ) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;",
    'cloggy_search_options' => "CREATE TABLE IF NOT EXISTS `cloggy_search_options` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `engine` varchar(255) NOT NULL,
        `option_key` varchar(255) NOT NULL,
        `option_value` text NOT NULL,
        `created` datetime NOT NULL,
        `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `engine` (`engine`,`option_key`)
      ) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;",
    'cloggy_search_terms' => "CREATE TABLE IF NOT EXISTS `cloggy_search_terms` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `term` varchar(255) NOT NULL,
        `count` bigint(150) NOT NULL,
        `created` datetime NOT NULL,
        `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `term` (`term`)
      ) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;"
);