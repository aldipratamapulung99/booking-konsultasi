<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Dashboard';

$route['dashboard'] = 'Dashboard';

// Students
$route['students'] = 'Students';
$route['students/(:any)'] = 'Students/$1';

// Supervisors
$route['supervisors'] = 'Supervisors';
$route['supervisors/(:any)'] = 'Supervisors/$1';

// Consultations
$route['consultations'] = 'Consultations';
$route['consultations/(:any)'] = 'Consultations/$1';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
