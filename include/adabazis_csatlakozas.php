<?php

$database['dbserver']="localhost";
$database['dbuser']="projectmunka";
$database['dbname']="projectmunka";
$database['dbpass']="i3Pg2pN5Ij(HI!mf";

$connect = mysqli_connect($database['dbserver'], $database['dbuser'], $database['dbpass'], $database['dbname']);
mysqli_set_charset($connect, 'utf8mb4');
?> 