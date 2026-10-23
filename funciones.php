<?php
/**
 * ============================================================================
 * PROYECTO: ESCAPE ROOM WEB - MAIRENA DEL ALCOR
 * MÓDULO: funciones.php
 * ALUMNO: 2º DAW - IES Los Alcores (Desarrollo Web en Entorno Servidor)
 * ============================================================================
 * Propósito pedagógico:
 * Este archivo centraliza toda la lógica de negocio del Escape Room en funciones
 * procedimentales reutilizables: saneamiento de entradas, validación de respuestas,
 * gestión de pistas progresivas, control de intentos mediante bucles y gestión de sesión.
 */

// Iniciamos la sesión si no ha sido arrancada previamente
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Función: obtenerBancoEnigmas
 * ----------------------------
 * Retorna la estructura de datos completa con los tres acertijos temáticos
 * de Mairena del Alcor en un array asociativo multidimensional.
 * 
 * @return array Array con los datos de las tres pruebas.
 */
function obtenerBancoEnigmas() {
    return [
        'prueba1' => [
            'numero' => 1,
            'titulo' => 'El Bastión de los Ponce de León y el Secreto de Bonsor',
            'subtitulo' => 'Enigma 1: El Castillo de Luna y su Arquitectura Defensiva',
            'relato' => 'Te encuentras atrapado en el patio de armas del Castillo de Luna, fortaleza medieval del siglo XIV '
                      . 'que defendía la comarca de Los Alcores. Frente a ti se alza el portón de la Torre del Homenaje, bloqueado '
                      . 'por un cerrojo con inscripciones arqueológicas. Una placa recuerda al ilustre hispanista y pintor de origen '
                      . 'anglo-francés que en 1902 adquirió esta fortaleza ruinosa, la restauró con esmero y estableció en ella su '
                      . 'residencia y museo personal. Para abrir la cerradura, debes introducir el nombre o apellido de este célebre personaje.',
            'pregunta' => '¿Qué arqueólogo e hispanista restauró el Castillo de Luna a principios del siglo XX y vivió en él?',
            // Array con variantes admitidas para facilitar la experiencia de usuario
            'respuestas_validas' => [
                'jorge bonsor',
                'george bonsor',
                'bonsor',
                'jorge edward bonsor',
                'george edward bonsor saint martin'
            ],
            // Array indexado con las 3 pistas progresivas
            'pistas' => [
                'Pista 1 (Histórica): Nació en Francia de padre británico y dedicó su vida a excavar yacimientos como los de Carmona y Mairena.',
                'Pista 2 (Ubicación): Su apellido da nombre directo al Museo arqueológico que hoy alberga el propio Castillo de Luna.',
                'Pista 3 (Solución directa): Se trata del arqueólogo hispanista Jorge Bonsor (o George Bonsor).'
            ]
        ],
        'prueba2' => [
            'numero' => 2,
            'titulo' => 'El Eco Jondo de la Casa de los Cruz',
            'subtitulo' => 'Enigma 2: El Flamenco y la Figura de Antonio Mairena',
            'relato' => 'Has conseguido atravesar el foso del castillo, pero quedas encerrado en una antigua fragua de la calle Real. '
                      . 'En la pared reposa un yunque junto a una fotografía en blanco y negro del maestro Antonio Cruz García '
                      . '("Antonio Mairena"), Hijo Predilecto de Andalucía y máxima figura del cante gitano-andaluz. '
                      . 'Para abrir la siguiente verja debes ingresar el nombre del legendario y codiciado galardón flamenco '
                      . 'que el maestro conquistó en Córdoba en el año 1962, consagrándose para la eternidad.',
            'pregunta' => '¿Qué histórico galardón flamenco concedido en Córdoba en 1962 consagró la maestría de Antonio Mairena?',
            'respuestas_validas' => [
                'llave de oro del cante',
                'la llave de oro del cante',
                'llave de oro',
                'la llave de oro',
                'iii llave de oro del cante',
                'tercera llave de oro del cante',
                'la llave del cante'
            ],
            'pistas' => [
                'Pista 1 (Simbología): Es un trofeo físico y honorífico muy restringido; solo se ha concedido 5 veces en toda la historia.',
                'Pista 2 (Metáfora): Su nombre evoca un instrumento de metal noble que abre puertas cerradas.',
                'Pista 3 (Solución directa): La distinción es la "Llave de Oro del Cante" (III Llave de Oro ganada en 1962).'
            ]
        ],
        'prueba3' => [
            'numero' => 3,
            'titulo' => 'La Provisión Real de los Alcores',
            'subtitulo' => 'Enigma 3: La Feria Antigua de 1441 y su Origen Histórico',
            'relato' => 'Has llegado al archivo subterráneo de la localidad, donde reposa el pergamino del siglo XV que dio vida '
                      . 'a la feria más antigua de Andalucía, celebrada ininterrumpidamente desde 1441 como punto neurálgico de compraventa '
                      . 'de ganado. Una vitrina blindada custodia la llave de salida definitiva del Escape Room. En la vitrina se lee: '
                      . '"Solo quien reconozca al Monarca de Castilla de la Casa de Trastámara que otorgó a Pedro Ponce de León el '
                      . 'privilegio rodado para fundar esta feria franca franqueará la salida a la luz".',
            'pregunta' => '¿Qué rey castellano firmó en 1441 el privilegio para celebrar la Feria de Mairena del Alcor?',
            'respuestas_validas' => [
                'juan ii',
                'juan ii de castilla',
                'rey juan ii',
                'el rey juan ii',
                'juan 2',
                'rey juan 2'
            ],
            'pistas' => [
                'Pista 1 (Genealogía): Fue monarca de la dinastía Trastámara, hijo de Enrique III y padre de Isabel la Católica.',
                'Pista 2 (Identificación): Su nombre es Juan y reinó con el ordinal número dos.',
                'Pista 3 (Solución directa): Se trata del Rey Juan II de Castilla (o Juan II).'
            ]
        ]
    ];
}

