<?php

include('./header.php');

include('./functionSQL.php');
include('./functionGet.php');
include('./functionPut.php');
include('./functionDelete.php');

$httpRequest = $_SERVER["REQUEST_METHOD"];

switch ($httpRequest) {

    case "POST":
        include('./functionPost.php');
        break;
    
    case "GET":
        $return["message"] = "get";
        break;
    
    case "PUT":
        $return["message"] = "put";
        break;

    case "DELETE":
        $return["message"] = "delete";
        break;

    default:
        $return["message"] = "nop";
        break;
}

echo json_encode($return)
?>