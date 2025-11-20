<?php
// Limpia toda la caché de PHP
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "✅ Caché de PHP limpiada<br>";
}

// Limpia caché de directorio
clearstatcache();
echo "✅ Caché de directorios limpiada<br>";

echo "<h2>¡Listo! Ahora actualiza la página de pico y placa</h2>";
echo '<a href="/">Volver al inicio</a>';
?>