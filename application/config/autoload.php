<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$autoload['packages'] = array();

// Library yang dipakai di semua controller: database, session, form_validation
$autoload['libraries'] = array('database', 'session', 'form_validation');

$autoload['drivers'] = array();

$autoload['helper'] = array('url', 'form', 'date');

$autoload['config'] = array();

$autoload['language'] = array();

$autoload['model'] = array();
