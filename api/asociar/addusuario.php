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
            !empty($data->idfp) || $data->idfp=="0" &&
            !empty($data->ide) || $data->ide=="0" &&
            !empty($data->estado) || $data->estado=="0"
        ){
            $asociar->idfp=$data->idfp;
            $asociar->ide=$data->ide;
            $asociar->tipo=1;
            $asociar->estado=$data->estado;
            if($asociar->validUsuario()){
                if($asociar->addElemento()){
                    http_response_code(201);
                    echo json_encode(array("message"=>"Usuario agegado."));
                }else{
                    http_response_code(503);
                    echo json_encode(array("message"=>"Usuario no agregado."));
                }
            }else{
                http_response_code(503);
                echo json_encode(array("message"=>"Usuario ya agregado a la parcela."));
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