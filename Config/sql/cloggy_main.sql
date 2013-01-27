-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 27, 2013 at 04:43 PM
-- Server version: 5.5.29
-- PHP Version: 5.4.6-1ubuntu1.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cloggy_main`
--

-- --------------------------------------------------------

--
-- Table structure for table `cloggy_nodes`
--

CREATE TABLE IF NOT EXISTS `cloggy_nodes` (
  `id` bigint(150) unsigned NOT NULL AUTO_INCREMENT,
  `node_type_id` bigint(150) unsigned NOT NULL,
  `user_id` bigint(150) unsigned NOT NULL,
  `node_parent` bigint(150) unsigned DEFAULT '0',
  `has_subject` tinyint(2) DEFAULT '0',
  `has_content` tinyint(2) DEFAULT '0',
  `has_media` tinyint(2) DEFAULT '0',
  `has_meta` tinyint(2) DEFAULT '0',
  `node_status` tinyint(2) DEFAULT '0',
  `node_created` datetime NOT NULL,
  `node_updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `node_type_id` (`node_type_id`),
  KEY `node_parent` (`node_parent`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='main data' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cloggy_node_contents`
--

CREATE TABLE IF NOT EXISTS `cloggy_node_contents` (
  `id` bigint(150) unsigned NOT NULL AUTO_INCREMENT,
  `node_id` bigint(150) unsigned NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `node_id` (`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='content data' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cloggy_node_media`
--

CREATE TABLE IF NOT EXISTS `cloggy_node_media` (
  `id` bigint(150) unsigned NOT NULL AUTO_INCREMENT,
  `node_id` bigint(150) unsigned NOT NULL,
  `media_file_type` varchar(255) NOT NULL,
  `media_file_location` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `node_id` (`node_id`,`media_file_type`),
  KEY `media_file_location` (`media_file_location`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='media management (video,images,etc)' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cloggy_node_meta`
--

CREATE TABLE IF NOT EXISTS `cloggy_node_meta` (
  `id` bigint(150) unsigned NOT NULL AUTO_INCREMENT,
  `node_id` bigint(150) NOT NULL,
  `meta_key` varchar(255) NOT NULL,
  `meta_value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `node_id` (`node_id`,`meta_key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='node properties' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cloggy_node_permalinks`
--

CREATE TABLE IF NOT EXISTS `cloggy_node_permalinks` (
  `id` bigint(150) unsigned NOT NULL AUTO_INCREMENT,
  `node_id` bigint(150) unsigned NOT NULL,
  `permalink_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `node_id` (`node_id`,`permalink_url`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='permalink based on title(subject)' AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `cloggy_node_rels`
--

CREATE TABLE IF NOT EXISTS `cloggy_node_rels` (
  `id` bigint(150) unsigned NOT NULL AUTO_INCREMENT,
  `node_id` bigint(150) unsigned NOT NULL,
  `node_object_id` bigint(150) unsigned NOT NULL,
  `relation_name` varchar(255) NOT NULL,
  `relation_created` datetime NOT NULL,
  `relation_updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `node_id` (`node_id`,`node_object_id`),
  KEY `relation_name` (`relation_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='relation between nodes(many to many)' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cloggy_node_subjects`
--

CREATE TABLE IF NOT EXISTS `cloggy_node_subjects` (
  `id` bigint(150) unsigned NOT NULL AUTO_INCREMENT,
  `node_id` bigint(150) unsigned NOT NULL,
  `subject` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `node_id` (`node_id`),
  KEY `subject` (`subject`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='subject data(title)' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cloggy_node_types`
--

CREATE TABLE IF NOT EXISTS `cloggy_node_types` (
  `id` bigint(150) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(150) unsigned NOT NULL,
  `node_type_name` varchar(255) NOT NULL,
  `node_type_desc` text NOT NULL,
  `node_type_created` datetime NOT NULL,
  `node_type_updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `node_type_name` (`node_type_name`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='content types' AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `cloggy_users`
--

CREATE TABLE IF NOT EXISTS `cloggy_users` (
  `id` bigint(150) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `users_roles_id` int(11) unsigned NOT NULL,
  `user_status` tinyint(2) NOT NULL DEFAULT '0',
  `user_last_login` datetime NOT NULL,
  `user_created` datetime NOT NULL,
  `user_updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_name` (`user_name`),
  KEY `user_email` (`user_email`,`user_status`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='users management' AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `cloggy_users_perms`
--

CREATE TABLE IF NOT EXISTS `cloggy_users_perms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `aro_object_id` int(11) unsigned NOT NULL DEFAULT '0',
  `aro_object` varchar(255) NOT NULL,
  `aco_object` varchar(255) NOT NULL,
  `aco_adapter` varchar(255) NOT NULL,
  `allow` int(2) NOT NULL DEFAULT '0',
  `deny` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `aco_adapter` (`aco_adapter`),
  KEY `aro_object` (`aro_object`),
  KEY `aro_object_id` (`aro_object_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='user permissions list' AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `cloggy_users_roles`
--

CREATE TABLE IF NOT EXISTS `cloggy_users_roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_name` (`role_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='user roles' AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `cloggy_user_login`
--

CREATE TABLE IF NOT EXISTS `cloggy_user_login` (
  `id` bigint(150) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(150) unsigned NOT NULL,
  `login_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='a group of user login' AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `cloggy_user_meta`
--

CREATE TABLE IF NOT EXISTS `cloggy_user_meta` (
  `id` bigint(150) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(150) unsigned NOT NULL,
  `meta_key` varchar(255) NOT NULL,
  `meta_value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`meta_key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='user properties' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
