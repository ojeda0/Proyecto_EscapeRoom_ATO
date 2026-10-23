<?php

require_once 'funciones.php';

$nombre = isset($_POST['nombre']) ? limpiarTexto($_POST['nombre']) : 'Jugador';
$intentos = isset($_POST['intentos']) ? (int)$_POST['intentos'] : 0;
$pistasPedidas = isset($_POST['pistas']) ? (int)$_POST['pistas'] : 0;

$mensaje = "";
$tipoMensaje = "";
$acertado = false;

$solucionesPrueba2 = [
    'llave de oro del cante',
    'la llave de oro del cante',
    'llave de oro',
    'la llave de oro',
    'iii llave de oro del cante'
];

$pistasPrueba2 = [
    "Es uno de los máximos premios honoríficos del flamenco.",
    "Tiene forma de objeto de metal para abrir cerraduras.",
    "La respuesta es: Llave de Oro del Cante (obtenida en 1962)."
];

// comprobamos si el usuario ha pulsado el boton de pedir pista
if (isset($_POST['boton_pista'])) {

    if ($pistasPedidas < count($pistasPrueba2)) {

        $pistasPedidas++;
        $mensaje = "Has desbloqueado una nueva pista sobre Antonio Mairena.";
        $tipoMensaje = "info";

    } else {

        $mensaje = "Ya has pedido todas las pistas disponibles.";
        $tipoMensaje = "info";

    }

}

// comprobamos si el usuario envia una respuesta
if (isset($_POST['boton_comprobar'])) {

    $respuesta = isset($_POST['respuesta']) ? limpiarTexto($_POST['respuesta']) : '';

    if (empty($respuesta)) {

        $mensaje = "Debes escribir una respuesta antes de comprobar.";
        $tipoMensaje = "error";

    } else {

        if (comprobarSolucion($respuesta, $solucionesPrueba2)) {

            $acertado = true;
            $mensaje = "¡Correcto! Antonio Mairena recibió la III Llave de Oro del Cante en 1962.";
            $tipoMensaje = "exito";

        } else {

            $intentos++;
            $mensaje = "Respuesta incorrecta. Ese no es el galardón que ganó.";
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
            <span>Abraham Teba Ojeda</span> | <span>2º DAW</span> | <span>IES Los Alcores</span>
        </div>
        <h1>Prueba 2: El Eco Flamenco de Antonio Mairena</h1>
    </header>

    <main class="contenedor">
        <div class="caja">
            <div class="barra-estado">
                <span>Jugador: <strong><?php echo $nombre; ?></strong></span>
                <span>Fase: 2 de 3</span>
            </div>

            <h2>El enigma de la fragua</h2>
            
            <p>
                Llegas a una antigua fragua donde cuelga una foto del maestro Antonio Mairena. 
                Para que la verja se abra debes recordar qué famoso e histórico galardón flamenco 
                ganó en Córdoba en 1962.
            </p>

            <div class="caja-pregunta">
                <p><strong>Pregunta:</strong> ¿Qué histórico galardón flamenco recibió Antonio Mairena en 1962?</p>
            </div>

            <!-- mensajes -->
            <?php if (!empty($mensaje)): ?>
                <div class="alerta <?php echo $tipoMensaje; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <!-- boton para la siguiente fase -->
            <?php if ($acertado): ?>
                <div class="zona-siguiente">
                    <p>¡Segunda prueba superada!</p>
                    <form action="prueba3.php" method="POST">
                        <input type="hidden" name="nombre" value="<?php echo $nombre; ?>">
                        <input type="hidden" name="intentos" value="0">
                        <input type="hidden" name="pistas" value="0">
                        <button type="submit" class="boton">Ir a la Prueba 3 (La Feria de 1441)</button>
                    </form>
                </div>
            <?php else: ?>
                <!-- formulario de respuesta -->
                <form action="prueba2.php" method="POST" class="formulario" id="formulario-prueba">
                    <input type="hidden" name="nombre" value="<?php echo $nombre; ?>">
                    <input type="hidden" name="intentos" value="<?php echo $intentos; ?>">
                    <input type="hidden" name="pistas" value="<?php echo $pistasPedidas; ?>">

                    <label for="respuesta">Escribe el galardón que ganó Antonio Mairena:</label>
                    <input type="text" name="respuesta" id="respuesta" placeholder="Ej: Premio de oro del cante...">

                    <div class="botones">
                        <button type="submit" name="boton_comprobar" class="boton">Comprobar respuesta</button>
                        <button type="submit" name="boton_pista" class="boton boton-secundario">Pedir pista (<?php echo $pistasPedidas; ?>/3)</button>
                    </div>
                </form>
            <?php endif; ?>

            <!-- pistas obtenidas -->
            <?php if ($pistasPedidas > 0): ?>
                <div class="caja-pistas">
                    <h3>Pistas obtenidas:</h3>
                    <?php echo mostrarPistas($pistasPrueba2, $pistasPedidas); ?>
                </div>
            <?php endif; ?>

            <!-- intentos fallidos -->
            <div class="caja-intentos">
                <h3>Historial de intentos:</h3>
                <?php echo mostrarMarcadorIntentos($intentos); ?>
            </div>

            <!-- volver alinicio -->
            <div class="enlace-inicio">
                <a href="index.php" class="boton-enlace" id="boton-salir">Abandonar y volver al inicio</a>
            </div>
        </div>
    </main>

    <!-- pie de pagina-->
    <footer>
        <p>EscapeRoom - Desarrollo Web en Entorno de Servidor - Abraham Teba Ojeda</p>
    </footer>

    <script src="interactividad.js"></script>
</body>
</html>
