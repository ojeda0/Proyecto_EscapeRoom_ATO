<?php
// prueba 2: antonio mairena y el flamenco
// abraham teba ojeda - 2º daw - ies los alcores

require_once 'funciones.php';

// recogemos los datos que vienen por post
$nombre = isset($_POST['nombre']) ? limpiarTexto($_POST['nombre']) : 'Jugador';
$intentos = isset($_POST['intentos']) ? (int)$_POST['intentos'] : 0;
$pistasPedidas = isset($_POST['pistas']) ? (int)$_POST['pistas'] : 0;

$mensaje = "";
$tipoMensaje = "";
$acertado = false;

// array asociativo o indexado con las respuestas validas
$solucionesPrueba2 = [
    'llave de oro del cante',
    'la llave de oro del cante',
    'llave de oro',
    'la llave de oro',
    'iii llave de oro del cante',
    'tercera llave de oro del cante'
];

// array con las pistas de la prueba 2
$pistasPrueba2 = [
    "Es uno de los máximos galardones honoríficos en la historia del cante flamenco.",
    "Tiene forma de objeto de metal precioso con el que metafóricamente se abren cerraduras.",
    "Se la entregaron en Córdoba en 1962: la Llave de Oro del Cante."
];

// accion de pedir pista
if (isset($_POST['btn_pista'])) {
    if ($pistasPedidas < count($pistasPrueba2)) {
        $pistasPedidas++;
        $mensaje = "Has desbloqueado una nueva pista sobre Antonio Mairena.";
        $tipoMensaje = "info";
    } else {
        $mensaje = "Ya has usado las 3 pistas disponibles de esta prueba.";
        $tipoMensaje = "info";
    }
}

// accion de comprobar la respuesta enviada
if (isset($_POST['btn_comprobar'])) {
    $respuesta = isset($_POST['respuesta']) ? limpiarTexto($_POST['respuesta']) : '';

    if (empty($respuesta)) {
        $mensaje = "Debes escribir una respuesta antes de comprobar.";
        $tipoMensaje = "error";
    } else {
        // usamos la funcion con bucle foreach
        if (comprobarSolucion($respuesta, $solucionesPrueba2)) {
            $acertado = true;
            $mensaje = "¡Correcto! Antonio Mairena recibió la III Llave de Oro del Cante en 1962.";
            $tipoMensaje = "exito";
        } else {
            $intentos++;
            $mensaje = "Respuesta incorrecta. Ese no es el galardón que ganó el maestro.";
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
    <title>Prueba 2: Antonio Mairena | Escape Room</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <!-- cabecera -->
    <header>
        <div class="cabecera-info">
            <span class="autor">Abraham Teba Ojeda</span>
            <span class="curso">2º DAW</span>
            <span class="instituto">IES Los Alcores</span>
        </div>
        <h1>Prueba 2: El Eco Flamenco de Antonio Mairena</h1>
    </header>

    <main class="contenedor">
        <div class="caja">
            <div class="barra-estado">
                <span>Jugador: <strong><?php echo $nombre; ?></strong></span>
                <span>Fase: 2 de 3</span>
            </div>

            <h2>El secreto de la fragua</h2>
            
            <p>
                Has logrado cruzar el patio del castillo pero ahora te encuentras ante una reja en una antigua fragua. 
                En la pared hay una imagen del gran cantaor mairenense Antonio Cruz García ("Antonio Mairena"). 
                Para que la reja se abra debes recordar el prestigioso trofeo histórico que le otorgaron en 1962 en Córdoba.
            </p>

            <div class="pregunta-box">
                <p><strong>Pregunta:</strong> ¿Qué histórico galardón flamenco recibió Antonio Mairena en 1962?</p>
            </div>

            <!-- mensajes de resultado -->
            <?php if (!empty($mensaje)): ?>
                <div class="alerta <?php echo $tipoMensaje; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <!-- si ha acertado pasa a la fase 3 -->
            <?php if ($acertado): ?>
                <div class="zona-siguiente">
                    <p>¡Segunda prueba superada!</p>
                    <form action="prueba3.php" method="POST">
                        <input type="hidden" name="nombre" value="<?php echo $nombre; ?>">
                        <input type="hidden" name="intentos" value="0">
                        <input type="hidden" name="pistas" value="0">
                        <button type="submit" class="btn">Ir a la Prueba 3 (La Feria de 1441)</button>
                    </form>
                </div>
            <?php else: ?>
                <!-- formulario de respuesta -->
                <form action="prueba2.php" method="POST" class="formulario" id="form-prueba">
                    <input type="hidden" name="nombre" value="<?php echo $nombre; ?>">
                    <input type="hidden" name="intentos" value="<?php echo $intentos; ?>">
                    <input type="hidden" name="pistas" value="<?php echo $pistasPedidas; ?>">

                    <label for="respuesta">Escribe el galardón que ganó Antonio Mairena:</label>
                    <input type="text" name="respuesta" id="respuesta" placeholder="Ej: Llave de oro del cante...">

                    <div class="botones">
                        <button type="submit" name="btn_comprobar" class="btn">Comprobar respuesta</button>
                        <button type="submit" name="btn_pista" class="btn btn-secundario">Pedir pista (<?php echo $pistasPedidas; ?>/3)</button>
                    </div>
                </form>
            <?php endif; ?>

            <!-- pistas desbloqueadas -->
            <?php if ($pistasPedidas > 0): ?>
                <div class="caja-pistas">
                    <h3>Pistas obtenidas:</h3>
                    <?php echo mostrarPistas($pistasPrueba2, $pistasPedidas); ?>
                </div>
            <?php endif; ?>

            <!-- contador de fallos -->
            <div class="caja-intentos">
                <h3>Historial de intentos:</h3>
                <?php echo mostrarMarcadorIntentos($intentos); ?>
            </div>

            <div class="enlace-inicio">
                <a href="index.php" class="btn-enlace" id="btn-salir">Abandonar y volver al inicio</a>
            </div>
        </div>
    </main>

    <!-- pie de pagina -->
    <footer>
        <p>EscapeRoom Desarrollo Web en entorno de servidor</p>
    </footer>

    <script src="interactividad.js"></script>
</body>
</html>
