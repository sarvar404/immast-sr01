<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();


$CFG->dbtype    = 'pgsql';
$CFG->dblibrary = 'native';
$CFG->dbhost    = getenv('DB_HOST') ?: 'localhost';
$CFG->dbname    = getenv('DB_NAME') ?: 'immast_mdl_db';
$CFG->dbuser    = getenv('DB_USER') ?: 'immast_mdl_user';
$CFG->dbpass    = getenv('DB_PASS') ?: 'immast_mdl_user';
$CFG->prefix    = 'mdl_';
$iswindows = (DIRECTORY_SEPARATOR === '\\');
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbport' => getenv('DB_PORT') ?: ($iswindows ? '5432' : ''),
  'dbsocket' => '',
);

$CFG->wwwroot   = getenv('MOODLE_URL') ?: (getenv('MOODLE_DOMAIN') ?: ($iswindows ? 'http://localhost/immast' : 'https://courses.immast.org'));
$moodleslproxy = getenv('MOODLE_SSLPROXY');
$CFG->sslproxy  = ($moodleslproxy !== false) ? filter_var($moodleslproxy, FILTER_VALIDATE_BOOLEAN) : !$iswindows;
$moodlecookiedomain = getenv('MOODLE_COOKIE_DOMAIN');
$CFG->sessioncookiedomain = ($moodlecookiedomain !== false) ? $moodlecookiedomain : (getenv('MOODLE_DOMAIN') ?: ($iswindows ? '' : 'immast.org'));
$localdataroot = $iswindows ? 'C:/moodledata' : null;
$CFG->dataroot  = getenv('MOODLE_DATAROOT') ?: ($localdataroot ?: '/var/www/courses.immast.org/private/moodledata');
if (!is_dir($CFG->dataroot) || !is_writable($CFG->dataroot)) {
    throw new RuntimeException('Moodle dataroot must exist and be writable by the PHP process: ' . $CFG->dataroot);
}
$moodlehost = parse_url($CFG->wwwroot, PHP_URL_HOST);
if (in_array($moodlehost, array('localhost', '127.0.0.1', '::1'), true)) {
    $smtpuser = getenv('MOODLE_SMTP_USERNAME');
    $smtppass = getenv('MOODLE_SMTP_PASSWORD');
    $hasuser = $smtpuser !== false && $smtpuser !== '';
    $haspass = $smtppass !== false && $smtppass !== '';

    if ($hasuser !== $haspass) {
        throw new RuntimeException('Both local Moodle SMTP credentials must be configured.');
    }

    if ($hasuser && $haspass) {
        $smtphost = getenv('MOODLE_SMTP_HOST');
        $smtpport = getenv('MOODLE_SMTP_PORT');
        $smtpsecure = getenv('MOODLE_SMTP_SECURE');
        $smtpfrom = getenv('MOODLE_SMTP_FROM');

        if ($smtphost === false || !preg_match('/^[A-Za-z0-9.-]+$/D', $smtphost) ||
                $smtpport === false || !ctype_digit($smtpport) ||
                (int)$smtpport < 1 || (int)$smtpport > 65535 ||
                $smtpsecure === false || strtolower($smtpsecure) !== 'false' ||
                $smtpfrom === false || !filter_var($smtpfrom, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Local Moodle SMTP settings are incomplete or invalid.');
        }

        $CFG->smtphosts = $smtphost . ':' . $smtpport;
        $CFG->smtpsecure = 'tls';
        $CFG->smtpuser = $smtpuser;
        $CFG->smtppass = $smtppass;
        $CFG->noreplyaddress = $smtpfrom;
    }
}
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
