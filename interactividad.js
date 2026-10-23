/**
 * ============================================================================
 * PROYECTO: ESCAPE ROOM WEB - MAIRENA DEL ALCOR
 * MÓDULO: interactividad.js (JavaScript Nativo Vanilla)
 * ALUMNO: 2º DAW - IES Los Alcores (Desarrollo Web en Entorno Servidor)
 * ============================================================================
 * Propósito pedagógico:
 * Proporcionar interactividad del lado cliente sin librerías externas:
 * 1. Alternancia (toggle) de visibilidad del contenedor de pistas.
 * 2. Diálogo modal nativo de confirmación antes de reiniciar o abandonar.
 * 3. Enfoque automático (autofocus) en los campos de entrada.
 * 4. Validación básica previa al envío para evitar envíos en blanco.
 */

document.addEventListener('DOMContentLoaded', function () {

    // ========================================================================
    // 1. ALTERNAR VISIBILIDAD DE LA CAJA DE PISTAS (TOGGLE)
    // ========================================================================
    const btnTogglePistas = document.getElementById('btn-toggle-pistas');
    const cajaPistasContenido = document.getElementById('caja-pistas-contenido');

    if (btnTogglePistas && cajaPistasContenido) {
        btnTogglePistas.addEventListener('click', function () {
            // Evaluamos si el contenedor está oculto o visible
            const estaOculto = window.getComputedStyle(cajaPistasContenido).display === 'none';

            if (estaOculto) {
                cajaPistasContenido.style.display = 'flex';
                btnTogglePistas.textContent = '🙈 Ocultar Pistas';
            } else {
                cajaPistasContenido.style.display = 'none';
                btnTogglePistas.textContent = '👁 Ver Pistas';
            }
        });
    }

    // ========================================================================
    // 2. CONFIRMACIÓN NATIVA DE REINICIO O ABANDONO DE PARTIDA
    // ========================================================================
    const botonesReiniciar = document.querySelectorAll('.boton-reiniciar-confirm');

    botonesReiniciar.forEach(function (boton) {
        boton.addEventListener('click', function (evento) {
            const confirmacion = window.confirm(
                '¿Estás seguro de que deseas reiniciar tu partida en el Escape Room de Mairena del Alcor?\n\n' +
                'Se borrarán tu tiempo, tus pistas y tu progreso acumulado.'
            );

            // Si el usuario cancela en el diálogo nativo, detenemos la navegación
            if (!confirmacion) {
                evento.preventDefault();
            }
        });
    });

    // ========================================================================
    // 3. AUTOENFOQUE INTELIGENTE EN EL CAMPO DE TEXTO ACTIVO
    // ========================================================================
    const campoRespuesta = document.getElementById('campo-respuesta');
    const campoNombre = document.getElementById('nombre_jugador');

    if (campoRespuesta) {
        campoRespuesta.focus();
    } else if (campoNombre) {
        campoNombre.focus();
    }

    // ========================================================================
    // 4. VALIDACIÓN PREVIA EN EL CLIENTE (FEEDBACK INMEDIATO)
    // ========================================================================
    const formRespuesta = document.getElementById('form-respuesta');
    if (formRespuesta && campoRespuesta) {
        formRespuesta.addEventListener('submit', function (evento) {
            const valorLimpio = campoRespuesta.value.trim();

            if (valorLimpio.length === 0) {
                evento.preventDefault();
                alert('⚠️ Por favor, escribe una respuesta antes de girar la cerradura.');
                campoRespuesta.focus();
            }
        });
    }

    const formInicio = document.getElementById('form-inicio');
    if (formInicio && campoNombre) {
        formInicio.addEventListener('submit', function (evento) {
            const valorLimpio = campoNombre.value.trim();

            if (valorLimpio.length < 3) {
                evento.preventDefault();
                alert('⚠️ El nombre del jugador debe tener al menos 3 caracteres para registrarse.');
                campoNombre.focus();
            }
        });
    }

});
