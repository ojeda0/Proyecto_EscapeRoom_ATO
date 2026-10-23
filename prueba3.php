<?php
/**
 * ============================================================================
 * PROYECTO: ESCAPE ROOM WEB - MAIRENA DEL ALCOR
 * MÓDULO: prueba3.php (Enigma 3: La Feria Antigua de 1441 y Pantalla Final)
 * ALUMNO: 2º DAW - IES Los Alcores (Desarrollo Web en Entorno Servidor)
 * ============================================================================
 * Propósito pedagógico:
 * Desafío final del Escape Room enfocado en el origen histórico de la Feria de 1441.
 * Aplica control condicional estricto de acceso (impide el acceso directo sin
 * superar las fases 1 y 2), validación mediante bucle foreach, cálculo de métricas
 * finales con bucles sobre arrays asociativos y despliegue de la pantalla de victoria.
 */

require_once 'funciones.php';

// Estructura condicional: valida que el usuario superó las fases 1 y 2
controlarAccesoFase(3);

// Obtenemos los datos del tercer y definitivo enigma
$bancoEnigmas = obtenerBancoEnigmas();
$enigma = $bancoEnigmas['prueba3'];
$clavePrueba = 'prueba3';

// Variables locales
$mensajeExito = '';
$mensajeError = '';
$mensajeInfo  = '';
$juegoSuperado = false;

// Comprobamos si la fase final ya había sido completada en la sesión
if (isset($_SESSION['fase_actual']) && $_SESSION['fase_actual'] >= 4) {
    $juegoSuperado = true;
}

// Procesamiento del formulario POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // CASO 1: Solicitud de pista
    if (isset($_POST['accion']) && $_POST['accion'] === 'pedir_pista') {
        $pistasActuales = solicitarPistaPrueba($clavePrueba);
        if ($pistasActuales >= 3) {
            $mensajeInfo = 'Has consultado todas las pistas disponibles para la Feria de 1441.';
        } else {
            $mensajeInfo = '¡Se ha desvelado una pista sobre los monarcas de la Corona de Castilla!';
        }
    }

    // CASO 2: Validación de la respuesta del usuario
    if (isset($_POST['accion']) && $_POST['accion'] === 'comprobar_respuesta') {
        $respuestaEnviada = isset($_POST['respuesta']) ? sanearCadena($_POST['respuesta']) : '';

        // Condicional if/else para validar el envío
        if (empty($respuestaEnviada)) {
            $mensajeError = 'Introduce el nombre del rey de Castilla para abrir el arcón.';
        } else {
            // Función con bucle foreach para cotejar la solución
            $esValida = comprobarRespuesta($respuestaEnviada, $enigma['respuestas_validas']);

            if ($esValida) {
                // El jugador supera el Escape Room completo
                $_SESSION['fase_actual'] = 4;
                $juegoSuperado = true;
                $mensajeExito = '¡EXTRAORDINARIO! Has identificado a Juan II de Castilla. El pergamino de 1441 libera la cerradura definitiva y el portón del castillo se abre de par en par hacia el exterior.';
            } else {
                registrarIntentoFallido($clavePrueba, $respuestaEnviada);
                $mensajeError = 'El sello real no encaja. "' . htmlspecialchars($respuestaEnviada, ENT_QUOTES, 'UTF-8') . '" no fue el monarca que concedió el privilegio de la feria franca a Pedro Ponce de León en 1441.';
            }
        }
    }
}

// Variables de estado de la prueba 3
$totalIntentos = isset($_SESSION['intentos'][$clavePrueba]) ? $_SESSION['intentos'][$clavePrueba] : 0;
$pistasUsadas  = isset($_SESSION['pistas_usadas'][$clavePrueba]) ? $_SESSION['pistas_usadas'][$clavePrueba] : 0;
$historial     = isset($_SESSION['historial_fallos'][$clavePrueba]) ? $_SESSION['historial_fallos'][$clavePrueba] : [];

// BUCLES PARA ESTADÍSTICAS FINALES:
// Si el juego ha sido superado, calculamos los totales acumulados recorriendo los arrays
$totalFallosGlobales = 0;
$totalPistasGlobales = 0;

