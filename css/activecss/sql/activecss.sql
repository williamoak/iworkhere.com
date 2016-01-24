DROP TABLE IF EXISTS `activecss`;
CREATE TABLE `activecss` (
  `corder` int(16) NOT NULL AUTO_INCREMENT,
  `conditional` char(254) DEFAULT NULL,
  `looking_for` char(254) DEFAULT NULL,
  `theme` char(254) DEFAULT NULL,
  `section` char(254) DEFAULT NULL,
  `page` char(254) DEFAULT NULL,
  `css_name` char(254) DEFAULT NULL,
  `css_selector` char(254) DEFAULT NULL,
  `css_value` char(254) DEFAULT NULL,
  `css_place` int(5) DEFAULT NULL,
  PRIMARY KEY (`corder`),
  KEY `icorder` (`corder`),
  KEY `itheme` (`theme`),
  KEY `ibrowser` (`conditional`),
  KEY `isection` (`section`),
  KEY `ipage` (`page`)
);

-- ----------------------------
-- Records of activecss
-- ----------------------------
INSERT INTO `activecss` VALUES ('1', 'default', 'browser', 'default', null, null, 'msie', 'default', null, null);
INSERT INTO `activecss` VALUES ('2', 'browserlist', 'msie', 'default', null, null, 'Internet Explorer', 'msie.png', null, null);
INSERT INTO `activecss` VALUES ('3', 'browserlist', 'firefox', 'default', null, null, 'Firefox', 'firefox.png', null, null);
INSERT INTO `activecss` VALUES ('4', 'browserlist', 'chrome', 'default', null, null, 'Google Chrome', 'chrome.png', null, null);
INSERT INTO `activecss` VALUES ('5', 'browserlist', 'safari', 'default', null, null, 'Apple Safari', 'safari.png', null, null);
INSERT INTO `activecss` VALUES ('6', 'themelist', 'minimal', 'Minimal Theme', null, null, null, 'themes.gif', null, null);
INSERT INTO `activecss` VALUES ('7', 'themelist', 'default', 'Default Theme', null, null, null, 'themes.gif', null, null);
INSERT INTO `activecss` VALUES ('8', 'themelist', 'long', 'Long Pages', null, null, null, 'themes.gif', null, null);
INSERT INTO `activecss` VALUES ('9', 'themelist', 'green', 'Green Theme', null, null, null, 'themes.gif', null, null);
INSERT INTO `activecss` VALUES ('10', 'sectionlist', 'menu', 'menu styles', null, null, null, 'menusection.gif', null, null);
INSERT INTO `activecss` VALUES ('11', 'sectionlist', 'main', 'main page styles', null, null, null, 'sections.gif', null, null);
INSERT INTO `activecss` VALUES ('12', 'sectionlist', 'footer', 'footer styles', null, null, null, 'sections.gif', null, null);
INSERT INTO `activecss` VALUES ('13', 'sectionlist', 'default', 'Default Section', null, null, null, 'sections.gif', null, null);
INSERT INTO `activecss` VALUES ('14', 'browser', 'trash', 'default', null, null, 'Recycle Bin', 'trash.png', null, null);
INSERT INTO `activecss` VALUES ('15', 'browser', 'msie', 'default', 'main', null, '.maininfodiv', 'border', '0px solid black', '2');
INSERT INTO `activecss` VALUES ('16', 'browser', 'msie', 'default', 'main', null, '.maindiv', 'bottom', '10%', '2');
INSERT INTO `activecss` VALUES ('17', 'browser', 'msie', 'default', 'main', null, '.maindiv', 'overflow', 'auto', '1');
INSERT INTO `activecss` VALUES ('18', 'browser', 'msie', 'default', 'main', null, '.mcondiv', 'border', '0px solid blue', '2');
INSERT INTO `activecss` VALUES ('19', 'browser', 'msie', 'default', null, null, '.mainslider', 'left', '20%', '1');
INSERT INTO `activecss` VALUES ('20', 'browser', 'msie', 'default', 'main', null, '.mcondiv', 'bottom', '-13px', '1');
INSERT INTO `activecss` VALUES ('21', 'browser', 'msie', 'default', null, null, '.textpage', 'padding', '20px', '1');
INSERT INTO `activecss` VALUES ('22', 'browser', 'msie', 'default', null, null, '.bodydiv', 'padding', '5px', '1');
INSERT INTO `activecss` VALUES ('23', 'browser', 'msie', 'default', null, null, '.bodydiv', 'background-color', '#ffffff', '2');
INSERT INTO `activecss` VALUES ('24', 'browser', 'msie', 'default', null, null, '.bodydiv', 'width', '98%', '3');
INSERT INTO `activecss` VALUES ('25', 'browser', 'msie', 'default', 'main', null, '.maininfodiv', 'top', '95%', '1');
INSERT INTO `activecss` VALUES ('26', 'browser', 'msie', 'default', 'menu', null, '.imhere', 'border', '0px solid red', '1');
INSERT INTO `activecss` VALUES ('27', 'browser', 'msie', 'default', 'menu', null, '.imhere', 'background-image', 'url(\'/bgpics/blank.gif\')', '3');
INSERT INTO `activecss` VALUES ('28', 'browser', 'msie', 'default', 'menu', null, '.imhere', 'color', '#df1c1c', '2');
INSERT INTO `activecss` VALUES ('29', 'browser', 'msie', 'default', 'main', null, '.mcondiv', 'right', '10px', '3');
INSERT INTO `activecss` VALUES ('30', 'browser', 'msie', 'default', 'main', null, '.mainslider', 'left', '10px', '1');
INSERT INTO `activecss` VALUES ('31', 'browser', 'msie', 'default', 'main', null, '.mainslider', 'margin-left', 'auto', '1');
INSERT INTO `activecss` VALUES ('32', 'browser', 'msie', 'default', 'main', null, '.mainslider', 'margin-right', 'auto', '2');
INSERT INTO `activecss` VALUES ('33', 'browser', 'msie', 'default', 'main', null, '.jssorb03', 'bottom', '-10px !important', '2');
INSERT INTO `activecss` VALUES ('34', 'browser', 'msie', 'default', 'main', null, '.ustyles', 'height', '500px', '2');
INSERT INTO `activecss` VALUES ('35', 'browser', 'msie', 'default', 'main', null, '.maindiv', 'top', '18%', '3');
INSERT INTO `activecss` VALUES ('36', 'browser', 'msie', 'default', 'main', null, '.mainslider', 'bottom', '10%', '4');
INSERT INTO `activecss` VALUES ('37', 'browser', 'msie', 'default', 'main', null, '.mainslider', 'height', '500px', '4');
INSERT INTO `activecss` VALUES ('38', 'browser', 'msie', 'default', null, null, '.atbtn', 'position', 'absolute', '1');
INSERT INTO `activecss` VALUES ('39', 'browser', 'msie', 'default', null, null, '.atbtn', 'top', '10px', '2');
INSERT INTO `activecss` VALUES ('40', 'browser', 'msie', 'default', null, null, '.atbtn', 'right', '50px', '3');
INSERT INTO `activecss` VALUES ('41', 'browser', 'msie', 'default', null, null, '.atbtn', 'border-radius', '5px', '4');
INSERT INTO `activecss` VALUES ('42', 'browser', 'msie', 'default', null, null, '.abutton', 'border', '1px solid grey', '1');
INSERT INTO `activecss` VALUES ('43', 'browser', 'msie', 'default', null, null, '.abutton', 'border-radius', '5px', '2');
INSERT INTO `activecss` VALUES ('44', 'browser', 'msie', 'default', null, null, '.abutton:hover', 'border', '1px solid blue', '1');
INSERT INTO `activecss` VALUES ('45', 'browser', 'msie', 'default', null, null, '.abutton:hover', 'color', 'blue', '2');
INSERT INTO `activecss` VALUES ('46', 'browser', 'msie', 'default', 'main', null, '.maindiv', 'padding', '10px', '4');
INSERT INTO `activecss` VALUES ('47', 'browser', 'msie', 'default', null, null, '.topdiv', 'position', 'fixed', '1');
