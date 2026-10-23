<?php

// funcion para limpiar el texto recibido por post
function limpiarTexto($dato){

    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);

    return $dato;

}

// funcion para comprobar si la respuesta es correcta
function comprobarSolucion($respuestaUsuario, $solucionesValidas){

    $respuestaUsuario = strtolower(trim($respuestaUsuario));

    foreach ($solucionesValidas as $solucion) {

        if ($respuestaUsuario == strtolower(trim($solucion))) {

            return true;

        }

    }

    return false;

}

// funcion para mostrar las pistas solicitadas
function mostrarPistas($arrayPistas, $totalPistasPedidas){

    $salida = "";

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

// funcion para pintar los fallos acumulados
function mostrarMarcadorIntentos($numIntentos){

    if ($numIntentos == 0) {

        return "<p class='sin-fallos'>Sin fallos por ahora.</p>";

    }

    $salida = "<div class='marcas-intentos'>";
    $contador = 1;

    while ($contador <= $numIntentos) {

        $salida .= "<span class='marca-fallo'>Fallo " . $contador . "</span> ";
        $contador++;

    }

    $salida .= "</div>";

    return $salida;

}
?>
