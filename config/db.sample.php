<?php
define('DB_HOST', '');
define('DB_USER', '');
define('DB_PASS', '');
define('DB_NAME', '');

try {
    $GLOBALS['db'] = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
} catch (PDOException $e) {
    print "Erreur ! : " . $e->getMessage() . "<br/>";
    die();
}
