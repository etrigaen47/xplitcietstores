<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 9/11/2019
 * Time: 1:32 PM
 */
$db = mysqli_connect('127.0.0.1', 'root', '', 'xplitcietstores');
if(mysqli_connect_errno()){
    echo '<h1>DATABASE CONNECTION FAILED</h1><h2>'. strtoupper(mysqli_connect_error()) .'</h2>';
    die();
}
//require_once '../config.php';
//require_once BASEURL.'/helpers/helpers.php';
require_once $_SERVER['DOCUMENT_ROOT'].'../xplitcietstores/config.php';
require_once BASEURL.'/helpers/helpers.php';