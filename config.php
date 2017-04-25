<?php

$config = array(
	'host'     => '127.0.0.1',
	'dbname'   => 'dogtrack',
	'user'     => '',
	'password' => ''
	);

$pdo = new PDO("mysql:host=$config[host];dbname=$config[dbname]", $config['user'], $config['password']);

date_default_timezone_set('America/New_York');


function debug($arr) {
	echo "<pre>";
	print_r($arr);
	echo "</pre>";
}

function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}