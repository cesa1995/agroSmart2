<?php
	session_start();
	require_once('../../NoCSRF/nocsrf.php');
	if (isset($_SESSION["nivel"]) && $_SESSION["nivel"]==0){
		require '../../request.php';
		$request=new request();
		if (isset($_POST["_token"]) and $_SESSION["nivel"]==0) {
			if(NoCSRF::check('_token', $_POST, false, 60*10, false)){
				$nombre = $_POST['nombre'];
				$apellido = $_POST['apellido'];
				$nivel = $_POST['nivel'];
				$id = $_POST['id'];
				$request->data=json_encode(array(
					"id"=>$id,
					"nombre"=>$nombre,
					"apellido"=>$apellido,
					"nivel"=>$nivel,
					"jwt"=>$_SESSION['jwt']
				));
				$request->url="http://localhost/agroSmart/api/usuarios/update.php";
				$result_0=json_decode($request->sendPost(),true);
			}
		}else{
			$result_0['message']="token incorrecto";
		}
		if (isset($_GET['id'])){
			$id=$_GET['id'];
			$request->data=json_encode(array(
				"id"=>$id,
				"jwt"=>$_SESSION['jwt']
			));
			$request->url="http://localhost/agroSmart/api/usuarios/read_one.php";
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
            <div class="item"><a href="#"><span class="flaticon-usuario select"></span>Usuario</a>
            <div class="submenu">
                <a href="../usuario/adduser.php"><span class="flaticon-addusuario"></span>Agregar</a>
                <a href="../usuario/edituser.php"><span class="flaticon-ediusuario"></span>Editar</a></li>
            </div></div>
            <div class="item"><a href="#"><span class="flaticon-finca"></span>Finca</a>
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
		<?php
		if (isset($result_0['message'])) {
			$message=$result_0['message'];
		}
		if(isset($result_1['message'])){
			$message=$message." ".$result_1;
		}
		echo "<h4 class=\"error\">".$message."</h4>";
		?>
		<h1 class="titulo">Modificar Usuario</h1>
		<div class="formulario">
			<form action="" method="post">
				<input type="text" name="nombre" placeholder="Nombre" required value="<?php echo $result_1['nombre']; ?>">
				<input type="text" name="apellido" placeholder="Apellido" required value="<?php echo $result_1['apellido']; ?>">
				<select name="nivel" >
					<option value="2">Cliente</option>
					<option value="0">Administrador</option>
					<option value="1">Agronomo</option>
				</select>
				<input type="hidden" name="_token" value="<?php echo NoCSRF::generate('_token'); ?>">
				<input type="hidden" name="id" value="<?php echo $id; ?>">
				<input type="submit" value="Guardar">
			</form>
		</div>
		<div class="reset"><a href="resetpass.php?id=<?php echo $_GET['id']; ?>">Reestablecer Contraseña</a></div>
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