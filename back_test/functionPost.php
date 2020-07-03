<?php

    if (isset($data->{"action"})) {
    
        switch ($data->{"action"}) {
            
            case 'newUser':
    
                if (isset($data->{'mail'}) && isset($data->{'username'}) && isset($data->{'pass'})) {
    
                    try {
    
                        $request= $pdo->prepare("SELECT COUNT(*) FROM `user` WHERE `username` = :user");
                        $request->bindParam(':user', $data->{'username'});
                        $request->execute();
                        $result = $request->fetch(PDO::FETCH_ASSOC);
    
                        if ($result['COUNT(*)'] == 0) {
    
                            try {
    
                                $request= $pdo->prepare("SELECT COUNT(*) FROM USER WHERE mail = :mail");
                                $request->bindParam(':mail', $data->{'mail'});
                                $request->execute();
                                $result = $request->fetch(PDO::FETCH_ASSOC);
    
                                if ($result['COUNT(*)'] == 0) {
    
                                    try {
                                        $Hashing = (String) crypt($data->{'pass'},'$1$saltpswd$');

                                        $request= $pdo->prepare("INSERT INTO `user` (`lastName`,`firstName`,`mail`,`username`, `password`) VALUES (:lname,:fname,:mail,:user,:pass)");
                                        $request->bindParam(':lname', $data->{'lname'});
                                        $request->bindParam(':fname', $data->{'fname'});
                                        $request->bindParam(':mail', $data->{'mail'});
                                        $request->bindParam(':user', $data->{'username'});
                                        $request->bindParam(':pass', $Hashing);
                                        $request->execute();
                                
                                        $return["success"] = true;
                                        $return["message"] = "new user in table";
                                        
                                    } catch (Exception $e) {
                                    
                                        $return["success"] = false;
                                        $return["message"] = "fail add new user";
                                
                                    }
    
                                }else {
                                    $return["success"] = false;
                                    $return["message"] = "mail taken";
                                }
    
                            } catch (Exception $e) {
                                $return["success"] = false;
                                $return["message"] = "fail search user by mail";
                            }
                            
                        } else {
                            $return["success"] = false;
                            $return["message"] = "Nom d'utilisateur déjà pris";
                        }
                        
                    } catch (Exception $e) {
                        $return["success"] = false;
                        $return["message"] = "fail search user by username";
                    }
    
                } else {
                    $return["success"] = false;
                    $return["message"] = "username or password or mail not exist";
                }
                
                break;

            case 'connection':

                if (isset($data->{'username'}) && isset($data->{'pass'})) {

                    try {

                        $request= $pdo->prepare("SELECT * FROM `user` WHERE `username` = :user");
                        $request->bindParam(':user', $data->{'username'});
                        $request->execute();
                        $result = $request->fetch(PDO::FETCH_ASSOC);

                        if ($result['username'] != null) {

                            if (!hash_equals($result["password"], crypt($data->{'pass'},$result["password"])) ) {
                                $return["success"] = false;
                                $return["message"] = "pb crypt";
                                return;
                            }

                            $x = 0;
                            while ($x == 0) {
                            
                                $token = bin2hex(random_bytes(16));

                                try {

                                    $request= $pdo->prepare("SELECT COUNT(*) FROM `user` WHERE `token` = :token");
                                    $request->bindParam(':token', $token);
                                    $request->execute();
                                    $result = $request->fetch(PDO::FETCH_ASSOC);

                                    if ($result['COUNT(*)'] == 0) {

                                        try {

                                            $request= $pdo->prepare("UPDATE `user` SET `token` = :token, `dateToken` = NOW() WHERE `username` = :username");
                                            $request->bindParam(':token', $token);
                                            $request->bindParam(':username', $data->{'username'});
                                            $request->execute();
                                        
                                        } catch (Exception $e) {
                                            $return["success"] = false;
                                            $return["message"] = "fail to update token";        
                                        }

                                        $return["success"] = true;
                                        $return["message"] = $token;
                                        $x = 1;
                                    }

                                } catch (Exception $e) {

                                    $return["success"] = false;
                                    $return["message"] = "fail to sherch token";
                                    
                                }

                            }

                        } else {
                            $return["success"] = false;
                            $return["message"] = "error username or password";
                        }
                        
                    } catch (Exception $e) {
                        $return["success"] = false;
                        $return["message"] = "fail to sherch user";
                    }

                } else {
                    $return["success"] = false;
                    $return["message"] = "username or password not exist";
                }

                break;

            case 'newQuest':

                if (isset($data->{'tokenUser'}) && isset($data->{'title'}) && isset($data->{'content'})) {

                    try {
                        $request= $pdo->prepare("SELECT `id` FROM `user` WHERE token = :token");
                        $request->bindParam(':token', $data->{'tokenUser'});
                        $request->execute();
                        $result = $request->fetch(PDO::FETCH_ASSOC);

                        try {
                            
                            $request= $pdo->prepare("INSERT INTO `quest` (`title`,`content`,`date`,`userADD`) VALUES (:title, :content, NOW(), :id)");
                            $request->bindParam(':title', $data->{'title'});
                            $request->bindParam(':content', $data->{'content'});
                            $request->bindParam(':id', $result['id']);
                            $request->execute();
                    
                            $return["success"] = true;
                            $return["message"] = "new quest in table";

                        } catch (Exception $e) {
                            $return["success"] = false;
                            $return["message"] = "error to add quest";    
                        }

                    } catch (Exception $e) {
                        $return["success"] = false;
                        $return["message"] = "error to take id from token";    
                    }

                } else {
                    $return["success"] = false;
                    $return["message"] = "data missing";
                }

                break;
            
            case 'getQuest':
                
                try {
                    
                    $request= $pdo->prepare("SELECT `quest`.`id`, `quest`.`title`, `quest`.`content`, `quest`.`date`, `user`.`username` FROM `quest` INNER JOIN `user` ON `quest`.`userAdd` = `user`.`id`");
                    $request->execute();
                    $result = $request->fetchall();

                    $return["success"] = true;
                    $return["message"] = $result;

                } catch (Exception $e) {
                    
                    $return["success"] = false;
                    $return["message"] = "fail to sherch quest";

                }

                break;

            case 'questToUser':
                
                if (isset($data->{'tokenUser'}) && isset($data->{'idQuest'})) {
                    
                    try {

                        $request= $pdo->prepare("SELECT `id` FROM `user` WHERE token = :token");
                        $request->bindParam(':token', $data->{'tokenUser'});
                        $request->execute();
                        $result = $request->fetch(PDO::FETCH_ASSOC);

                        try {
                            
                            $request= $pdo->prepare("INSERT INTO `quest_asign` (`idUser`,`idQuest`,`etat`) VALUES (:idUser, :idQuest, 0)");
                            $request->bindParam(':idUser', $result['id']);
                            $request->bindParam(':idQuest', $data->{'idQuest'});
                            $request->execute();
                    
                            $return["success"] = true;
                            $return["message"] = "new questAsign in table";

                        } catch (Exception $e) {
                            $return["success"] = false;
                            $return["message"] = "error to add questAssign";    
                        }

                    } catch (Exception $e) {
                        $return["success"] = false;
                        $return["message"] = "error to take id from token";    
                    }

                } else {
                    $return["success"] = false;
                    $return["message"] = "data missing";
                }

                break;

            case 'myQuest':
                
                if (isset($data->{'tokenUser'})) {

                    try {

                        $request= $pdo->prepare("SELECT `id` FROM `user` WHERE token = :token");
                        $request->bindParam(':token', $data->{'tokenUser'});
                        $request->execute();
                        $result = $request->fetch(PDO::FETCH_ASSOC);

                        try {

                            $request= $pdo->prepare("SELECT `id`,`title`,`content`,`date` FROM `quest` WHERE `userAdd` =  :id");
                            $request->bindParam(':id', $result['id']);
                            $request->execute();
                            $result = $request->fetchall();

                            $return["success"] = true;
                            $return["message"] = $result;
                            
                        } catch (Exception $e) {
                            $return["success"] = false;
                            $return["message"] = "fail to sherch quest";
                        }

                    } catch (Exception $e) {
                        $return["success"] = false;
                        $return["message"] = "error to take id from token";
                    }

                } else {
                    $return["success"] = false;
                    $return["message"] = "data missing";
                }

                break;

            case 'questAccept':
                
                if (isset($data->{'tokenUser'})) {

                    try {

                        $request= $pdo->prepare("SELECT `id` FROM `user` WHERE token = :token");
                        $request->bindParam(':token', $data->{'tokenUser'});
                        $request->execute();
                        $result = $request->fetch(PDO::FETCH_ASSOC);

                        try {

                            $request= $pdo->prepare("SELECT `idQuest` FROM `quest_asign` WHERE `idUser` = :id");
                            $request->bindParam(':id', $result['id']);
                            $request->execute();
                            $result = $request->fetchall();

                            $array = array();

                            for ($i=0; $i < COUNT($result); $i++) { 
                                try {
                                    $request= $pdo->prepare("SELECT `quest`.`id`, `quest`.`title`, `quest`.`content`, `quest`.`date`, `user`.`username` FROM `quest` INNER JOIN `user` ON `quest`.`userAdd` = `user`.`id` WHERE `quest`.`id` = :id");
                                    $request->bindParam(':id', $result[$i]["idQuest"]);
                                    $request->execute();
                                    $results = $request->fetch(PDO::FETCH_ASSOC);

                                    array_push($array,$results);

                                } catch (Exception $e) {
                                    $return["success"] = false;
                                    $return["message"] = "fail to sherch quest asign";
                                }
                            }

                            $return["success"] = false;
                            $return["message"] = $array;
                            
                        } catch (Exception $e) {
                            $return["success"] = false;
                            $return["message"] = "fail to sherch quest";
                        }

                    } catch (Exception $e) {
                        $return["success"] = false;
                        $return["message"] = "error to take id from token";
                    }

                } else {
                    $return["success"] = false;
                    $return["message"] = "data missing";
                }

                break;

            case 'finishQuest':

                if (isset($data->{'tokenUser'}) &&  isset($data->{'idQuest'})) {

                    try {

                        $request= $pdo->prepare("SELECT `id` FROM `user` WHERE token = :token");
                        $request->bindParam(':token', $data->{'tokenUser'});
                        $request->execute();
                        $result = $request->fetch(PDO::FETCH_ASSOC);


                        try {

                            $request= $pdo->prepare("UPDATE `quest_asign` SET `etat` = 1 WHERE `idUser` = :idUser AND `idQuest` = :idQuest");
                            $request->bindParam(':idUser', $result['id']);
                            $request->bindParam(':idQuest', $data->{'idQuest'});
                            $request->execute();

                            $return["success"] = true;
                            $return["message"] = "remove success";

                        } catch (Exception $e) {

                            $return["success"] = false;
                            $return["message"] = "error remove";

                        }

                    } catch (Exception $e) {
                        $return["success"] = false;
                        $return["message"] = "error";
                    }

                } else {
                    $return["success"] = false;
                    $return["message"] = "data missing";
                }
                
                break;

            case 'removeQuest':

                if (isset($data->{'tokenUser'}) &&  isset($data->{'idQuest'})) {

                    try {
                        
                        $request= $pdo->prepare("SELECT `id` FROM `user` WHERE token = :token");
                        $request->bindParam(':token', $data->{'tokenUser'});
                        $request->execute();
                        $result = $request->fetch(PDO::FETCH_ASSOC);

                        
                        try {
                            
                            $request= $pdo->prepare("DELETE FROM `quest` WHERE `userAdd` = :idUser AND `id` = :idQuest");
                            $request->bindParam(':idUser', $result['id']);
                            $request->bindParam(':idQuest', $data->{'idQuest'});
                            $request->execute();

                            $return["success"] = true;
                            $return["message"] = "remove success";

                        } catch (Exception $e) {
                            
                            $return["success"] = false;
                            $return["message"] = "error remove";

                        }

                    } catch (Exception $e) {
                        $return["success"] = false;
                        $return["message"] = "error";
                    }

                } else {
                    $return["success"] = false;
                    $return["message"] = "data missing";
                }
                
                break;

            case 'checkToken':
                
                if (isset($data->{'token'})) {
                    try {
                        $request = $pdo->prepare("SELECT * FROM `user` WHERE token = :token");
                        $request->bindParam(':token', $data->{'token'});
                        $request->execute();
                        $result = $request->fetch(PDO::FETCH_ASSOC);
                    
                        if ($result == "") {
                            $return["success"] = false;
                            $return["message"] = "token n'existe pas";
                        }else {
                    
                            $today = date("Y-m-d");
                    
                            if ($today > $result['dateToken']) {
                                $return["success"] = false;
                                $return["message"] = "token expired";
                            } else {
                                $return["success"] = true;
                                $return["message"] = "good token";
                            }
                        }
                    
                    } catch (Exeption $e) {
                        $return["success"] = false;
                        $return["message"] = "error";
                    }
                } else {
                    $return["success"] = false;
                    $return["message"] = "data missing";
                }
                

                break;

            case 'lookFinish':
                
                if (isset($data->{'idQuest'})) {

                    $request= $pdo->prepare("SELECT `user`.`username`FROM `quest_asign` LEFT JOIN `user` ON `quest_asign`.`idUser` = `user`.`id` WHERE `quest_asign`.`idQuest` = :idQuest AND `quest_asign`.`etat` = 1");
                    $request->bindParam(':idQuest', $data->{'idQuest'});
                    $request->execute();
                    $result = $request->fetchall();

                    $return["success"] = true;
                    $return["message"] = $result;
                }

                break;

            case 'questFinish':
                
                if (isset($data->{'idQuest'}) && isset($data->{'username'})) {

                    $request = $pdo->prepare("SELECT * FROM `user` WHERE username = :username");
                    $request->bindParam(':username', $data->{'username'});
                    $request->execute();
                    $result = $request->fetch(PDO::FETCH_ASSOC);

                    $request= $pdo->prepare("DELETE FROM `quest_asign` WHERE `idUser` = :idUser AND `idQuest` = :idQuest");
                    $request->bindParam(':idUser', $result['id']);
                    $request->bindParam(':idQuest', $data->{'idQuest'});
                    $request->execute();

                    $return["success"] = true;
                    $return["message"] = $result['id'];

                }

                break;
            
                case 'questRefuse':
                
                    if (isset($data->{'idQuest'}) && isset($data->{'username'})) {
    
                        $request = $pdo->prepare("SELECT * FROM `user` WHERE username = :username");
                        $request->bindParam(':username', $data->{'username'});
                        $request->execute();
                        $result = $request->fetch(PDO::FETCH_ASSOC);
    
                        $request= $pdo->prepare("UPDATE `quest_asign` SET `etat` = 0 WHERE `idUser` = :idUser AND `idQuest` = :idQuest");
                        $request->bindParam(':idUser', $result['id']);
                        $request->bindParam(':idQuest', $data->{'idQuest'});
                        $request->execute();
    
                        $return["success"] = true;
                        $return["message"] = $result['id'];
    
                    }
    
                    break;

                case 'cancelQuest':
                
                    if (isset($data->{'tokenUser'}) && isset($data->{'idQuest'})) {

                        $request= $pdo->prepare("SELECT `id` FROM `user` WHERE token = :token");
                        $request->bindParam(':token', $data->{'tokenUser'});
                        $request->execute();
                        $result = $request->fetch(PDO::FETCH_ASSOC);

                        $request= $pdo->prepare("DELETE FROM `quest_asign` WHERE `idUser` = :idUser AND `idQuest` = :idQuest");
                        $request->bindParam(':idUser', $result['id']);
                        $request->bindParam(':idQuest', $data->{'idQuest'});
                        $request->execute();

                        $return["success"] = true;
                        $return["message"] = "ok";
    
                    }
    
                    break;





                default:

                $return["success"] = false;
                $return["message"] = "error in argument action";
            
                break;
        }
    }

?>