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
            !empty($data->idparcela) || $data->idparcela==0 &&
            !empty($data->intervalo) &&
            !empty($data->pag)
        ){
            $comentario->idparcela=$data->idparcela;
            $intervalo=$data->intervalo;
            $pag=$data->pag;
            $stmt=$comentario->read_pag($intervalo,$pag);
            $num = $stmt->rowCount();
            if($num>0){
                $comentario_arr=array();
                $comentario_arr["records"]=array();

                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);

                    $comentario_item=array(
                        "idparcela"=>$idparcela,
                        "mensaje"=>$comentario,
                        "usuario"=>$usuario,
                        "hora"=>$fecha
                    );
                    array_push($comentario_arr["records"], $comentario_item);
                }
                http_response_code(200);
                echo json_encode($comentario_arr);
            }else{
                http_response_code(404);
                echo json_encode(array("message"=>"no hay comentario."));
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
