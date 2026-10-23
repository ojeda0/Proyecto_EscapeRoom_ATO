// interactividad de la pagina
window.addEventListener('load', function() {

    // foco en el campo de texto
    var campoRespuesta = document.getElementById('respuesta');
    var campoNombre = document.getElementById('nombre');

    if (campoRespuesta) {

        campoRespuesta.focus();

    } else if (campoNombre) {

        campoNombre.focus();

    }

    // confirmacion para volver al inicio
    var botonSalir = document.getElementById('boton-salir');

    if (botonSalir) {

        botonSalir.addEventListener('click', function(evento) {

            var seguro = confirm("¿Seguro que quieres salir y volver al inicio?");

            if (!seguro) {

                evento.preventDefault();

            }

        });

    }

    // comprobar que no se envie vacio
    var formularioPrueba = document.getElementById('formulario-prueba');

    if (formularioPrueba && campoRespuesta) {

        formularioPrueba.addEventListener('submit', function(evento) {

            var botonPulsado = document.activeElement;

            if (botonPulsado && botonPulsado.name === 'boton_comprobar') {

                if (campoRespuesta.value.trim() === '') {

                    evento.preventDefault();
                    alert("Escribe una respuesta antes de comprobar.");
                    campoRespuesta.focus();

                }

            }

        });

    }

});
