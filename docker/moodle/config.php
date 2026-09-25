<?php  // Configuration Moodle de développement, lue depuis l'environnement Docker.
unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->dbtype    = 'pgsql';
$CFG->dblibrary = 'native';
$CFG->dbhost    = getenv('MOODLE_DB_HOST') ?: 'moodle-db';
$CFG->dbname    = getenv('MOODLE_DB_NAME') ?: 'moodle';
$CFG->dbuser    = getenv('MOODLE_DB_USER') ?: 'moodle';
$CFG->dbpass    = getenv('MOODLE_DB_PASSWORD') ?: 'moodle';
$CFG->prefix    = 'mdl_';
$CFG->dboptions = ['dbpersist' => 0, 'dbport' => 5432, 'dbsocket' => ''];

$CFG->wwwroot   = getenv('MOODLE_URL') ?: 'http://localhost:8081';
$CFG->dataroot  = '/var/www/moodledata';
$CFG->admin     = 'admin';
$CFG->directorypermissions = 02777;

// Développement : erreurs visibles, SCSS recompilé à chaque requête, JS non minifié.
$CFG->debug = E_ALL;
$CFG->debugdisplay = 1;
$CFG->themedesignermode = true;
$CFG->cachejs = false;
$CFG->cachetemplates = false;
$CFG->theme = 'asaka';
$CFG->smtphosts = 'mailpit:1025';
$CFG->noreplyaddress = 'noreply@asaka.local';

require_once(__DIR__ . '/lib/setup.php');
