CREATE TABLE IF NOT EXISTS `cnt_mark` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mark1` varchar(50) NOT NULL COMMENT '索引key1',
  `mark2` varchar(50) NOT NULL COMMENT '索引key2',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `item` (`mark1`, `mark2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='计数器类型表';


CREATE TABLE IF NOT EXISTS `cnt_counter` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mark_id` int(11) unsigned NOT NULL COMMENT '类型ID',
  `count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '计数',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '预留字段，整形',
  `str` varchar(255) NOT NULL DEFAULT '' COMMENT '预留字段，字符串',
  `upadte_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `item` (`mark_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='计数器计数表';