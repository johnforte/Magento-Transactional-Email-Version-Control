<?php
$installer = $this;
$installer->startSetup();
$installer->run("
DROP TABLE IF EXISTS `TransactionalEmailVersionControl`;
CREATE TABLE `TransactionalEmailVersionControl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `TemplateId` int(10) unsigned NOT NULL,
  `Code` varchar(255) NOT NULL,
  `Subject` varchar(255) NOT NULL,
  `Text` text,
  `Styles` text,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
$installer->endSetup();
