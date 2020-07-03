<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
header('Access-Control-Allow-Credentials: true');

date_default_timezone_set('europe/paris');

try {
    $pdo = new PDO('mysql:host=localhost; port=3306; dbname=api_test', 'root', '');
    
    $return["success"] = true;
    $return["message"] = "connect to bdd";
    $data = file_get_contents("php://input");
    $data = json_decode($data);

} catch (Exception $e) {
    
    $return["success"] = false;
    $return["message"] = "fail connect to bdd";

}

?>