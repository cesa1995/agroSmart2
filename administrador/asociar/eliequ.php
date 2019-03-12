<?php

session_start();
    if(isset($_SESSION['nivel']) && $_SESSION["nivel"]==0){
        include "../../request.php";
        $idfinca = $_GET['idfin'];
        $id = $_GET['fincaequid'];
        $request = new request();
        $request->data=json_encode(array(
            "id"=>$id,
            "jwt"=>$_SESSION['jwt']
        ));
        $request->url="http://localhost/agroSmart/api/asociar/rmequipo.php";
        $result=json_decode($request->sendPost(),true);
        header("location: asociar.php?id=".$idfinca."&div=1&error=".$result['message']);
    }else{
        header("location: ../../");
    }

?>