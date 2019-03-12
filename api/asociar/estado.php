<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../php-jwt/src/BeforeValidException.php';
require_once '../php-jwt/src/ExpiredException.php';
require_once '../php-jwt/src/SignatureInvalidException.php';
require_once '../php-jwt/src/JWT.php';
include_once '../config/database.php';
include_once '../object/asociar.php';
include_once '../object/jwt.php';


$data = json_decode(file_get_contents("php://input"));

if(isset($data->jwt)){
    $validToken = new myjwt();
    $validToken->jwt = $data->jwt;
    $token=$validToken->tokenlife();
    if($token && $validToken->nivel==0){
        $database = new Database();
        $db=$database->getConnection();
        $asociar = new asociar($db);

        if(
            !empty($data->id) || $data->id=="0" &&
            !empty($data->estado) || $data->estado=="0"
        ){
            $asociar->id=$data->id;
            if($data->estado=="0"){
                $asociar->estado="1";
            }else{
                $asociar->estado="0";
            }
            if($asociar->changeStateElement()){
                http_response_code(201);
                echo json_encode(array("message"=>"El estado ha Cambiado"));
            }else{
                http_response_code(503);
                echo json_encode(array("message"=>"El estado no ha Cambiado."));
            }
        }else{
            http_response_code(400);
            echo json_encode(array("message"=>"Data incompleta."));
        }
    }else{
        http_response_code(401);
        echo json_encode(array("message"=>"no autorizado"));
    }
}else{
    http_response_code(400);
    echo json_encode(array("message"=>"sesion no iniciada."));
}


?>