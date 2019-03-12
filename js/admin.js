$(document).ready(function(){
    $("#contenedor").load('../administrador/adminpage.php');
    $("#inicio").click(function(event){
        $("#contenedor").load('../administrador/adminpage.php');
    });
    $("#asociar").click(function(event){
        $("#contenedor").load('../administrador/asociarpage.php');
    });
    $("#salir").click(function(event){
        window.location.href = "../out.php";
    });
});