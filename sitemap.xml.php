<?php
header('Content-Type: application/xml; charset=utf-8');

$config = require_once 'config-ciudades.php';
$ciudades = $config['ciudades'];

// Fecha actual
$hoy = new DateTime();
$startDate = new DateTime('2025-01-01');
$endDate = new DateTime('2025-12-31');

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0">' . "\n";

// Home
echo '  <url>' . "\n";
echo '    <loc>https://picoyplacabogota.com.co/</loc>' . "\n";
echo '    <lastmod>' . $hoy->format('Y-m-d') . '</lastmod>' . "\n";
echo '    <changefreq>daily</changefreq>' . "\n";
echo '    <priority>1.0</priority>' . "\n";
echo '    <mobile:mobile/>' . "\n";
echo '  </url>' . "\n";

// URLs dinámicas por ciudad y fecha (últimos 90 días + próximos 30)
$interval = new DateInterval('P1D');
$period = new DatePeriod($startDate, $interval, $endDate);
$count = 0;

foreach ($period as $date) {
    if ($count >= 1000) break; // Limitar a 1000 URLs
    
    foreach ($ciudades as $codigo => $ciudad) {
        if ($count >= 1000) break;
        
        echo '  <url>' . "\n";
        echo '    <loc>https://picoyplacabogota.com.co/pico-y-placa/' . $date->format('Y-m-d') . '-' . $codigo . '</loc>' . "\n";
        
        // Última modificación
        if ($date <= $hoy) {
            echo '    <lastmod>' . $date->format('Y-m-d') . '</lastmod>' . "\n";
        }
        
        echo '    <changefreq>weekly</changefreq>' . "\n";
        echo '    <priority>0.8</priority>' . "\n";
        echo '    <mobile:mobile/>' . "\n";
        echo '  </url>' . "\n";
        
        $count++;
    }
}

echo '</urlset>';
?>