/**
 * Función: sanearCadena
 * ---------------------
 * Limpia y normaliza una cadena de texto introducida por el usuario:
 * elimina espacios al inicio y final, quita barras de escape y convierte caracteres especiales.
 * 
 * @param string $entrada Texto introducido por el usuario.
 * @return string Texto saneado.
 */
function sanearCadena($entrada) {
    if (!isset($entrada)) {
        return '';
    }
    $limpio = trim($entrada);
    $limpio = stripslashes($limpio);
    $limpio = htmlspecialchars($limpio, ENT_QUOTES, 'UTF-8');
    return $limpio;
}

/**
 * Función: normalizarRespuestaParaComparacion
 * ------------------------------------------
 * Convierte el texto a minúsculas y retira tildes o signos diacríticos
 * para permitir una comparación más justa y flexible con las soluciones válidas.
 * 
 * @param string $cadena Cadena a normalizar.
 * @return string Cadena en minúsculas y sin acentos.
 */
function normalizarRespuestaParaComparacion($cadena) {
    $cadena = mb_strtolower(trim($cadena), 'UTF-8');
    
    // Matriz de reemplazo de vocales con tilde y caracteres especiales
    $buscar  = ['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ'];
    $poner   = ['a', 'e', 'i', 'o', 'u', 'u', 'n'];
    $cadena  = str_replace($buscar, $poner, $cadena);
    
    // Eliminamos signos de puntuación sobrantes
    $cadena = preg_replace('/[.,;:\-_]/', ' ', $cadena);
    // Compactamos espacios repetidos a un único espacio
    $cadena = preg_replace('/\s+/', ' ', $cadena);
    
    return trim($cadena);
}

/**
 * Función: comprobarRespuesta
 * ---------------------------
 * Valida si la respuesta del usuario coincide con alguna de las respuestas admitidas.
 * USO DE BUCLE: Se utiliza obligatoriamente un bucle foreach para iterar el array
 * de respuestas válidas y comprobar posibles coincidencias.
 * 
 * @param string $respuestaUsuario Texto enviado en el formulario.
 * @param array $respuestasValidas Array indexado con soluciones posibles.
 * @return bool True si coincide con alguna, False en caso contrario.
 */
function comprobarRespuesta($respuestaUsuario, $respuestasValidas) {
    $normalizadaUsuario = normalizarRespuestaParaComparacion($respuestaUsuario);

    // Bucle foreach obligatorio para recorrer el array de respuestas válidas
    foreach ($respuestasValidas as $opcionValida) {
        $normalizadaValida = normalizarRespuestaParaComparacion($opcionValida);
        
        // Estructura condicional if para evaluar la igualdad exacta
        if ($normalizadaUsuario === $normalizadaValida) {
            return true; // Respuesta correcta encontrada
        }
    }

    return false; // No hubo coincidencias
}

