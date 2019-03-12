<?php
	session_start();
	require_once('../../NoCSRF/nocsrf.php');
	if (isset($_SESSION["nivel"]) && $_SESSION["nivel"]==0){
        if (isset($_POST["_token"]) and $_SESSION["nivel"]==0) {
            if (NoCSRF::check('_token', $_POST, false, 60*10, false)) {
                $telefono=$_POST["telefono"];
                include '../../request.php';
                $nombre=$_POST["nombre"];
                $direccion=$_POST["adress"];
                $request=new request();
                $request->data=json_encode(array(
                    "nombre"=>$nombre,
                    "telefono"=>$telefono,
                    "direccion"=>$direccion,
                    "jwt"=>$_SESSION['jwt']
                ));
                $request->url="http://localhost/agroSmart/api/fincas/create.php";
                $result=json_decode($request->sendPost(),true);
            }
        }
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="../../img/logo.ico" type="image/x-icon">
	<title>Agregar</title>
	<link rel="stylesheet" href="../../css/administrador.css">
	<link rel="stylesheet" href="../../iconos/font/flaticon.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<header>
        <label for="check" class="flaticon-menu iconmenu"></label>
        <input type="checkbox" id="check" name="check">
        <div class="logo">
            <img src="../../img/logo.png">
            <a>Smart Agroindustry</a>
        </div>
        <nav>
            <div class="item"><a href="../../"><span class="flaticon-inicio"></span>Inicio</a></div>
            <div class="item"><a href="#"><span class="flaticon-usuario"></span>Usuario</a>
            <div class="submenu">
                <a href="../usuario/adduser.php"><span class="flaticon-addusuario"></span>Agregar</a>
                <a href="../usuario/edituser.php"><span class="flaticon-ediusuario"></span>Editar</a></li>
            </div></div>
            <div class="item"><a href="#"><span class="flaticon-finca select"></span>Finca</a>
            <div class="submenu">
                <a href="#"><span class="flaticon-agregar"></span>Agregar</a></li>
                <a href="../finca/editfinca.php"><span class="flaticon-editar"></span>Editar</a></li>
            </div></div>
            <div class="item"><a href="#"><span class="flaticon-sensor"></span>Equipos</a>
            <div class="submenu">
                <a href="../equipo/addequipo.php"><span class="flaticon-agregar"></span>Agregar</a></li>
                <a href="../equipo/editequipo.php"><span class="flaticon-editar"></span>Editar</a></li>
            </div></div>
            <div class="item"><a href="../asociar/asociar.php"><span class="flaticon-sincronizar"></span>Asociar</a></li></div>
            <div class="item"><a href="../../out.php"><span class="flaticon-salir"></span>Salir</a></li></div>
    	</nav>
	</header>
	<main>
		<h1 class="titulo">Agregar Finca</h1>
		<?php if (isset($result['message'])) {
                    echo "<h4 class=\"error\">".$result['message']."</h4>";
		} ?>
		<div class="formulario">
			<form action="" method="post">
                <input type="text" name="nombre" placeholder="Nombre" autofocus required>
                <input type="tel" name="telefono" placeholder="telefono" required>
				<textarea type="text" name="adress" placeholder="Direccion" required></textarea>
				<input type="hidden" name="_token" value="<?php echo NoCSRF::generate('_token'); ?>">
				<input type="submit" value="Agregar">
			</form>
		</div>
	</main>
	<footer>
        <p>Smart Agroindustry &copy; 2018 by <span>Cesar Contreras</span></p>
    </footer>
</body>
</html>
<?php
}else{
	header("location: ../../");
}
?>