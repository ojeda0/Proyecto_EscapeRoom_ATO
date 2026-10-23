<?php
/**
 * ============================================================================
 * PROYECTO: ESCAPE ROOM WEB - MAIRENA DEL ALCOR
 * MÓDULO: prueba1.php (Enigma 1: El Castillo de Luna)
 * ALUMNO: 2º DAW - IES Los Alcores (Desarrollo Web en Entorno Servidor)
 * ============================================================================
 * Propósito pedagógico:
 * Gestiona el primer enigma sobre el Castillo de Luna y el arqueólogo Jorge Bonsor.
 * Incluye validación de sesión, control de flujo con condicionales if/else,
 * recepción y saneamiento de datos por POST, bucle foreach para validar respuestas,
 * y bucle for/while para monitorizar intentos fallidos y pistas desveladas.
 */

require_once 'funciones.php';

// Estructura condicional que asegura que el usuario no acceda sin nombre registrado
controlarAccesoFase(1);

// Obtenemos del array asociativo central los datos del primer desafío
$bancoEnigmas = obtenerBancoEnigmas();
$enigma = $bancoEnigmas['prueba1'];
$clavePrueba = 'prueba1';

// Variables locales para gestionar mensajes en la vista
$mensajeExito = '';
$mensajeError = '';
$mensajeInfo  = '';
$acertado     = false;

// Comprobamos si la fase ya había sido superada en esta sesión
if (isset($_SESSION['fase_actual']) && $_SESSION['fase_actual'] > 1) {
    $acertado = true;
    $mensajeExito = '¡Ya habías resuelto este enigma previamente! Puedes avanzar al siguiente reto o repasar la información.';
}

// Procesamiento de peticiones POST enviadas por el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // CASO 1: El usuario solicita una nueva pista cultural
    if (isset($_POST['accion']) && $_POST['accion'] === 'pedir_pista') {
        $pistasActuales = solicitarPistaPrueba($clavePrueba);
        if ($pistasActuales >= 3) {
            $mensajeInfo = 'Ya has revelado las 3 pistas disponibles para este enigma.';
        } else {
            $mensajeInfo = '¡Se ha desvelado una nueva pista histórica para ayudarte!';
        }
    }

    // CASO 2: El usuario envía una respuesta para resolver el enigma
    if (isset($_POST['accion']) && $_POST['accion'] === 'comprobar_respuesta') {
        $respuestaEnviada = isset($_POST['respuesta']) ? sanearCadena($_POST['respuesta']) : '';

        // Condicional if/else: validamos que el campo no esté vacío
        if (empty($respuestaEnviada)) {
            $mensajeError = 'Debes introducir una respuesta antes de girar el mecanismo del cerrojo.';
        } else {
            // Llamamos a la función modular que utiliza un bucle foreach para comparar
            $esValida = comprobarRespuesta($respuestaEnviada, $enigma['respuestas_validas']);

            // Condicional if/else para bifurcar el flujo de evaluación
            if ($esValida) {
                $acertado = true;
                // Si aún estábamos en la fase 1, ascendemos el progreso a la fase 2
                if ($_SESSION['fase_actual'] === 1) {
                    $_SESSION['fase_actual'] = 2;
                }
                $mensajeExito = '¡ENHORABUENA! Has descifrado la memoria de Jorge Bonsor. El portón del Castillo de Luna se abre con un crujido ancestral permitiéndote el paso hacia las caballerizas.';
            } else {
                // Registramos el fallo en las estructuras de sesión mediante función
                registrarIntentoFallido($clavePrueba, $respuestaEnviada);
                $mensajeError = 'La cerradura no cede. "' . htmlspecialchars($respuestaEnviada, ENT_QUOTES, 'UTF-8') . '" no es la respuesta correcta para este baluarte. Revisa la historia del castillo o solicita una pista.';
            }
        }
    }
}

