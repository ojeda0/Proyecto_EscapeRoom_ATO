<?php

require_once 'funciones.php';

$nombre = isset($_POST['nombre']) ? limpiarTexto($_POST['nombre']) : 'Jugador';
$intentos = isset($_POST['intentos']) ? (int)$_POST['intentos'] : 0;
$pistasPedidas = isset($_POST['pistas']) ? (int)$_POST['pistas'] : 0;

$mensaje = "";
$tipoMensaje = "";
$juegoTerminado = false;

$solucionesPrueba3 = [
    'juan ii',
    'juan ii de castilla',
    'rey juan ii',
    'el rey juan ii',
    'juan 2'
];

$pistasPrueba3 = [
    "Rey de la dinastía Trastámara en la primera mitad del siglo XV.",
    "Padre de Enrique IV y de la reina Isabel la Católica.",
    "La respuesta es: Juan II (o Juan II de Castilla)."
];

// comprobamos si el usuario ha pulsado el boton de pedir pista
if (isset($_POST['btn_pista'])) {

    if ($pistasPedidas < count($pistasPrueba3)) {

        $pistasPedidas++;
        $mensaje = "Has desbloqueado una nueva pista histórica.";
        $tipoMensaje = "info";

    } else {

        $mensaje = "Ya has pedido todas las pistas disponibles.";
        $tipoMensaje = "info";

    }

}

// comprobamos si el usuario envia una respuesta
if (isset($_POST['btn_comprobar'])) {

    $respuesta = isset($_POST['respuesta']) ? limpiarTexto($_POST['respuesta']) : '';

    if (empty($respuesta)) {

        $mensaje = "Debes escribir una respuesta antes de comprobar.";
        $tipoMensaje = "error";

    } else {

        if (comprobarSolucion($respuesta, $solucionesPrueba3)) {

            $juegoTerminado = true;
            $mensaje = "¡Respuesta correcta! Has identificado al rey Juan II de Castilla.";
            $tipoMensaje = "exito";

        } else {

            $intentos++;
            $mensaje = "Respuesta incorrecta. Ese no fue el rey que concedió la feria.";
            $tipoMensaje = "error";

        }

    }

}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba 3: La Feria de 1441 | Escape Room</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <!-- cabecera -->
    <header>
        <div class="cabecera-info">
            <span>Abraham Teba Ojeda</span> | <span>2º DAW</span> | <span>IES Los Alcores</span>
        </div>
        <h1>Prueba 3: La Feria Antigua de 1441</h1>
    </header>

    <main class="contenedor">
        <div class="caja">
            <div class="barra-estado">
                <span>Jugador: <strong><?php echo $nombre; ?></strong></span>
                <span>Fase: 3 de 3</span>
            </div>

            <?php if ($juegoTerminado): ?>
                <div class="pantalla-final">
                    <h2>¡ENHORABUENA, <?php echo strtoupper($nombre); ?>!</h2>
                    <p class="texto-felicidades">¡Has completado el Escape Room y has conseguido salir del Castillo de Luna!</p>

                    <div class="resumen-final">
                        <h3>Resumen cultural aprendido:</h3>
                        <ul>
                            <li><strong>Castillo de Luna:</strong> Restaurado en 1902 por el arqueólogo Jorge Bonsor.</li>
                            <li><strong>Antonio Mairena:</strong> Ganó la III Llave de Oro del Cante en Córdoba en 1962.</li>
                            <li><strong>Feria de 1441:</strong> La más antigua de Andalucía, concedida por el rey Juan II de Castilla a Pedro Ponce de León.</li>
                        </ul>
                    </div>

                    <a href="index.php" class="btn">Volver a jugar</a>
                </div>

            <?php else: ?>
                <h2>El enigma del privilegio rodado</h2>
                
                <p>
                    Llegas a la última sala. Sobre un atril descansa el pergamino que permitió fundar en Mairena 
                    la feria más antigua de Andalucía (1441). Para abrir la puerta final debes responder qué rey de 
                    Castilla concedió este privilegio a Pedro Ponce de León.
                </p>

                <div class="pregunta-box">
                    <p><strong>Pregunta:</strong> ¿Qué rey castellano concedió en 1441 el privilegio para la feria de Mairena?</p>
                </div>

                <?php if (!empty($mensaje)): ?>
                    <div class="alerta <?php echo $tipoMensaje; ?>">
                        <?php echo $mensaje; ?>
                    </div>
                <?php endif; ?>

                <form action="prueba3.php" method="POST" class="formulario" id="form-prueba">
                    <input type="hidden" name="nombre" value="<?php echo $nombre; ?>">
                    <input type="hidden" name="intentos" value="<?php echo $intentos; ?>">
                    <input type="hidden" name="pistas" value="<?php echo $pistasPedidas; ?>">

                    <label for="respuesta">Escribe el nombre del monarca:</label>
                    <input type="text" name="respuesta" id="respuesta" placeholder="Ej: Juan II...">

                    <div class="botones">
                        <button type="submit" name="btn_comprobar" class="btn">Comprobar y escapar</button>
                        <button type="submit" name="btn_pista" class="btn btn-secundario">Pedir pista (<?php echo $pistasPedidas; ?>/3)</button>
                    </div>
                </form>

                <?php if ($pistasPedidas > 0): ?>
                    <div class="caja-pistas">
                        <h3>Pistas obtenidas:</h3>
                        <?php echo mostrarPistas($pistasPrueba3, $pistasPedidas); ?>
                    </div>
                <?php endif; ?>

                <div class="caja-intentos">
                    <h3>Historial de intentos:</h3>
                    <?php echo mostrarMarcadorIntentos($intentos); ?>
                </div>

                <div class="enlace-inicio">
                    <a href="index.php" class="btn-enlace" id="btn-salir">Abandonar y volver al inicio</a>
                </div>

            <?php endif; ?>
        </div>
    </main>

    <!-- pie de pagina -->
    <footer>
        <p>EscapeRoom - Desarrollo Web en Entorno de Servidor - Abraham Teba Ojeda</p>
    </footer>

    <script src="interactividad.js"></script>
</body>
</html>
