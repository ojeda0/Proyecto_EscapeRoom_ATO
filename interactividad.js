// archivo de interactividad javascript para el escape room
// abraham teba ojeda - 2º daw - ies los alcores

// esperamos a que cargue la pagina para ejecutar el codigo
window.addEventListener('load', function() {

    // ponemos el foco automaticamente en el campo de texto si existe
    var campoRespuesta = document.getElementById('respuesta');
    var campoNombre = document.getElementById('nombre');

    if (campoRespuesta) {
        campoRespuesta.focus();
    } else if (campoNombre) {
        campoNombre.focus();
    }

    // confirmacion sencilla para cuando el usuario quiere abandonar el juego
    var btnSalir = document.getElementById('btn-salir');
    if (btnSalir) {
        btnSalir.addEventListener('click', function(e) {
            var seguro = confirm("¿Seguro que quieres salir y volver al inicio?");
            if (!seguro) {
                e.preventDefault();
            }
        });
    }

    // validacion basica en el cliente para no mandar la respuesta vacia
    var formPrueba = document.getElementById('form-prueba');
    if (formPrueba && campoRespuesta) {
        formPrueba.addEventListener('submit', function(e) {
            // si el boton pulsado es el de comprobar y el campo esta vacio avisamos
            var botonPulsado = document.activeElement;
            if (botonPulsado && botonPulsado.name === 'btn_comprobar') {
                if (campoRespuesta.value.trim() === '') {
                    e.preventDefault();
                    alert("Escribe una respuesta antes de comprobar.");
                    campoRespuesta.focus();
                }
            }
        });
    }

});
