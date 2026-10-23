<?php
/**
 * ============================================================================
 * PROYECTO: ESCAPE ROOM WEB - MAIRENA DEL ALCOR
 * MÓDULO: prueba2.php (Enigma 2: Antonio Mairena y el Flamenco)
 * ALUMNO: 2º DAW - IES Los Alcores (Desarrollo Web en Entorno Servidor)
 * ============================================================================
 * Propósito pedagógico:
 * Segundo acertijo del Escape Room centrado en el cante jondo y Antonio Mairena.
 * Aplica control de acceso obligatorio mediante condicionales (no permite entrar
 * sin haber completado la Fase 1), actualización de estado de sesión,
 * bucle foreach para evaluación de respuestas y bucles de renderizado visual.
 */

require_once 'funciones.php';

// Estructura condicional: garantiza que el usuario solo acceda si completó la prueba 1
controlarAccesoFase(2);

// Obtenemos del array asociativo la configuración del segundo enigma
$bancoEnigmas = obtenerBancoEnigmas();
$enigma = $bancoEnigmas['prueba2'];
$clavePrueba = 'prueba2';

// Variables locales para retroalimentación
$mensajeExito = '';
$mensajeError = '';
$mensajeInfo  = '';
$acertado     = false;

// Verificamos si esta fase ya estaba superada
if (isset($_SESSION['fase_actual']) && $_SESSION['fase_actual'] > 2) {
    $acertado = true;
    $mensajeExito = '¡Ya habías descifrado este acertijo flamenco! Puedes continuar hacia la última prueba.';
}

// Procesamiento de peticiones POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // CASO 1: Solicitud de pista adicional
    if (isset($_POST['accion']) && $_POST['accion'] === 'pedir_pista') {
        $pistasActuales = solicitarPistaPrueba($clavePrueba);
        if ($pistasActuales >= 3) {
            $mensajeInfo = 'Has agotado las 3 pistas disponibles para este enigma.';
        } else {
            $mensajeInfo = '¡Se ha revelado una nueva pista sobre el maestro Antonio Mairena!';
        }
    }

    // CASO 2: Validación de la respuesta enviada por el usuario
    if (isset($_POST['accion']) && $_POST['accion'] === 'comprobar_respuesta') {
        $respuestaEnviada = isset($_POST['respuesta']) ? sanearCadena($_POST['respuesta']) : '';

        // Condicional if/else para validar la presencia de texto
        if (empty($respuestaEnviada)) {
            $mensajeError = 'Debes introducir una respuesta antes de golpear el yunque.';
        } else {
            // Invocación a función con bucle foreach interno para comparar
            $esValida = comprobarRespuesta($respuestaEnviada, $enigma['respuestas_validas']);

            if ($esValida) {
                $acertado = true;
                // Si la sesión marcaba fase 2, promocionamos a la fase 3
                if ($_SESSION['fase_actual'] === 2) {
                    $_SESSION['fase_actual'] = 3;
                }
                $mensajeExito = '¡MAGISTRAL! Has recordado la III Llave de Oro del Cante otorgada en 1962. El eco flamenco hace vibrar la reja de hierro, abriéndose de par en par hacia las galerías subterráneas.';
            } else {
                // Registrar intento fallido en sesión
                registrarIntentoFallido($clavePrueba, $respuestaEnviada);
                $mensajeError = 'La fragua se apaga. "' . htmlspecialchars($respuestaEnviada, ENT_QUOTES, 'UTF-8') . '" no es el galardón histórico que consagró a Antonio Mairena en 1962. ¡Sigue investigando!';
            }
        }
    }
}

