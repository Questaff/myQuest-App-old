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

// try {
//     $request = $pdo->prepare("SELECT * FROM `user` WHERE token = 'f1342f723604977d937737870ca5af63'");
//     //$request->bindParam(':token', 'aaa');
//     $request->execute();
//     $result = $request->fetch(PDO::FETCH_ASSOC);

//     if ($result == "") {
//         $return["success"] = false;
//         $return["message"] = "token n'existe pas";
//     }else {

//         $today = date("Y-m-d");

//         if ($today > $result['dateToken']) {
//             $return["success"] = false;
//             $return["message"] = "token expired";
//         } else {
//             $return["success"] = true;
//             $return["message"] = "good token";
//         }
//     }

// } catch (Exeption $e) {
//     $return["success"] = false;
//     $return["message"] = "error";
// }

// $request= $pdo->prepare("SELECT * FROM `quest_asign` WHERE idQuest = 34 AND `etat` = 1");
$request= $pdo->prepare("SELECT `user`.`username`FROM `quest_asign` LEFT JOIN `user` ON `quest_asign`.`idUser` = `user`.`id` WHERE `quest_asign`.`idQuest` = 34 AND `quest_asign`.`etat` = 1");
$request->execute();
$result = $request->fetchall();

print_r($result);

?>