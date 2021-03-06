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
include_once '../object/fincas.php';
include_once '../object/jwt.php';
include_once '../object/asociar.php';


$data = json_decode(file_get_contents("php://input"));

if(isset($data->jwt)){
    $validToken = new myjwt();
    $validToken->jwt = $data->jwt;
    $token=$validToken->tokenlife();
    if($token && $validToken->nivel==0){
        $database = new Database();
        $db=$database->getConnection();
        $fincas = new fincas($db);
        if(isset($data->id)){
            $fincas->id=$data->id;
            $asociar= new asociar($db);
            $asociar->fincaid=$data->id;

            if($fincas->delete() && $asociar->deleteequipo() && $asociar->deleteusuario()){
                http_response_code(200);
                echo json_encode(array("message"=>"Finca eliminado"));
            }else{
                http_response_code(503);
                echo json_encode(array("message"=>"Finca no eliminado"));
            }
        }else{
            http_response_code(400);
            echo json_encode(array("message"=>"Data Incompleta"));
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