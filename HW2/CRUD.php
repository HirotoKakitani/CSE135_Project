<?php
    $method = $_SERVER['REQUEST_METHOD'];
    $jsonFile = "users.json";
    
    switch($method){
        case 'POST':    //create
            $newUser = array("fullname"=> $_POST["fullname"], "login"=> $_POST["login"] , "admin"=> $_POST["admin"]);
            header("Content-Type", "application/json");
            $jsonFileData = file_get_contents($jsonFile);   //get current contents
            $userArray = json_decode($jsonFileData, true);  //decode into array
            array_push($userArray, $newUser);               //add newUser to array
            $jsonFileData = json_encode($userArray);        //make updated array into json
            file_put_contents($jsonFile, $jsonFileData);    //write json into file
            echo json_encode($newUser);
            break;
        case 'GET':     //read
            header("Content-Type", "application/json");
            $jsonFileData = file_get_contents($jsonFile);   //get current contents
            $userArray = json_decode($jsonFileData, true);  //decode into array
            foreach($userArray as $i=>$data){               //iterate through array
                if ($_GET['login'] == $data["login"]){;     //if login matches, echo it
                    echo json_encode($data);
                    break;
                };
            }
            break;
        case 'PUT':     //update
            header("Content-Type", "application/json");
            parse_str(file_get_contents("php://input"),$putVars); //read put request from stdin
            $updatingUser = $putVars["updatingUser"];
            $updatingUserData = null;
            $jsonFileData = file_get_contents($jsonFile);   //get current contents
            $userArray = json_decode($jsonFileData, true);  //decode into array
            foreach($userArray as $i=>$data){               //iterate through array
                if ($putVars['updatingUser'] == $data["login"]){  //if login matches, update
                    //update the user's fields, if applicable
                    $newFullname = ($putVars["fullname"] == null? $data["fullname"] : $putVars["fullname"]);
                    $newLogin = ($putVars["login"] == null ? $data["login"] : $putVars["login"]);
                    $newAdmin = ($putVars["admin"] == null ? $data["admin"] : $putVars["admin"]);
                    $updatedUser = array("fullname"=> $newFullname, "login"=> $newLogin , "admin"=> $newAdmin);
                    
                    //delete old element from array
                    if (($key = array_search($data, $userArray)) !== false){
                        unset($userArray[$key]);
                    } 
                    array_push($userArray, $updatedUser);           //add updatedUser to array
                    //write updated user to file
                    $jsonFileData = json_encode($userArray);        //make updated array into json
                    file_put_contents($jsonFile, $jsonFileData);    //write json into file
                    echo json_encode($updatedUser);
                    break;
                }
            }
            break;
        case 'DELETE':  //delete
            header("Content-Type", "application/json");
            parse_str(file_get_contents("php://input"),$delVars); //read put request from stdin
            $jsonFileData = file_get_contents($jsonFile);   //get current contents
            $userArray = json_decode($jsonFileData, true);  //decode into array
            foreach($userArray as $i=>$data){               //iterate through array
                if ($delVars['login'] == $data["login"]){  //if login matches delete
                    if (($key = array_search($data, $userArray)) !== false){
                        unset($userArray[$key]);
                    }
                    $jsonFileData = json_encode($userArray);        //make updated array into json
                    file_put_contents($jsonFile, $jsonFileData);    //write json into file
                    echo $jsonFileData;
                    break; 
                }
            }
            break;
        default:
            break;
    }
?>
