<?php

$dbname = 'mysql:host=localhost;dbname=modulefinder';
$dbuser = 'root';
$dbpass = '';

$conn = new PDO($dbname, $dbuser, $dbpass);

if (!$conn) {
    echo 'Not Connected to database';
}
// FOR CHECKING IF THE CONNECTION TO DATABASE IS GOOD
//else{
//     echo 'Connected Successfully to database';
// }