// Variables de estado leídas de la sesión nativa
$totalIntentos = isset($_SESSION['intentos'][$clavePrueba]) ? $_SESSION['intentos'][$clavePrueba] : 0;
$pistasUsadas  = isset($_SESSION['pistas_usadas'][$clavePrueba]) ? $_SESSION['pistas_usadas'][$clavePrueba] : 0;
$historial     = isset($_SESSION['historial_fallos'][$clavePrueba]) ? $_SESSION['historial_fallos'][$clavePrueba] : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba 1: El Castillo de Luna | Escape Room IES Los Alcores</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <!-- Encabezado con estado del jugador y cronómetro -->
    <header class="cabecera-principal">
        <div class="contenedor-cabecera">
            <div class="logo-instituto">
                <span class="siglas-instituto">IES LOS ALCORES</span>
                <span class="ciclo-daw">2º DAW · DWES</span>
            </div>
            <div class="barra-estado-jugador">
                <span class="info-jugador">👤 Explorador: <strong><?php echo htmlspecialchars($_SESSION['jugador'], ENT_QUOTES, 'UTF-8'); ?></strong></span>
                <span class="cronometro" id="reloj-tiempo">⏱ Tiempo: <?php echo calcularTiempoTranscurrido(); ?></span>
            </div>
        </div>
    </header>

    <!-- Barra de progreso visual del Escape Room -->
    <nav class="barra-fases" aria-label="Progreso del juego">
        <div class="paso-fase activa">
            <span class="paso-numero">1</span>
            <span class="paso-texto">Castillo de Luna</span>
        </div>
        <div class="paso-fase <?php echo ($_SESSION['fase_actual'] >= 2) ? 'desbloqueada' : 'bloqueada'; ?>">
            <span class="paso-numero">2</span>
            <span class="paso-texto">Antonio Mairena</span>
        </div>
        <div class="paso-fase <?php echo ($_SESSION['fase_actual'] >= 3) ? 'desbloqueada' : 'bloqueada'; ?>">
            <span class="paso-numero">3</span>
            <span class="paso-texto">Feria de 1441</span>
        </div>
    </nav>

    <!-- Contenedor central del juego -->
    <main class="contenedor-principal">

        <section class="tarjeta-sala">
            <div class="cabecera-sala">
                <span class="etiqueta-enigma">Etapa 1 de 3</span>
                <h2><?php echo htmlspecialchars($enigma['titulo'], ENT_QUOTES, 'UTF-8'); ?></h2>
                <h3 class="subtitulo-sala"><?php echo htmlspecialchars($enigma['subtitulo'], ENT_QUOTES, 'UTF-8'); ?></h3>
            </div>

            <!-- Narrativa inmersiva -->
            <div class="relato-narrativo">
                <p><?php echo htmlspecialchars($enigma['relato'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>

            <!-- Caja con la pregunta o enigma clave -->
            <div class="caja-enigma-pregunta">
                <h4>📜 Desafío del Torreón:</h4>
                <p class="texto-pregunta"><?php echo htmlspecialchars($enigma['pregunta'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>

            <!-- Alertas dinámicas condicionales de respuesta -->
            <?php if (!empty($mensajeExito)): ?>
                <div class="mensaje-alerta exito" role="alert">
                    ✨ <?php echo $mensajeExito; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($mensajeError)): ?>
                <div class="mensaje-alerta error" role="alert">
                    ❌ <?php echo $mensajeError; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($mensajeInfo)): ?>
                <div class="mensaje-alerta info" role="status">
                    ℹ️ <?php echo htmlspecialchars($mensajeInfo, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <!-- Renderizado del medidor de intentos con bucle for -->
            <?php echo renderizarMedidorIntentosHTML($totalIntentos); ?>

            <!-- Panel de Pistas Desplegable / Interactivo -->
            <div class="seccion-pistas">
                <div class="cabecera-seccion-pistas">
                    <h4>💡 Sistema de Ayuda y Pistas Culturales (<?php echo $pistasUsadas; ?>/3 desbloqueadas)</h4>
                    <button type="button" class="boton-secundario boton-alternar-pistas" id="btn-toggle-pistas">
                        👁 Ver / Ocultar Pistas
                    </button>
                </div>

                <div class="contenedor-desplegable-pistas" id="caja-pistas-contenido">
                    <!-- Bucle foreach invocado en la función para renderizar cada pista -->
                    <?php echo renderizarListaPistasHTML($enigma['pistas'], $pistasUsadas); ?>

                    <?php if ($pistasUsadas < 3 && !$acertado): ?>
                        <form action="prueba1.php" method="POST" class="form-pista">
                            <input type="hidden" name="accion" value="pedir_pista">
                            <button type="submit" class="boton-alerta">
                                🔓 Desbloquear siguiente pista
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Formulario de Respuesta o Botón para Continuar según condicional -->
            <?php if ($acertado): ?>
                <div class="zona-siguiente-fase">
                    <p class="texto-felicidades">¡Camino despejado hacia el siguiente monumento mairenense!</p>
                    <a href="prueba2.php" class="boton-primario boton-grande">
                        Avanzar a la Prueba 2: Antonio Mairena ➔
                    </a>
                </div>
            <?php else: ?>
                <form action="prueba1.php" method="POST" class="formulario-respuesta" id="form-respuesta">
                    <input type="hidden" name="accion" value="comprobar_respuesta">
                    
                    <div class="grupo-campo">
                        <label for="campo-respuesta">Introduce tu respuesta para abrir el portón:</label>
                        <input type="text" 
                               name="respuesta" 
                               id="campo-respuesta" 
                               placeholder="Escribe el nombre o apellido del arqueólogo..." 
                               required 
                               autocomplete="off">
                    </div>

                    <div class="botones-fila">
                        <button type="submit" class="boton-primario">
                            🗝 Probar combinación
                        </button>
                    </div>
                </form>
            <?php endif; ?>

            <!-- Historial de intentos fallidos generado mediante bucle while -->
            <div class="seccion-historial">
                <h4>📜 Registro de combinaciones fallidas en esta puerta:</h4>
                <?php echo renderizarHistorialFallosHTML($historial); ?>
            </div>

            <!-- Pie de la sala: abandonar y reiniciar -->
            <div class="sala-pie-acciones">
                <a href="index.php?accion=reiniciar" class="enlace-reiniciar boton-reiniciar-confirm">
                    ↻ Rendirse y reiniciar la partida
                </a>
            </div>

        </section>

    </main>

    <footer class="pie-pagina">
        <p>IES Los Alcores · 2º DAW · Desarrollo Web en Entorno Servidor · Escape Room Mairena</p>
    </footer>

    <script src="interactividad.js"></script>
</body>
</html>
