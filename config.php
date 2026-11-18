<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();


$CFG->dbtype    = 'pgsql';
$CFG->dblibrary = 'native';
$CFG->dbhost    = getenv('DB_HOST') ?: 'localhost';
$CFG->dbname    = getenv('DB_NAME') ?: 'immast_mdl_db';
$CFG->dbuser    = getenv('DB_USER') ?: 'immast_mdl_user';
$CFG->dbpass    = getenv('DB_PASS') ?: 'q5kvsrQQEMrapHUH';
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbport' => '',
  'dbsocket' => '',
);

$CFG->wwwroot   = getenv('MOODLE_DOMAIN') ?: 'https://courses.immast.org';
$CFG->sslproxy  = true;
$CFG->sessioncookiedomain = getenv('MOODLE_DOMAIN') ?: 'immast.org';
$CFG->dataroot  = getenv('MOODLE_DATAROOT') ?: '/var/www/courses.immast.org/private/moodledata';
$CFG->admin     = 'admin';
$CFG->maxbytes  = 1048576000  ;

$CFG->directorypermissions = 0777;
$CFG->disableupdateautodeploy = true; 

require_once(__DIR__ . '/lib/setup.php');

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!

// Moodle debugging.
@error_reporting(E_ALL);
@ini_set('display_errors', '0');
@ini_set('log_errors', '1');
// @ini_set('error_log', '/var/log/moodle/php-error.log');

$CFG->debug = (E_ALL | E_STRICT);
$CFG->debugdisplay = 1;

// tail -f /var/log/ispconfig/httpd/courses.immast.org/php_error.log