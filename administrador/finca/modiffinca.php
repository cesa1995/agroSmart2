<?php
	session_start();
	require_once('../../NoCSRF/nocsrf.php');
	if (isset($_SESSION["nivel"]) && $_SESSION["nivel"]==0){
		include '../../request.php';
		$request= new request();
		if(isset($_POST["_token"])){
			if(NoCSRF::check('_token', $_POST, false, 60*10, false)){
				$telefono=$_POST["telefono"];
				$id = $_POST['id'];
				$nombre = $_POST['nombre'];
				$direccion = $_POST['adress'];
				$request->data=json_encode(array(
					"id"=>$id,
					"nombre"=>$nombre,
					"telefono"=>$telefono,
					"direccion"=>$direccion,
					"jwt"=>$_SESSION['jwt']
				));
				$request->url="http://localhost/agroSmart/api/fincas/update.php";
				$result_0=json_decode($request->sendPost(),true);
			}else{
				$result_0['message']="token incorrecto";
			}
		}
		if (isset($_GET["id"])) {
			$id=$_GET['id'];
			$request->data=json_encode(array(
				"id"=>$id,
				"jwt"=>$_SESSION['jwt']
			));
			$request->url="http://localhost/agroSmart/api/fincas/read_one.php";
			$result_1=json_decode($request->sendPost(),true);
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
                <a href="../finca/addfinca.php"><span class="flaticon-agregar"></span>Agregar</a></li>
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
		<h1 class="titulo">Modificar Finca</h1>
		<?php
		if(isset($result_0["message"])){
			$message=$result_0["message"];
		}
		if(isset($result_1["message"])){
			$message=$message.$result_1["message"];
		}
			echo "<h4 class=\"error\">".$message."</h4>";
		?>
		<div class="formulario">
			<form action="" method="post">
				<input type="text" name="nombre" placeholder="Nombre" value="<?php echo $result_1["nombre"]; ?>" autofocus required>
				<input type="tel" name="telefono" placeholder="Telefono" value="<?php echo $result_1["telefono"]; ?>" required>
				<textarea type="text" name="adress" placeholder="Direccion" required><?php echo $result_1["direccion"]; ?></textarea>
				<input type="hidden" name="_token" value="<?php echo NoCSRF::generate('_token'); ?>">
				<input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>">
				<input type="submit" value="Modificar">
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
}else{
	header("location: ../../");
}
?>