/**
 * Función: inicializarPartida
 * ---------------------------
 * Establece o reinicia los valores de sesión para una nueva partida del Escape Room.
 * 
 * @param string $nombreJugador Nombre indicado por el usuario.
 */
function inicializarPartida($nombreJugador) {
    $_SESSION['jugador'] = sanearCadena($nombreJugador);
    $_SESSION['fase_actual'] = 1; // 1 = prueba1, 2 = prueba2, 3 = prueba3, 4 = completado
    $_SESSION['inicio_tiempo'] = time();
    
    // Arrays asociativos de control de estado
    $_SESSION['intentos'] = [
        'prueba1' => 0,
        'prueba2' => 0,
        'prueba3' => 0
    ];
    
    $_SESSION['pistas_usadas'] = [
        'prueba1' => 0,
        'prueba2' => 0,
        'prueba3' => 0
    ];
    
    $_SESSION['historial_fallos'] = [
        'prueba1' => [],
        'prueba2' => [],
        'prueba3' => []
    ];
}

/**
 * Función: controlarAccesoFase
 * ----------------------------
 * Estructura condicional estricta de flujo: verifica que el jugador no salte fases.
 * Si intenta acceder a prueba2 sin haber superado prueba1, es redirigido a prueba1, etc.
 * 
 * @param int $faseEsperada Número de fase que requiere la página actual.
 */
function controlarAccesoFase($faseEsperada) {
    // Si no hay jugador registrado en sesión, volver a la portada
    if (!isset($_SESSION['jugador']) || empty($_SESSION['jugador'])) {
        header('Location: index.php');
        exit();
    }

    $faseActual = isset($_SESSION['fase_actual']) ? (int)$_SESSION['fase_actual'] : 1;

    // Si el jugador intenta saltarse fases anteriores, redirigir a su fase correspondiente
    if ($faseActual < $faseEsperada) {
        if ($faseActual === 1) {
            header('Location: prueba1.php');
            exit();
        } elseif ($faseActual === 2) {
            header('Location: prueba2.php');
            exit();
        } elseif ($faseActual === 3) {
            header('Location: prueba3.php');
            exit();
        }
    }
}

/**
 * Función: registrarIntentoFallido
 * --------------------------------
 * Incrementa el contador de intentos y almacena la respuesta errónea en el historial.
 * 
 * @param string $clavePrueba 'prueba1', 'prueba2' o 'prueba3'.
 * @param string $respuestaErronea Texto que intentó enviar el usuario.
 */
function registrarIntentoFallido($clavePrueba, $respuestaErronea) {
    if (isset($_SESSION['intentos'][$clavePrueba])) {
        $_SESSION['intentos'][$clavePrueba]++;
    }
    
    if (isset($_SESSION['historial_fallos'][$clavePrueba])) {
        $_SESSION['historial_fallos'][$clavePrueba][] = sanearCadena($respuestaErronea);
    }
}

/**
 * Función: solicitarPistaPrueba
 * -----------------------------
 * Desbloquea una nueva pista para la prueba en curso, hasta un máximo de 3.
 * 
 * @param string $clavePrueba 'prueba1', 'prueba2' o 'prueba3'.
 * @return int Cantidad de pistas que quedan desbloqueadas tras la llamada.
 */
function solicitarPistaPrueba($clavePrueba) {
    if (isset($_SESSION['pistas_usadas'][$clavePrueba])) {
        if ($_SESSION['pistas_usadas'][$clavePrueba] < 3) {
            $_SESSION['pistas_usadas'][$clavePrueba]++;
        }
        return $_SESSION['pistas_usadas'][$clavePrueba];
    }
    return 0;
}

/**
 * Función: renderizarMedidorIntentosHTML
 * --------------------------------------
 * USO DE BUCLE FOR: Genera un indicador visual de intentos realizados mediante
 * un bucle tradicional for, útil pedagógicamente para mostrar el progreso de fallos.
 * 
 * @param int $intentosRealizados Número de intentos erróneos cometidos.
 * @return string Código HTML con los indicadores de intento.
 */
