<?php
defined('MOODLE_INTERNAL') || die();

$plugin->version   = 2021051700;
$plugin->requires  = 2021051700;
$plugin->component = 'qtype_combined';
$plugin->maturity  = MATURITY_STABLE;
$plugin->release   = '3.11.0';
$plugin->dependencies = [
    'qtype_pmatch' => 2021051700 // Often required by OU plugins
];