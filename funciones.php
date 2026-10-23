<?php
// archivo de funciones para el escape room de mairena
// abraham teba ojeda - 2º daw - ies los alcores

// funcion para limpiar los datos que introduce el usuario por formulario
function limpiarTexto($dato) {
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $dato;
}

// funcion para comprobar si la respuesta del usuario es correcta
// recorre el array de soluciones validas con un bucle foreach
function comprobarSolucion($respuestaUsuario, $solucionesValidas) {
    // pasamos la respuesta a minusculas para que no haya problemas con mayusculas
    $respuestaUsuario = strtolower(trim($respuestaUsuario));

    // bucle foreach para comparar con cada una de las respuestas correctas
    foreach ($solucionesValidas as $solucion) {
        if ($respuestaUsuario == strtolower(trim($solucion))) {
            return true; // si coincide con alguna devolvemos true
        }
    }

    return false; // si termina el bucle y no hay coincidencias devuelve false
}

// funcion para mostrar las pistas desbloqueadas usando un bucle for
function mostrarPistas($arrayPistas, $totalPistasPedidas) {
    $salida = "";

    // bucle for para ir mostrando solo las pistas que ha pedido el usuario
    for ($i = 0; $i < $totalPistasPedidas; $i++) {
        if (isset($arrayPistas[$i])) {
            $numero = $i + 1;
            $salida .= "<div class='item-pista'>";
            $salida .= "<strong>Pista " . $numero . ":</strong> " . $arrayPistas[$i];
            $salida .= "</div>";
        }
    }

    return $salida;
}

// funcion para mostrar los fallos que lleva usando un bucle while
function mostrarMarcadorIntentos($numIntentos) {
    if ($numIntentos == 0) {
        return "<p class='sin-fallos'>Todavía no has cometido ningún fallo en esta prueba.</p>";
    }

    $salida = "<div class='marcas-intentos'>";
    $contador = 1;

    // bucle while para pintar los intentos realizados
    while ($contador <= $numIntentos) {
        $salida .= "<span class='marca-fallo'>Fallo " . $contador . " ❌</span> ";
        $contador++;
    }

    $salida .= "</div>";
    return $salida;
}
?>