if ($juegoSuperado) {
    // Bucle foreach para sumar los fallos cometidos en todas las pruebas
    if (isset($_SESSION['intentos']) && is_array($_SESSION['intentos'])) {
        foreach ($_SESSION['intentos'] as $nombreFase => $numFallos) {
            $totalFallosGlobales += (int)$numFallos;
        }
    }

    // Bucle foreach para sumar las pistas consumidas en todo el juego
    if (isset($_SESSION['pistas_usadas']) && is_array($_SESSION['pistas_usadas'])) {
        foreach ($_SESSION['pistas_usadas'] as $nombreFase => $numPistas) {
            $totalPistasGlobales += (int)$numPistas;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $juegoSuperado ? '¡Victoria! Escape Room Completado' : 'Prueba 3: La Feria de 1441'; ?> | IES Los Alcores</title>
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
        <div class="paso-fase completada">
            <a href="prueba2.php" class="enlace-paso">
                <span class="paso-numero">✓</span>
                <span class="paso-texto">Antonio Mairena</span>
            </a>
        </div>
        <div class="paso-fase <?php echo $juegoSuperado ? 'completada' : 'activa'; ?>">
            <span class="paso-numero"><?php echo $juegoSuperado ? '✓' : '3'; ?></span>
            <span class="paso-texto">Feria de 1441</span>
        </div>
    </nav>

    <!-- Contenedor central -->
    <main class="contenedor-principal">

        <?php if ($juegoSuperado): ?>
            <!-- ========================================== -->
            <!-- PANTALLA TRIUNFAL DE ESCAPE COMPLETADO      -->
            <!-- ========================================== -->
            <section class="tarjeta-sala pantalla-victoria">
                <div class="insignia-victoria">🏆 ¡ESCAPE ROOM SUPERADO CON ÉXITO!</div>
                <h2>¡Enhorabuena, <?php echo htmlspecialchars($_SESSION['jugador'], ENT_QUOTES, 'UTF-8'); ?>!</h2>
                
                <p class="resumen-victoria">
                    Has demostrado un conocimiento ejemplar del patrimonio, el flamenco y las raíces históricas de 
                    <strong>Mairena del Alcor</strong>. Gracias a tu ingenio, las puertas del Castillo de Luna se han 
                    abierto y has logrado salir sano y salvo al exterior.
                </p>

                <!-- Panel con estadísticas calculadas mediante bucles -->
                <div class="grid-metricas-victoria">
                    <div class="caja-metrica">
                        <span class="metrica-icono">⏱</span>
                        <span class="metrica-valor"><?php echo calcularTiempoTranscurrido(); ?></span>
                        <span class="metrica-etiqueta">Tiempo Total Invertido</span>
                    </div>

                    <div class="caja-metrica">
                        <span class="metrica-icono">❌</span>
                        <span class="metrica-valor"><?php echo $totalFallosGlobales; ?></span>
                        <span class="metrica-etiqueta">Intentos Fallidos Totales</span>
                    </div>

                    <div class="caja-metrica">
                        <span class="metrica-icono">💡</span>
                        <span class="metrica-valor"><?php echo $totalPistasGlobales; ?> / 9</span>
                        <span class="metrica-etiqueta">Pistas Consultadas</span>
                    </div>
                </div>

                <!-- Resumen cultural de lo aprendido en el proyecto -->
                <div class="resumen-cultural-aprendido">
                    <h3>📚 Cuaderno de Campo Cultural: Mairena del Alcor</h3>
                    <ul class="lista-hitos-culturales">
                        <li>
                            <strong>1. Castillo de Luna:</strong> Fortaleza del siglo XIV de la Casa de Arcos, restaurada con rigor arqueológico en 1902 por el pintor e hispanista anglo-francés <em>Jorge Bonsor</em>.
                        </li>
                        <li>
                            <strong>2. Antonio Mairena:</strong> Cantaor universal y referente del cante jondo gitano-andaluz, galardonado en Córdoba en 1962 con la célebre <em>III Llave de Oro del Cante</em>.
                        </li>
                        <li>
                            <strong>3. Feria de 1441:</strong> La feria más antigua de Andalucía, cuyo privilegio rodado fue otorgado por el rey <em>Juan II de Castilla</em> a Don Pedro Ponce de León.
                        </li>
                    </ul>
                </div>

                <div class="acciones-victoria">
                    <a href="index.php?accion=reiniciar" class="boton-primario boton-grande">
                        ↻ Jugar de nuevo con otro aventurero
                    </a>
                </div>
            </section>

        <?php else: ?>
            <!-- ========================================== -->
            <!-- PANTALLA DE JUEGO ACTIVA: PRUEBA 3         -->
            <!-- ========================================== -->
            <section class="tarjeta-sala">
                <div class="cabecera-sala">
                    <span class="etiqueta-enigma">Etapa Final · Desafío 3 de 3</span>
                    <h2><?php echo htmlspecialchars($enigma['titulo'], ENT_QUOTES, 'UTF-8'); ?></h2>
                    <h3 class="subtitulo-sala"><?php echo htmlspecialchars($enigma['subtitulo'], ENT_QUOTES, 'UTF-8'); ?></h3>
                </div>

                <!-- Relato histórico -->
                <div class="relato-narrativo">
                    <p><?php echo htmlspecialchars($enigma['relato'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>

                <!-- Pregunta del enigma -->
                <div class="caja-enigma-pregunta">
                    <h4>👑 Desafío Real Medieval:</h4>
                    <p class="texto-pregunta"><?php echo htmlspecialchars($enigma['pregunta'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>

                <!-- Alertas condicionales -->
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

                <!-- Renderizado de medidor de intentos con bucle for -->
                <?php echo renderizarMedidorIntentosHTML($totalIntentos); ?>

                <!-- Sección de pistas con bucle foreach -->
                <div class="seccion-pistas">
                    <div class="cabecera-seccion-pistas">
                        <h4>💡 Pistas sobre la Feria de 1441 (<?php echo $pistasUsadas; ?>/3 desbloqueadas)</h4>
                        <button type="button" class="boton-secundario boton-alternar-pistas" id="btn-toggle-pistas">
                            👁 Ver / Ocultar Pistas
                        </button>
                    </div>

                    <div class="contenedor-desplegable-pistas" id="caja-pistas-contenido">
                        <?php echo renderizarListaPistasHTML($enigma['pistas'], $pistasUsadas); ?>

                        <?php if ($pistasUsadas < 3): ?>
                            <form action="prueba3.php" method="POST" class="form-pista">
                                <input type="hidden" name="accion" value="pedir_pista">
                                <button type="submit" class="boton-alerta">
                                    🔓 Desbloquear siguiente pista
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Formulario de respuesta -->
                <form action="prueba3.php" method="POST" class="formulario-respuesta" id="form-respuesta">
                    <input type="hidden" name="accion" value="comprobar_respuesta">
                    
                    <div class="grupo-campo">
                        <label for="campo-respuesta">Introduce el nombre del rey de Castilla otorgante en 1441:</label>
                        <input type="text" 
                               name="respuesta" 
                               id="campo-respuesta" 
                               placeholder="Ej: Juan II de Castilla..." 
                               required 
                               autocomplete="off">
                    </div>

                    <div class="botones-fila">
                        <button type="submit" class="boton-primario">
                            🗝 Probar combinación final y escapar
                        </button>
                    </div>
                </form>

                <!-- Historial de fallos con bucle while -->
                <div class="seccion-historial">
                    <h4>📜 Registro de sellos reales incorrectos probados:</h4>
                    <?php echo renderizarHistorialFallosHTML($historial); ?>
                </div>

                <div class="sala-pie-acciones">
                    <a href="index.php?accion=reiniciar" class="enlace-reiniciar boton-reiniciar-confirm">
                        ↻ Rendirse y reiniciar la partida
                    </a>
                </div>

            </section>
        <?php endif; ?>

    </main>

    <footer class="pie-pagina">
        <p>IES Los Alcores · 2º DAW · Desarrollo Web en Entorno Servidor · Escape Room Mairena</p>
    </footer>

    <script src="interactividad.js"></script>
</body>
</html>
