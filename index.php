<?php
/**
 * Created by PhpStorm.
 * User: khoa.nguyen
 * Date: 8/11/2015
 * Time: 1:30 PM
 */

error_reporting(-1);
ini_set('display_errors', 'On');

require('Controllers/DefaultController.php');

use Controllers\DefaultController;

$main = new DefaultController();
$main->dumpXML();