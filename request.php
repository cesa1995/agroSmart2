<?php

class request{
    public $data;
    public $url;

    function sendPost(){
        $urlR=curl_init($this->url);
        curl_setopt($urlR,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($urlR,CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($urlR,CURLOPT_POSTFIELDS,$this->data);
        $response=curl_exec($urlR);
        curl_close($urlR);
        if(!$response){
            return false;
        }
        return $response;
    }

}

function validNivel(){
    if ($_SESSION['nivel'] == 0 && isset($_SESSION['nivel'])) {
        header("location: administrador/administrador.php");
    }else if($_SESSION['nivel'] == 1 && isset($_SESSION['nivel'])){
        header("location: agronomo/agronomo.php");
    }else if($_SESSION['nivel'] == 2 && isset($_SESSION['nivel'])){
        header("location: cliente/cliente.php");
    }
}

?>