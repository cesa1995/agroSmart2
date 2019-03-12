<?php
    session_start();
    if(isset($_SESSION['nivel']) && $_SESSION['nivel']==0){
        include "../../request.php";
        $fincaid=$_GET['idfin'];
        $usuarioid=$_GET['idusu'];
        $request= new request();
        $request->data=json_encode(array(
            "fincaid"=>$fincaid,
            "usuarioid"=>$usuarioid,
            "jwt"=>$_SESSION['jwt']
        ));
        $request->url="http://localhost/agroSmart/api/asociar/addusuario.php";
        $result=json_decode($request->sendPost(),true);
        header("location: asociar.php?id=".$fincaid."&div=0&error=".$result['message']);
    }else{
        header("location: ../../");
    }

?>