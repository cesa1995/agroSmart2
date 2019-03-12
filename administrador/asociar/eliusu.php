<?php
session_start();
    if(isset($_SESSION['jwt']) && $_SESSION["nivel"]==0){
        include "../../request.php";
        $idfinca = $_GET['idfin'];
        $id = $_GET['fincausuid'];
        $request= new request();
        $request->data=json_encode(array(
            "id"=>$id,
            "jwt"=>$_SESSION['jwt']
        ));
        $request->url="http://localhost/agroSmart/api/asociar/rmusuario.php";
        $result=json_decode($request->sendPost(),true);
        header("location: asociar.php?id=".$idfinca."&div=0&error=".$result['message']);
    }else{
        header("location: ../../");
    }

?>