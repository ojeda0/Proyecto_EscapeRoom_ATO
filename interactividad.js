window.addEventListener('load', function() {

    var campoRespuesta = document.getElementById('respuesta');
    var campoNombre = document.getElementById('nombre');

    if (campoRespuesta) {

        campoRespuesta.focus();

    } else if (campoNombre) {

        campoNombre.focus();

    }

    var btnSalir = document.getElementById('btn-salir');

    if (btnSalir) {

        btnSalir.addEventListener('click', function(e) {

            var seguro = confirm("¿Seguro que quieres salir y volver al inicio?");

            if (!seguro) {

                e.preventDefault();

            }

        });

    }

    var formPrueba = document.getElementById('form-prueba');

    if (formPrueba && campoRespuesta) {

        formPrueba.addEventListener('submit', function(e) {

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
