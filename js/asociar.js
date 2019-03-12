function muestra_oculta(idmostrar,idocultar,aazul,amorada){
    if (document.getElementById){ //se obtiene el id
    var mostrar = document.getElementById(idmostrar); //se define la variable "el" igual a nuestro div
    var ocultar = document.getElementById(idocultar);
    var a1 = document.getElementById(aazul);
    var a2 = document.getElementById(amorada);
    var usu = document.getElementById('agregarusu');
    var equ = document.getElementById('agregaruequ');
    mostrar.style.display = (mostrar.style.display == 'none') ? 'block' : 'none'; //damos un atributo display:none que oculta el div
    ocultar.style.display = (ocultar.style.display == 'block') ? 'none' : 'block';
    a1.style.background = (mostrar.style.display == 'none') ? '#9204b2' : '#7cbcb3';
    a2.style.background = (ocultar.style.display == 'none') ? '#9204b2' : '#7cbcb3';
    usu.style.display = 'none';
    equ.style.display = 'none';
    }
}
window.onload = function(){/*hace que se cargue la función lo que predetermina que div estará oculto hasta llamar a la función nuevamente*/
    muestra_oculta('equipos', 'usuarios', 'aequipos', 'ausuarios');/* "contenido_a_mostrar" es el nombre que le dimos al DIV */
}

function mouseOver(equipo) {
    document.getElementById(equipo).style.background = "#f5f5f5";
}

function mouseOut(equipo,div) {
    var a1 = document.getElementById(equipo);
    var div = document.getElementById(div);
    a1.style.background = (div.style.display == 'block') ? '#9204b2' :'#7cbcb3';
}

function ocultardiv(){
        var minimenu = document.getElementById('minimenu');
        var equipos = document.getElementById('equipos');
        var usuarios = document.getElementById('usuarios');
        minimenu.style.display =(minimenu.style.display == 'block') ? 'none': 'none';
        equipos.style.display = (equipos.style.display == 'block') ? 'none': 'block';
        usuarios.style.display = (usuarios.style.display == 'block') ? 'none': 'block';
}

function mostraragregar(emergente){
    var agregar = document.getElementById(emergente);
    agregar.style.display = (agregar.style.display == 'block') ? 'none' : 'block';
}

function buscar(myinput,myul){
        var input, filter, ul, li, p, i;
        input = document.getElementById(myinput);
        filter = input.value.toUpperCase();
        ul = document.getElementById(myul);
        li = ul.getElementsByTagName('li');

        for (i = 0; i < li.length; i++) {
            p = li[i].getElementsByTagName("p")[0];
            if (p.innerHTML.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
}