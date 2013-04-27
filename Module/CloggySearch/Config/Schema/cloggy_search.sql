-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 27, 2013 at 12:13 AM
-- Server version: 5.5.31
-- PHP Version: 5.4.6-1ubuntu1.2

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
-- Table structure for table `cloggy_search_fulltext`
--

CREATE TABLE IF NOT EXISTS `cloggy_search_fulltext` (
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
  FULLTEXT KEY `source_fields` (`source_sentences`,`source_text`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `cloggy_search_fulltext`
--

INSERT INTO `cloggy_search_fulltext` (`id`, `source_table_name`, `source_table_key`, `source_table_field`, `source_sentences`, `source_text`, `source_created`, `source_updated`) VALUES
(1, 'cloggy_node_contents', 41, 'content', NULL, '<p>dfdfdf</p>\n', '2013-04-24 22:58:15', '0000-00-00 00:00:00'),
(2, 'cloggy_node_contents', 40, 'content', NULL, 'dfdfdff', '2013-04-24 22:58:15', '0000-00-00 00:00:00'),
(3, 'cloggy_node_contents', 39, 'content', NULL, 'Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!', '2013-04-24 22:58:15', '0000-00-00 00:00:00'),
(4, 'cloggy_node_subjects', 236, 'subject', 'test', NULL, '2013-04-24 22:58:15', '0000-00-00 00:00:00'),
(5, 'cloggy_node_subjects', 235, 'subject', 'test featured image', NULL, '2013-04-24 22:58:15', '0000-00-00 00:00:00'),
(6, 'cloggy_node_subjects', 234, 'subject', 'Hello world!', NULL, '2013-04-24 22:58:15', '0000-00-00 00:00:00'),
(7, 'cloggy_node_subjects', 233, 'subject', 'tes3', NULL, '2013-04-24 22:58:15', '0000-00-00 00:00:00'),
(8, 'cloggy_node_subjects', 232, 'subject', 'tes2', NULL, '2013-04-24 22:58:15', '0000-00-00 00:00:00'),
(9, 'cloggy_node_subjects', 231, 'subject', 'tes1', NULL, '2013-04-24 22:58:15', '0000-00-00 00:00:00'),
(10, 'cloggy_node_subjects', 230, 'subject', 'Uncategorized', NULL, '2013-04-24 22:58:15', '0000-00-00 00:00:00'),
(11, 'cloggy_node_subjects', 229, 'subject', 'testchildren', NULL, '2013-04-24 22:58:15', '0000-00-00 00:00:00'),
(12, 'cloggy_node_subjects', 228, 'subject', 'testcat2', NULL, '2013-04-24 22:58:15', '0000-00-00 00:00:00'),
(13, 'cloggy_node_subjects', 227, 'subject', 'testcat1', NULL, '2013-04-24 22:58:15', '0000-00-00 00:00:00'),
(14, 'cloggy_node_subjects', 226, 'subject', 'testc3', NULL, '2013-04-24 22:58:15', '0000-00-00 00:00:00'),
(15, 'cloggy_node_subjects', 237, 'subject', 'test category to index', NULL, '2013-04-24 22:59:07', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `cloggy_search_last_update`
--

CREATE TABLE IF NOT EXISTS `cloggy_search_last_update` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `cloggy_search_last_update`
--

INSERT INTO `cloggy_search_last_update` (`id`, `engine`, `object_name`, `object_type`, `object_id`, `created`, `updated`) VALUES
(1, 'mysqlfulltext', 'cloggy_node_contents', 'table', 41, '2013-04-24 22:58:15', '0000-00-00 00:00:00'),
(2, 'mysqlfulltext', 'cloggy_node_subjects', 'table', 237, '2013-04-24 22:58:15', '2013-04-24 15:59:07');

-- --------------------------------------------------------

--
-- Table structure for table `cloggy_search_logs`
--

CREATE TABLE IF NOT EXISTS `cloggy_search_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) NOT NULL,
  `engine` varchar(255) NOT NULL,
  `activities` text NOT NULL,
  `created` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`,`engine`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cloggy_search_options`
--

CREATE TABLE IF NOT EXISTS `cloggy_search_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `engine` varchar(255) NOT NULL,
  `option_key` varchar(255) NOT NULL,
  `option_value` text NOT NULL,
  `created` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `engine` (`engine`,`option_key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cloggy_search_terms`
--

CREATE TABLE IF NOT EXISTS `cloggy_search_terms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `term` varchar(255) NOT NULL,
  `count` bigint(150) NOT NULL,
  `created` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `term` (`term`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
