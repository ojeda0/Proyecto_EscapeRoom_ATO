<?php

require_once 'funciones.php';

$nombre = isset($_POST['nombre']) ? limpiarTexto($_POST['nombre']) : 'Jugador';
$intentos = isset($_POST['intentos']) ? (int)$_POST['intentos'] : 0;
$pistasPedidas = isset($_POST['pistas']) ? (int)$_POST['pistas'] : 0;

$mensaje = "";
$tipoMensaje = "";
$acertado = false;

$solucionesPrueba1 = [
    'jorge bonsor',
    'george bonsor',
    'bonsor'
];

$pistasPrueba1 = [
    "Arqueólogo anglo-francés que excavó varios yacimientos en Los Alcores.",
    "Compró el Castillo de Luna en ruinas en 1902 para vivir en él y restaurarlo.",
    "La respuesta es el hispanista: Jorge Bonsor."
];

// comprobamos si el usuario ha pulsado el boton de pedir pista
if (isset($_POST['btn_pista'])) {

    if ($pistasPedidas < count($pistasPrueba1)) {

        $pistasPedidas++;
        $mensaje = "Has desbloqueado una nueva pista.";
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

        if (comprobarSolucion($respuesta, $solucionesPrueba1)) {

            $acertado = true;
            $mensaje = "¡Correcto! Jorge Bonsor restauró el castillo en 1902. El portón se abre.";
            $tipoMensaje = "exito";

        } else {

            $intentos++;
            $mensaje = "Respuesta incorrecta. Ese no es el arqueólogo del castillo.";
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
    <title>Prueba 1: Castillo de Luna | Escape Room</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <!-- cabecera -->
    <header>
        <div class="cabecera-info">
            <span>Abraham Teba Ojeda</span> | <span>2º DAW</span> | <span>IES Los Alcores</span>
        </div>
        <h1>Prueba 1: El Castillo de Luna</h1>
    </header>

    <main class="contenedor">
        <div class="caja">
            <div class="barra-estado">
                <span>Jugador: <strong><?php echo $nombre; ?></strong></span>
                <span>Fase: 1 de 3</span>
            </div>

            <h2>El enigma del portón medieval</h2>
            
            <p>
                Estás frente al portón principal del Castillo de Luna. Para poder abrirlo tienes que 
                descifrar el nombre del arqueólogo que compró esta fortaleza en 1902 y vivió en ella.
            </p>

            <div class="pregunta-box">
                <p><strong>Pregunta:</strong> ¿Qué célebre arqueólogo restauró y vivió en el Castillo de Luna?</p>
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
                    <p>¡Has superado la primera prueba!</p>
                    <form action="prueba2.php" method="POST">
                        <input type="hidden" name="nombre" value="<?php echo $nombre; ?>">
                        <input type="hidden" name="intentos" value="0">
                        <input type="hidden" name="pistas" value="0">
                        <button type="submit" class="btn">Ir a la Prueba 2 (Antonio Mairena)</button>
                    </form>
                </div>
            <?php else: ?>
                <!-- formulario de respuesta -->
                <form action="prueba1.php" method="POST" class="formulario" id="form-prueba">
                    <input type="hidden" name="nombre" value="<?php echo $nombre; ?>">
                    <input type="hidden" name="intentos" value="<?php echo $intentos; ?>">
                    <input type="hidden" name="pistas" value="<?php echo $pistasPedidas; ?>">

                    <label for="respuesta">Escribe tu respuesta:</label>
                    <input type="text" name="respuesta" id="respuesta" placeholder="Nombre o apellido del arqueólogo...">

                    <div class="botones">
                        <button type="submit" name="btn_comprobar" class="btn">Comprobar respuesta</button>
                        <button type="submit" name="btn_pista" class="btn btn-secundario">Pedir pista (<?php echo $pistasPedidas; ?>/3)</button>
                    </div>
                </form>
            <?php endif; ?>

            <!-- pistas obtenidas -->
            <?php if ($pistasPedidas > 0): ?>
                <div class="caja-pistas">
                    <h3>Pistas obtenidas:</h3>
                    <?php echo mostrarPistas($pistasPrueba1, $pistasPedidas); ?>
                </div>
            <?php endif; ?>

            <!-- intentos fallidos -->
            <div class="caja-intentos">
                <h3>Historial de intentos:</h3>
                <?php echo mostrarMarcadorIntentos($intentos); ?>
            </div>

            <!-- volver al inicio -->
            <div class="enlace-inicio">
                <a href="index.php" class="btn-enlace" id="btn-salir">Abandonar y volver al inicio</a>
            </div>
        </div>
    </main>

    <!-- pie de pagina -->
    <footer>
        <p>EscapeRoom - Desarrollo Web en Entorno de Servidor - Abraham Teba Ojeda</p>
    </footer>

    <script src="interactividad.js"></script>
</body>
</html>
