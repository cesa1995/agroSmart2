<?php
	session_start();
	require_once('../../NoCSRF/nocsrf.php');
	if (isset($_SESSION["nivel"]) && $_SESSION["nivel"]==0){
        include '../../request.php';
        $request= new request();
            if (isset($_GET["id"])) {
                $id=$_GET['id'];
                $request->data=json_encode(array(
                    "id"=>$id,
                    "jwt"=>$_SESSION['jwt']
                ));
                $request->url="http://localhost/agroSmart/api/fincas/delete.php";
                $result=json_decode($request->sendPost(),true);
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
    <link rel="stylesheet" href="../../css/finca.css">
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
                <a href="#"><span class="flaticon-editar"></span>Editar</a></li>
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
    <?php if (isset($result['message'])) {
            echo "<h4 class=\"error\">".$result['message']."</h4>";
	} ?>
		<table>
            <caption><h1>Fincas Registradas</h1></caption>
            <thead>
                <tr>
                    <th><h1>id</h1></th>
                    <th><h1>Nombre</h1></th>
                    <th><h1>Telefono</h1></th>
                    <th><h1>Direccion</h1></th>
                </tr>
            </thead>
            <tbody>
            <?php
            $request->data=json_encode(array(
                "jwt"=>$_SESSION['jwt']
            ));
            $request->url="http://localhost/agroSmart/api/fincas/read.php";
            $result=json_decode($request->sendPost(),true);
            foreach ($result['records'] as $row){
            ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['nombre']; ?></td>
                    <td><?php echo $row['telefono']; ?></td>
                    <td><?php echo $row['direccion']; ?></td>
                    <td><a href="modiffinca.php?id=<?php echo $row['id']; ?>">Editar</a></td>
                    <td><a href="?id=<?php echo $row['id']; ?>">Eliminar</a></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
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