// Lectura de variables desde la sesión nativa
$totalIntentos = isset($_SESSION['intentos'][$clavePrueba]) ? $_SESSION['intentos'][$clavePrueba] : 0;
$pistasUsadas  = isset($_SESSION['pistas_usadas'][$clavePrueba]) ? $_SESSION['pistas_usadas'][$clavePrueba] : 0;
$historial     = isset($_SESSION['historial_fallos'][$clavePrueba]) ? $_SESSION['historial_fallos'][$clavePrueba] : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba 2: Antonio Mairena y el Flamenco | Escape Room IES Los Alcores</title>
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

    <!-- Barra de progreso visual -->
    <nav class="barra-fases" aria-label="Progreso del juego">
        <div class="paso-fase completada">
            <a href="prueba1.php" class="enlace-paso">
                <span class="paso-numero">✓</span>
                <span class="paso-texto">Castillo de Luna</span>
            </a>
        </div>
        <div class="paso-fase activa">
            <span class="paso-numero">2</span>
            <span class="paso-texto">Antonio Mairena</span>
        </div>
        <div class="paso-fase <?php echo ($_SESSION['fase_actual'] >= 3) ? 'desbloqueada' : 'bloqueada'; ?>">
            <span class="paso-numero">3</span>
            <span class="paso-texto">Feria de 1441</span>
        </div>
    </nav>

    <!-- Contenedor central -->
    <main class="contenedor-principal">

        <section class="tarjeta-sala">
            <div class="cabecera-sala">
                <span class="etiqueta-enigma">Etapa 2 de 3</span>
                <h2><?php echo htmlspecialchars($enigma['titulo'], ENT_QUOTES, 'UTF-8'); ?></h2>
                <h3 class="subtitulo-sala"><?php echo htmlspecialchars($enigma['subtitulo'], ENT_QUOTES, 'UTF-8'); ?></h3>
            </div>

            <!-- Narrativa del enigma -->
            <div class="relato-narrativo">
                <p><?php echo htmlspecialchars($enigma['relato'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>

            <!-- Caja con la pregunta del acertijo -->
            <div class="caja-enigma-pregunta">
                <h4>🎙 Desafío del Cante Jondo:</h4>
                <p class="texto-pregunta"><?php echo htmlspecialchars($enigma['pregunta'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>

            <!-- Alertas dinámicas -->
            <?php if (!empty($mensajeExito)): ?>
                <div class="mensaje-alerta exito" role="alert">
                    🎶 <?php echo $mensajeExito; ?>
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

            <!-- Renderizado de intentos con bucle for -->
            <?php echo renderizarMedidorIntentosHTML($totalIntentos); ?>

            <!-- Panel de pistas con bucle foreach -->
            <div class="seccion-pistas">
                <div class="cabecera-seccion-pistas">
                    <h4>💡 Pistas sobre Antonio Mairena (<?php echo $pistasUsadas; ?>/3 desbloqueadas)</h4>
                    <button type="button" class="boton-secundario boton-alternar-pistas" id="btn-toggle-pistas">
                        👁 Ver / Ocultar Pistas
                    </button>
                </div>

                <div class="contenedor-desplegable-pistas" id="caja-pistas-contenido">
                    <?php echo renderizarListaPistasHTML($enigma['pistas'], $pistasUsadas); ?>

                    <?php if ($pistasUsadas < 3 && !$acertado): ?>
                        <form action="prueba2.php" method="POST" class="form-pista">
                            <input type="hidden" name="accion" value="pedir_pista">
                            <button type="submit" class="boton-alerta">
                                🔓 Desbloquear siguiente pista
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Formulario o Botón de Avance -->
            <?php if ($acertado): ?>
                <div class="zona-siguiente-fase">
                    <p class="texto-felicidades">¡El compás gitano te abre el camino hacia la última prueba de Mairena!</p>
                    <a href="prueba3.php" class="boton-primario boton-grande">
                        Avanzar a la Prueba Final: La Feria de 1441 ➔
                    </a>
                </div>
            <?php else: ?>
                <form action="prueba2.php" method="POST" class="formulario-respuesta" id="form-respuesta">
                    <input type="hidden" name="accion" value="comprobar_respuesta">
                    
                    <div class="grupo-campo">
                        <label for="campo-respuesta">Introduce el galardón flamenco conquistado en 1962:</label>
                        <input type="text" 
                               name="respuesta" 
                               id="campo-respuesta" 
                               placeholder="Ej: Llave de oro del cante..." 
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

            <!-- Historial de fallos con bucle while -->
            <div class="seccion-historial">
                <h4>📜 Registro de intentos erróneos en esta fragua:</h4>
                <?php echo renderizarHistorialFallosHTML($historial); ?>
            </div>

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
