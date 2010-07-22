<?php defined('SYSPATH') or die('No direct access allowed.');

return array
(
	'default' => array
	(
		'type'       => 'PDOSQLite',
		'connection' => array(
			'dsn'   => 'sqlite:sc2.db3',
			'persistent' => FALSE,
			'database'   => 'vnq',
		),
		'table_prefix' => '',
		'charset'      => 'utf8',
		'caching'      => FALSE,
		'profiling'    => TRUE,
	),
);