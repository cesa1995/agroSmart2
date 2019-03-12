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
include_once '../object/comentario.php';
include_once '../object/jwt.php';
include_once '../vendor/autoload.php';

use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;

$data = json_decode(file_get_contents("php://input"));

if(isset($data->jwt)){
    $validToken = new myjwt();
    $validToken->jwt = $data->jwt;
    $token=$validToken->tokenlife();
    if($token){
        $database = new Database();
        $db=$database->getConnection();
        $comentario = new comentario($db);
        if(
            !empty($data->idparcela) || $data->idparcela==0
        ){
            $comentario->idparcela=$data->idparcela;
            if($comentario->delete()){
                http_response_code(201);
                echo json_encode(array("message"=>"Comentarios eliminados"));
            }else{
                http_response_code(404);
                echo json_encode(array("message"=>"Comentario no eliminado."));
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