function renderizarMedidorIntentosHTML($intentosRealizados) {
    $salidaHtml = '<div class="medidor-intentos">';
    $salidaHtml .= '<span class="etiqueta-intentos">Intentos registrados en esta prueba: <strong>' . (int)$intentosRealizados . '</strong></span>';
    $salidaHtml .= '<div class="marcas-intentos">';

    // Bucle for justificado para renderizar los iconos de intento realizados
    if ($intentosRealizados === 0) {
        $salidaHtml .= '<span class="marca-intento sin-fallos" title="Aún no has cometido ningún fallo">¡Primer intento limpio!</span>';
    } else {
        // Limitamos visualmente hasta un máximo de 10 marcas para no desbordar
        $limiteGrafico = min($intentosRealizados, 10);
        for ($i = 1; $i <= $limiteGrafico; $i++) {
            $salidaHtml .= '<span class="marca-intento fallo" title="Fallo ' . $i . '">❌ ' . $i . '</span> ';
        }
        if ($intentosRealizados > 10) {
            $salidaHtml .= '<span class="marca-intento extra">+' . ($intentosRealizados - 10) . ' más</span>';
        }
    }

    $salidaHtml .= '</div>';
    $salidaHtml .= '</div>';

    return $salidaHtml;
}

/**
 * Función: renderizarListaPistasHTML
 * ----------------------------------
 * USO DE BUCLE FOREACH: Itera sobre el array de pistas del enigma y genera
 * las cajas visibles u ocultas según cuántas pistas haya solicitado el usuario.
 * 
 * @param array $pistasTotales Array con las 3 pistas.
 * @param int $pistasDesbloqueadas Número de pistas activadas (0 a 3).
 * @return string Código HTML de las pistas.
 */
function renderizarListaPistasHTML($pistasTotales, $pistasDesbloqueadas) {
    $html = '<div class="bloque-pistas-contenedor">';
    $indice = 1;

    // Bucle foreach para iterar y presentar cada pista
    foreach ($pistasTotales as $textoPista) {
        if ($indice <= $pistasDesbloqueadas) {
            // Pista ya desbloqueada
            $html .= '<div class="pista-caja desbloqueada">';
            $html .= '  <div class="pista-cabecera">💡 Pista #' . $indice . ' [Desvelada]</div>';
            $html .= '  <div class="pista-cuerpo">' . htmlspecialchars($textoPista, ENT_QUOTES, 'UTF-8') . '</div>';
            $html .= '</div>';
        } else {
            // Pista aún bloqueada
            $html .= '<div class="pista-caja bloqueada">';
            $html .= '  <div class="pista-cabecera">🔒 Pista #' . $indice . ' [Bloqueada]</div>';
            $html .= '  <div class="pista-cuerpo">Pulsa el botón "Solicitar Pista" para revelar esta ayuda cultural.</div>';
            $html .= '</div>';
        }
        $indice++;
    }

    $html .= '</div>';
    return $html;
}

/**
 * Función: renderizarHistorialFallosHTML
 * --------------------------------------
 * USO DE BUCLE WHILE: Demuestra el uso justificado de la estructura while
 * para extraer y mostrar en orden inverso los últimos intentos fallidos del usuario.
 * 
 * @param array $historial Array indexado con las respuestas fallidas.
 * @return string Código HTML con la lista de respuestas descartadas.
 */
function renderizarHistorialFallosHTML($historial) {
    if (empty($historial)) {
        return '<p class="sin-historial">No hay intentos fallidos registrados todavía en esta sala.</p>';
    }

    $html = '<ul class="lista-fallos">';
    $total = count($historial);
    $posicion = $total - 1;

    // Bucle while para recorrer el array desde el último fallo hacia el primero
    while ($posicion >= 0) {
        $fallo = $historial[$posicion];
        $numeroFallo = $posicion + 1;
        $html .= '<li>Intento #' . $numeroFallo . ': <code>' . htmlspecialchars($fallo, ENT_QUOTES, 'UTF-8') . '</code> (Incorrecto)</li>';
        $posicion--;
    }

    $html .= '</ul>';
    return $html;
}

/**
 * Función: calcularTiempoTranscurrido
 * -----------------------------------
 * Retorna una cadena formateada en minutos y segundos con el tiempo jugado.
 * 
 * @return string Ejemplo: "12 minutos y 34 segundos"
 */
function calcularTiempoTranscurrido() {
    if (!isset($_SESSION['inicio_tiempo'])) {
        return '0 minutos y 0 segundos';
    }
    
    $segundosTotales = time() - $_SESSION['inicio_tiempo'];
    $minutos = floor($segundosTotales / 60);
    $segundos = $segundosTotales % 60;
    
    return $minutos . ' min y ' . $segundos . ' seg';
}
