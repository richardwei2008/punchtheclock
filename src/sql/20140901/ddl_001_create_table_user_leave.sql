CREATE TABLE IF NOT EXISTS `attendance_user_leave` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(256) COLLATE utf8_bin NOT NULL,
  `days` double NOT NULL,
  `reason` varchar(128) COLLATE utf8_bin NOT NULL,
  `fromDate` varchar(128) COLLATE utf8_bin NOT NULL,
  `fromTime` varchar(128) COLLATE utf8_bin NOT NULL,
  `toDate` varchar(128) COLLATE utf8_bin NOT NULL,
  `toTime` varchar(128) COLLATE utf8_bin NOT NULL,
  `supplement` varchar(256) COLLATE utf8_bin NOT NULL,  
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `openid` (`openid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
