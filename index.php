
<?php
session_start();
include_once 'request.php';
validNivel();
if(isset($_POST['email']) && isset($_POST['passwd'])){
	$request=new request();
	$request->data=json_encode(array(
		"email"=>$_POST['email'],
		"password"=>$_POST['passwd']
	));
	$request->url="http://localhost/agroSmart/api/usuarios/login.php";
	$result= json_decode($request->sendPost(), true);
	if(isset($result['jwt'])){
		$_SESSION['jwt']=$result['jwt'];
		$_SESSION['nombre']=$result['nombre'];
		$_SESSION['nivel']=$result['nivel'];
		validNivel();
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Inicio de seccion</title>
		<link rel="stylesheet" type="text/css" href="css/index.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body>
		<img class="logo" src="img/logo.png">
		<?php if(isset($result['message'])){
			echo '<h4 class="msg">'.$result['message'].'</h4>';
		} ?>
		<form method="post" action="">
		  <input type="email" name="email" placeholder="Email"/>
		  <input type="password" name="passwd" placeholder="Contrase&ntilde;a" />
		  <input type="submit" value="Entrar"/>
		</form>
	</body>
</html>