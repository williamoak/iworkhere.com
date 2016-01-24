DROP TABLE IF EXISTS `activecss`;
CREATE TABLE `activecss` (
  `corder` int(16) NOT NULL AUTO_INCREMENT,
  `conditional` char(254) DEFAULT NULL,
  `looking_for` char(254) DEFAULT NULL,
  `theme` char(254) DEFAULT NULL,
  `css_name` char(254) DEFAULT NULL,
  `css_selector` char(254) DEFAULT NULL,
  `css_value` char(254) DEFAULT NULL,
  `css_place` int(5) DEFAULT NULL,
  PRIMARY KEY (`corder`),
  KEY `icorder` (`corder`),
  KEY `itheme` (`theme`)
) AUTO_INCREMENT=1;

INSERT INTO `activecss` VALUES ( '1','default', 'browser', 'default', 'msie', null, null, null);
INSERT INTO `activecss` VALUES ( '2','browserlist', 'msie', 'default', 'Internet Explorer', null, null, null);
INSERT INTO `activecss` VALUES ( '3','browserlist', 'firefox', 'default', 'Firefox', null, null, null);
INSERT INTO `activecss` VALUES ( '4','browserlist', 'chrome', 'default', 'Google Chrome', null, null, null);
INSERT INTO `activecss` VALUES ( '5','browserlist', 'safari', 'default', 'Apple Safari', null, null, null);
INSERT INTO `activecss` VALUES ( '6','browserlist', 'mobile', 'default', 'Android Mobile', null, null, null);
INSERT INTO `activecss` VALUES ( '7','themelist', 'default', 'Default Theme', null, null, null, null);
INSERT INTO `activecss` VALUES ( '8','themelist', 'minimal', 'Minimalist Theme', null, null, null, null);
INSERT INTO `activecss` VALUES ( '9','browser', 'msie', 'default', '.test', 'color', 'blue', '1');
INSERT INTO `activecss` VALUES ( '10','browser', 'firefox', 'default', '.test', 'color', 'red', '1');
INSERT INTO `activecss` VALUES ( '11','browser', 'msie', 'default', '.bodydiv', 'padding', '5px', '1');
INSERT INTO `activecss` VALUES ( '12','browser', 'msie', 'minimal', '.test', 'color', 'green', '1');
INSERT INTO `activecss` VALUES ( '13','browser', 'firefox', 'minimal', '.test', 'color', '#cc6699', '1');
INSERT INTO `activecss` VALUES ( '14','browser', 'firefox', 'minimal', '.test', 'background-color', '#eeeeee', '2');
