<?php
// pagina principal del escape room
// abraham teba ojeda - 2º daw - ies los alcores
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escape Room Mairena del Alcor</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <!-- cabecera sencilla con mis datos -->
    <header>
        <div class="cabecera-info">
            <span class="autor">Abraham Teba Ojeda</span>
            <span class="curso">2º DAW</span>
            <span class="instituto">IES Los Alcores</span>
        </div>
        <h1>Escape Room: Misterio en Mairena del Alcor</h1>
    </header>

    <!-- contenedor principal con la introduccion del juego -->
    <main class="contenedor">
        <div class="caja">
            <h2>¡Atrapado en el Castillo de Luna!</h2>
            
            <p>
                Durante una salida cultural por Mairena del Alcor, las viejas puertas de hierro del 
                Castillo de Luna se han cerrado de repente. Para encontrar la salida tendrás que resolver 
                3 enigmas sobre la historia, las costumbres y los personajes de nuestro pueblo.
            </p>

            <!-- resumen de las tres pruebas a superar -->
            <div class="resumen-pruebas">
                <div class="tarjeta-prueba">
                    <h3>Prueba 1</h3>
                    <p>El Castillo de Luna y el arqueólogo que lo restauró.</p>
                </div>
                <div class="tarjeta-prueba">
                    <h3>Prueba 2</h3>
                    <p>El flamenco y el gran maestro Antonio Mairena.</p>
                </div>
                <div class="tarjeta-prueba">
                    <h3>Prueba 3</h3>
                    <p>El origen histórico de la Feria Antigua de 1441.</p>
                </div>
            </div>

            <!-- formulario para pedir el nombre del jugador y mandarlo por POST a la prueba 1 -->
            <form action="prueba1.php" method="POST" class="formulario" id="form-inicio">
                <label for="nombre">Introduce tu nombre de jugador:</label>
                <input type="text" name="nombre" id="nombre" placeholder="Escribe tu nombre..." required>

                <!-- campos ocultos para inicializar intentos y pistas a 0 -->
                <input type="hidden" name="intentos" value="0">
                <input type="hidden" name="pistas" value="0">

                <button type="submit" class="btn">Empezar Escape Room</button>
            </form>
        </div>
    </main>

    <!-- pie de pagina obligatorio -->
    <footer>
        <p>EscapeRoom Desarrollo Web en entorno de servidor</p>
    </footer>

    <!-- archivo javascript de interactividad -->
    <script src="interactividad.js"></script>
</body>
</html>
