<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escape Room Mairena del Alcor</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <!-- cabecera -->
    <header>
        <div class="cabecera-info">
            <span>Abraham Teba Ojeda</span> | <span>2º DAW</span> | <span>IES Los Alcores</span>
        </div>
        <h1>Escape Room: Misterio en Mairena del Alcor</h1>
    </header>

    <!-- contenedor principal -->
    <main class="contenedor">
        <div class="caja">
            <h2>¡Atrapado en el Castillo de Luna!</h2>
            
            <p>
                Durante una visita cultural por Mairena del Alcor, las puertas de hierro del Castillo de Luna 
                se han cerrado a tus espaldas. Para poder salir tendrás que resolver 3 enigmas sobre la historia, 
                el flamenco y las fiestas de nuestro pueblo.
            </p>

            <div class="resumen-pruebas">
                <div class="tarjeta-prueba">
                    <h3>Prueba 1</h3>
                    <p>El Castillo de Luna y el arqueólogo que lo restauró.</p>
                </div>
                <div class="tarjeta-prueba">
                    <h3>Prueba 2</h3>
                    <p>Antonio Mairena y el cante jondo flamenco.</p>
                </div>
                <div class="tarjeta-prueba">
                    <h3>Prueba 3</h3>
                    <p>La Feria Antigua de 1441 y su origen real.</p>
                </div>
            </div>

            <!-- formulario de inicio -->
            <form action="prueba1.php" method="POST" class="formulario" id="form-inicio">
                <label for="nombre">Introduce tu nombre:</label>
                <input type="text" name="nombre" id="nombre" placeholder="Tu nombre..." required>

                <input type="hidden" name="intentos" value="0">
                <input type="hidden" name="pistas" value="0">

                <button type="submit" class="btn">Empezar Escape Room</button>
            </form>
        </div>
    </main>

    <!-- pie de pagina -->
    <footer>
        <p>EscapeRoom - Desarrollo Web en Entorno de Servidor - Abraham Teba Ojeda</p>
    </footer>

    <script src="interactividad.js"></script>
</body>
</html>
