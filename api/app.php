<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/4/16
 * Time: 1:12 PM
 */

\Slim\Slim::registerAutoloader();

global $app;

if(!isset($app))
    $app = new \Slim\Slim();

$app->response->headers->set('Access-Control-Allow-Credentials',  'true');

$app->response->headers->set('Content-Type', 'application/json');

/* Starting routes */

$app->get('/usage/:username','getTodayUsage');
$app->post('/usage/:username', 'saveUsage');

$app->post('/auth', 'authUser');

$app->get('/companies/:company_id/managers/:manager_id/employees','getManagerEmployees');

$app->get('/profession/:profession/type/:type/instance','getInstance');
$app->post('/instance','updateInstance');
$app->get('/instance/:user_id/last10','getUserLast10Instance');

$app->get('/companies/:company_id/managers/:manager_id/employees/:employee','getEmployee');

//$app->post('/login','login');
/* Ending Routes */

$app->run();