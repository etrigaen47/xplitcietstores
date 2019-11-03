<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 9/8/2019
 * Time: 12:22 AM
 */
$sql = "SELECT * FROM categories WHERE parent = 0";
//selects category options from the database where the parent id is 0
$parentQuery = $db->query($sql);
    //the $db-> is the method of the object $db