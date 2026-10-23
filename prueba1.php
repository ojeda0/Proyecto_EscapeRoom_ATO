<?php
// prueba 1: el castillo de luna y jorge bonsor
// abraham teba ojeda - 2º daw - ies los alcores

require_once 'funciones.php';

// recogemos los datos enviados por post o ponemos valores por defecto
$nombre = isset($_POST['nombre']) ? limpiarTexto($_POST['nombre']) : 'Jugador';
$intentos = isset($_POST['intentos']) ? (int)$_POST['intentos'] : 0;
$pistasPedidas = isset($_POST['pistas']) ? (int)$_POST['pistas'] : 0;

$mensaje = "";
$tipoMensaje = "";
$acertado = false;

// array con las soluciones correctas aceptadas
$solucionesPrueba1 = [
    'jorge bonsor',
    'george bonsor',
    'bonsor'
];

// array con las pistas de la prueba 1
$pistasPrueba1 = [
    "Nació en Francia pero era de origen británico y dedicó su vida a excavar Los Alcores.",
    "Compró el Castillo de Luna en ruinas en 1902 para vivir en él y restaurarlo.",
    "Su apellido da nombre al museo que hay en el castillo: Jorge Bonsor."
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

// comprobamos si el usuario ha enviado una respuesta
if (isset($_POST['btn_comprobar'])) {
    $respuesta = isset($_POST['respuesta']) ? limpiarTexto($_POST['respuesta']) : '';

    // condicion if para comprobar si el campo esta vacio
    if (empty($respuesta)) {
        $mensaje = "Debes escribir una respuesta antes de comprobar.";
        $tipoMensaje = "error";
    } else {
        // llamamos a la funcion que tiene el bucle foreach para validar
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
            <span class="autor">Abraham Teba Ojeda</span>
            <span class="curso">2º DAW</span>
            <span class="instituto">IES Los Alcores</span>
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
                Estás frente a la entrada principal del Castillo de Luna. Para poder cruzar el puente 
                y entrar al patio de armas tienes que descifrar qué ilustre arqueólogo e hispanista compró 
                esta fortaleza en el año 1902, salvándola de la ruina y fijando en ella su residencia.
            </p>

            <div class="pregunta-box">
                <p><strong>Pregunta:</strong> ¿Qué célebre arqueólogo restauró y vivió en el Castillo de Luna?</p>
            </div>

            <!-- si hay algun mensaje de exito, error o info lo mostramos -->
            <?php if (!empty($mensaje)): ?>
                <div class="alerta <?php echo $tipoMensaje; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <!-- si ha acertado mostramos el boton para pasar a la prueba 2 -->
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
                <!-- formulario para enviar la respuesta o pedir pista por POST -->
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

            <!-- zona donde se van mostrando las pistas que ha pedido el usuario -->
            <?php if ($pistasPedidas > 0): ?>
                <div class="caja-pistas">
                    <h3>Pistas obtenidas:</h3>
                    <?php echo mostrarPistas($pistasPrueba1, $pistasPedidas); ?>
                </div>
            <?php endif; ?>

            <!-- mostramos los fallos acumulados con el bucle while de funciones.php -->
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
