<?php
$installer = $this;
$installer->startSetup();

$installer->run("ALTER TABLE `oepl_map_fields` ADD `mag_field_type` VARCHAR( 100 ) NOT NULL DEFAULT 'Default' AFTER `mag_field` ;");

$installer->endSetup();

?>