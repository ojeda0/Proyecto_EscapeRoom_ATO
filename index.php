<?php
/**
 * ============================================================================
 * PROYECTO: ESCAPE ROOM WEB - MAIRENA DEL ALCOR
 * MÓDULO: index.php (Portada y Punto de Entrada)
 * ALUMNO: 2º DAW - IES Los Alcores (Desarrollo Web en Entorno Servidor)
 * ============================================================================
 * Propósito pedagógico:
 * Punto de inicio de la aplicación. Gestiona la ambientación de la historia,
 * la captura del nombre del jugador mediante formulario POST, la inicialización
 * de variables de sesión en PHP y la reanudación o reinicio de partidas activas.
 */

require_once 'funciones.php';

// Variable local para mensajes informativos o de validación
$mensajeError = '';

// Procesamiento del reinicio de partida solicitado por GET
if (isset($_GET['accion']) && $_GET['accion'] === 'reiniciar') {
    // Vaciamos el array de sesión y destruimos la sesión nativa
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    header('Location: index.php');
    exit();
}

// Procesamiento del formulario de inicio mediante método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreIngresado = isset($_POST['nombre_jugador']) ? sanearCadena($_POST['nombre_jugador']) : '';

    // Estructura condicional if/else para validar que el nombre no viaje en blanco
    if (empty($nombreIngresado) || strlen($nombreIngresado) < 3) {
        $mensajeError = 'Por favor, introduce un nombre o apodo válido de al menos 3 caracteres para registrar tu aventura.';
    } else {
        // Inicializamos las estructuras de datos en sesión e iniciamos en la Fase 1
        inicializarPartida($nombreIngresado);
        header('Location: prueba1.php');
        exit();
    }
}

// Comprobamos si el jugador ya tiene una partida guardada en sesión
$partidaEnCurso = isset($_SESSION['jugador']) && !empty($_SESSION['jugador']) && isset($_SESSION['fase_actual']);
$faseReanudar = $partidaEnCurso ? (int)$_SESSION['fase_actual'] : 1;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escape Room: El Enigma de los Alcores | IES Los Alcores</title>
    <!-- Hoja de estilos única solicitada para la práctica -->
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <!-- Encabezado corporativo IES Los Alcores -->
    <header class="cabecera-principal">
        <div class="contenedor-cabecera">
            <div class="logo-instituto">
                <span class="siglas-instituto">IES LOS ALCORES</span>
                <span class="ciclo-daw">2º DAW · Entorno Servidor</span>
            </div>
            <div class="titulo-escape">
                <h1>MISTERIO EN LOS ALCORES</h1>
                <p class="subtitulo-escape">Escape Room Histórico-Cultural de Mairena del Alcor</p>
            </div>
        </div>
    </header>

    <!-- Contenedor central -->
    <main class="contenedor-principal">

        <section class="tarjeta-portada">
            <div class="insignia-fase">Desafío Académico DWES</div>
            <h2>El Secreto Oculto del Castillo de Luna</h2>
            
            <p class="texto-introductorio">
                Durante una excursión de investigación del ciclo de <strong>Desarrollo de Aplicaciones Web</strong>, 
                te has adentrado en las profundidades del emblemático <strong>Castillo de Luna</strong>. De repente, 
                un pesado portón de roble y forja se ha cerrado a tus espaldas con estruendo.
            </p>

            <p class="texto-narrativo">
                Grabada en la piedra, una misteriosa inscripción te advierte: 
                <em>«Solo aquel aventurero que domine la historia medieval de Mairena del Alcor, reconozca el legado de 
                nuestro cante jondo y descifre el origen de la feria ganadera más antigua de Andalucía hallará el salvoconducto 
                para regresar a la libertad».</em>
            </p>

            <!-- Resumen visual de las 3 fases del reto -->
            <div class="grid-fases-preview">
                <div class="tarjeta-fase-item">
                    <div class="numero-etapa">1</div>
                    <h3>El Castillo de Luna</h3>
                    <p>Arquitectura defensiva, fosos y el legado arqueológico de Jorge Bonsor.</p>
                </div>
                <div class="tarjeta-fase-item">
                    <div class="numero-etapa">2</div>
                    <h3>Antonio Mairena</h3>
                    <p>El compás gitano, la fragua familiar y la codiciada Llave de Oro del Cante.</p>
                </div>
                <div class="tarjeta-fase-item">
                    <div class="numero-etapa">3</div>
                    <h3>La Feria de 1441</h3>
                    <p>El privilegio real otorgado por la Casa de Trastámara en el siglo XV.</p>
                </div>
            </div>

            <!-- Alerta condicional de validación -->
            <?php if (!empty($mensajeError)): ?>
                <div class="mensaje-alerta error" role="alert">
                    ⚠️ <?php echo htmlspecialchars($mensajeError, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <!-- Condicional if/else: Reanudar partida o Formulario de nuevo jugador -->
            <?php if ($partidaEnCurso): ?>
                <div class="caja-reanudacion">
                    <h3>¡Atención, aventurero!</h3>
                    <p>Detectamos una partida en curso a nombre de <strong><?php echo htmlspecialchars($_SESSION['jugador'], ENT_QUOTES, 'UTF-8'); ?></strong> en la <strong>Fase <?php echo $faseReanudar; ?></strong>.</p>
                    <div class="botones-fila">
                        <?php
                            // Determinamos a qué archivo redirigir con un condicional
                            $destino = 'prueba1.php';
                            if ($faseReanudar === 2) {
                                $destino = 'prueba2.php';
                            } elseif ($faseReanudar >= 3) {
                                $destino = 'prueba3.php';
                            }
                        ?>
                        <a href="<?php echo $destino; ?>" class="boton-primario">▶ Continuar mi aventura</a>
                        <a href="index.php?accion=reiniciar" class="boton-secundario boton-reiniciar-confirm">↻ Empezar desde cero</a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Formulario POST para capturar los datos del nuevo jugador -->
                <form action="index.php" method="POST" class="formulario-inicio" id="form-inicio" novalidate>
                    <div class="grupo-campo">
                        <label for="nombre_jugador">Introduce tu nombre o apodo para iniciar la sesión:</label>
                        <input type="text" 
                               name="nombre_jugador" 
                               id="nombre_jugador" 
                               placeholder="Ej: Abraham, Juan, María..." 
                               required 
                               autocomplete="off" 
                               maxlength="30">
                        <span class="ayuda-campo">Los Alcores registrarán tus intentos y tiempos de resolución.</span>
                    </div>

                    <button type="submit" class="boton-primario ancho-completo">
                        🚪 Cruzar el umbral y comenzar
                    </button>
                </form>
            <?php endif; ?>

        </section>

    </main>

    <!-- Pie de página académico -->
    <footer class="pie-pagina">
        <p>Práctica de Evaluación Obligatoria · <strong>Escape Room Mairena del Alcor</strong></p>
        <p>IES Los Alcores · Curso Académico 2024/2025 · Departamento de Informática</p>
    </footer>

    <!-- Script de interactividad nativo -->
    <script src="interactividad.js"></script>
</body>
</html>
