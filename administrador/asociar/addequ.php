<?php
    session_start();
    if(isset($_SESSION['nivel']) && $_SESSION['nivel']==0){
        include "../../request.php";
        $fincaid=$_GET['idfin'];
        $equipoid=$_GET['idequ'];
        $request = new request();
        $request->url="http://localhost/agroSmart/api/asociar/addequipo.php";
        $request->data=json_encode(array(
            "fincaid"=>$fincaid,
            "equipoid"=>$equipoid,
            "estado"=>0,
            "jwt"=>$_SESSION['jwt']
        ));
        $result=json_decode($request->sendPost(),true);
        header("location: asociar.php?id=".$fincaid."&div=1&error=".$result['message']);
    }else{
        header("location: ../../");
    }

?>