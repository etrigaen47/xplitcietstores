<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 9/8/2019
 * Time: 12:01 AM
 */

$db = mysqli_connect('127.0.0.1', 'root', '', 'xplitcietstores');
if(mysqli_connect_errno()){
    echo '<h1>DATABASE CONNECTION FAILED</h1><h2>'. strtoupper(mysqli_connect_error()) .'</h2>';
    die();
}
define('BASEURL', '/xplitcietstores/');//a constant
//constants in php are all uppercase by convention(first parameter in brackets above)