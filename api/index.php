<?php

require_once "header.php";

include 'db.php';
require 'Slim/Slim.php';


//usage resource
require_once "resources/usage/saveUsage.php";
require_once "resources/usage/getTodayUsage.php";
require_once "resources/companies/managers/getManagerEmployees.php";
require_once "resources/companies/managers/employees/getEmployee.php";
require_once "resources/auth/authUser.php";

require_once "resources/instance/getInstance.php";
require_once "resources/instance/updateInstanceType.php";
require_once "resources/instance/getUserLast10Instance.php";


//app
require_once "app.php";





?>