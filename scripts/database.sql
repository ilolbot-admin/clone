--
-- Database: `clonedb`
--

-- --------------------------------------------------------

--
-- Table structure for table `banned`
--

CREATE TABLE IF NOT EXISTS `banned` (
  `user_ip` varchar(15) NOT NULL DEFAULT '000.000.000.000',
  KEY `user_ip` (`user_ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `message_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_ip` varchar(15) NOT NULL DEFAULT '000.000.000.000',
  `user_name` varchar(64) DEFAULT NULL,
  `chat_message` text,
  `image` text,
  `time_sent` varchar(20) NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`message_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `people_online`
--

CREATE TABLE IF NOT EXISTS `people_online` (
  `user_name` varchar(64) NOT NULL,
  `user_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `time` varchar(20) NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
