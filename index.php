<?php
/**
 * PICO Y PLACA - Sistema de Consulta de Restricciones Vehiculares
 * Versión 2.0 - Refactorizada con clase modular
 */

require_once 'clases/PicoYPlaca.php';
$config = require_once 'config-ciudades.php';
$ciudades = $config['ciudades'];
$festivos = $config['festivos'];

$isDatePage = false;
$dateData = [];

// ==========================================
// PROCESAR DATOS DE HOY
// ==========================================

$ahora = new DateTime();

$datos_hoy = [];
foreach ($ciudades as $codigo => $info) {
    $pyp = new PicoYPlaca($codigo, $ahora, $ciudades, $festivos);
    $datos_hoy[$codigo] = [
        'restricciones' => $pyp->getRestricciones(),
        'permitidas' => $pyp->getPermitidas(),
        'horario' => $pyp->getHorario(),
        'nombre' => $info['nombre'],
        'horarioInicio' => $info['horarioInicio'],
        'horarioFin' => $info['horarioFin']
    ];
}

$datos_hoy_json = json_encode($datos_hoy);

// ==========================================
// PROCESAR URL DE FECHA ESPECÍFICA
// ==========================================

if (preg_match('/pico-y-placa\/(\d{4})-(\d{2})-(\d{2})-(\w+)/', $_SERVER['REQUEST_URI'], $matches)) {
    $year = (int)$matches[1];
    $month = (int)$matches[2];
    $day = (int)$matches[3];
    $ciudad = $matches[4];
    
    if (isset($ciudades[$ciudad])) {
        try {
            $fecha = new DateTime("$year-$month-$day");
            $pyp = new PicoYPlaca($ciudad, $fecha, $ciudades, $festivos);
            
            $monthNames = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
            
            $dateData = [
                'dayNameEs' => $pyp->getDiaEnEspanol(),
                'dayNum' => (int)$fecha->format('d'),
                'monthName' => $monthNames[$month - 1],
                'year' => $year,
                'month' => $month,
                'day' => $day,
                'cityName' => $pyp->getNombreCiudad(),
                'city' => $ciudad,
                'restrictions' => $pyp->getRestricciones(),
                'allowed' => $pyp->getPermitidas(),
                'isWeekend' => $pyp->esFinDeSemana(),
                'isHoliday' => $pyp->esFestivo(),
                'horario' => $pyp->getHorario(),
                'estado' => $pyp->getEstado()
            ];
            
            $isDatePage = true;
        } catch (Exception $e) {
            http_response_code(404);
        }
    } else {
        http_response_code(404);
    }
}

// Generar meta tags
if ($isDatePage) {
    $title = "Pico y placa " . strtolower($dateData['dayNameEs']) . " " . $dateData['dayNum'] . " de " . $dateData['monthName'] . " en " . $dateData['cityName'];
    $description = "Pico y placa en " . $dateData['cityName'] . " el " . $dateData['dayNameEs'] . " " . $dateData['dayNum'] . " de " . $dateData['monthName'] . ". Placas restringidas: " . (count($dateData['restrictions']) > 0 ? implode(', ', $dateData['restrictions']) : 'Sin restricción');
    $keywords = "pico y placa " . $dateData['cityName'] . ", pico y placa " . strtolower($dateData['dayNameEs']);
} else {
    $title = "Pico y Placa HOY en Colombia 🚗 | Consulta en Tiempo Real";
    $description = "Consulta GRATIS el pico y placa en Bogotá, Medellín, Cali, Barranquilla, Cartagena, Bucaramanga y Santa Marta. Información en tiempo real.";
    $keywords = "pico y placa hoy, pico y placa bogota, pico y placa medellin, restriccion vehicular";
}

$ciudadesJSON = json_encode(array_map(function($codigo, $info) {
    return [
        'codigo' => $codigo,
        'nombre' => $info['nombre'],
        'horario' => $info['horario'],
        'horarioInicio' => $info['horarioInicio'],
        'horarioFin' => $info['horarioFin']
    ];
}, array_keys($ciudades), $ciudades));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Pico y PL">
    <meta name="language" content="es-CO">
    <meta name="author" content="Pico y Placa Colombia">
    <meta name="theme-color" content="#667eea">
    <meta name="robots" content="index, follow">
    
    <link rel="manifest" href="/manifest.json">
    <link rel="sitemap" type="application/xml" href="/sitemap.xml.php">
    
    <title><?php echo htmlspecialchars($title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($description); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($keywords); ?>">
    
    <?php if ($isDatePage): ?>
    <link rel="canonical" href="https://picoyplacabogota.com.co/pico-y-placa/<?php echo $dateData['year']; ?>-<?php echo str_pad($dateData['month'], 2, '0', STR_PAD_LEFT); ?>-<?php echo str_pad($dateData['day'], 2, '0', STR_PAD_LEFT); ?>-<?php echo $dateData['city']; ?>">
    <?php else: ?>
    <link rel="canonical" href="https://picoyplacabogota.com.co/">
    <?php endif; ?>
    
    <link rel="icon" type="image/png" sizes="192x192" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 192 192'><rect fill='%23667eea' width='192' height='192'/><text x='50%' y='50%' font-size='120' font-weight='bold' text-anchor='middle' dy='.3em' fill='white' font-family='Arial'>🚗</text></svg>">
    
    <meta property="og:title" content="<?php echo htmlspecialchars($title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($description); ?>">
    <meta property="og:type" content="website">
    <?php if ($isDatePage): ?>
    <meta property="og:url" content="https://picoyplacabogota.com.co/pico-y-placa/<?php echo $dateData['year']; ?>-<?php echo str_pad($dateData['month'], 2, '0', STR_PAD_LEFT); ?>-<?php echo str_pad($dateData['day'], 2, '0', STR_PAD_LEFT); ?>-<?php echo $dateData['city']; ?>">
    <?php else: ?>
    <meta property="og:url" content="https://picoyplacabogota.com.co/">
    <?php endif; ?>
    
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebApplication",
        "name": "Pico y Placa Colombia",
        "description": "Consulta en tiempo real el pico y placa",
        "url": "https://picoyplacabogota.com.co",
        "applicationCategory": "UtilityApplication",
        "offers": {"@type": "Offer", "price": "0"}
    }
    </script>
    
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-2L2EV10ZWW"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-2L2EV10ZWW');
</script>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 10px; color: #333; transition: background 0.3s; }
        body.pico-activo { background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%); }
        body.sin-pico { background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%); }
        .container { max-width: 1200px; margin: 0 auto; }
        
        header { text-align: center; color: white; margin-bottom: 20px; padding: 15px 10px; }
        h1 { font-size: clamp(1.5rem, 8vw, 3rem); margin-bottom: 8px; font-weight: 800; }
        .subtitle { font-size: clamp(0.85rem, 3vw, 1.1rem); opacity: 0.95; }
        
        .install-btn { position: absolute; top: 10px; right: 10px; background: white; color: #667eea; border: none; padding: 8px 16px; border-radius: 20px; font-weight: 600; cursor: pointer; font-size: 0.85rem; display: none; }
        .install-btn.show { display: block; }
        
        .date-search-section, .search-box, .restrictions-today { background: white; padding: 20px; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); margin-bottom: 20px; }
        @media (min-width: 600px) { .date-search-section, .search-box, .restrictions-today { padding: 30px; } }
        
        .today-info { background: white; padding: 15px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: 12px; }
        .info-card { padding: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 10px; text-align: center; }
        .info-card h3 { font-size: 0.75rem; text-transform: uppercase; margin-bottom: 8px; }
        .info-card p { font-size: clamp(1rem, 4vw, 1.5rem); font-weight: 800; }
        
        .main-content { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        @media (max-width: 900px) { .main-content { grid-template-columns: 1fr; } }
        
        .cities-grid { display: flex; gap: 8px; overflow-x: auto; flex-wrap: nowrap; padding: 10px 0; }
        .city-btn { padding: 10px 15px; border: 2px solid #e0e0e0; background: white; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 0.9rem; min-height: 44px; white-space: nowrap; }
        .city-btn.active { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-color: #667eea; }
        
        input[type="text"], input[type="date"], select { padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 1rem; min-height: 44px; }
        .btn-search { padding: 12px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; min-height: 44px; }
        
        .result-box { margin-top: 20px; padding: 20px; border-radius: 12px; display: none; }
        .result-box.show { display: block; }
        .result-success { background: #d4edda; border: 2px solid #28a745; color: #155724; }
        .result-restricted { background: #f8d7da; border: 2px solid #dc3545; color: #721c24; }
        
        .plates-list { display: flex; flex-wrap: wrap; gap: 8px; }
        .plate-badge { background: #84fab0; color: #333; padding: 8px 14px; border-radius: 20px; font-weight: 700; }
        .plate-badge.restricted { background: #f093fb; color: white; }
        
        .info-section { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 15px; margin-bottom: 20px; }
        
        /* COUNTDOWN MEJORADO */
        #countdownContainer { 
    background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(245,245,245,0.95) 100%);
    border: 3px solid #667eea;
    box-shadow: 0 10px 40px rgba(102, 126, 234, 0.2);
    border-radius: 20px;
    padding: 30px 20px;
    margin: 20px 0;
    display: block !important;
}
        
        #countdownTitle {
            color: #667eea;
            font-size: 1.3rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .countdown-display {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin: 0;
        }
        
        .countdown-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 20px 25px;
            min-width: 90px;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            animation: countdown-bounce 0.6s ease-in-out infinite;
        }
        
        .countdown-item:nth-child(1) { animation-delay: 0s; }
        .countdown-item:nth-child(3) { animation-delay: 0.1s; }
        .countdown-item:nth-child(5) { animation-delay: 0.2s; }
        
        .countdown-item div:first-child {
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 8px;
        }
        
        .countdown-item small {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            opacity: 0.9;
        }
        
        .countdown-separator {
            font-size: 2rem;
            font-weight: 800;
            color: #667eea;
            margin: 0 5px;
        }
        
        @keyframes countdown-bounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.08); }
        }
        
        /* ESTADOS DEL COUNTDOWN */
        body.pico-activo #countdownContainer {
            border-color: #ff6b6b;
            box-shadow: 0 10px 40px rgba(255, 107, 107, 0.2);
        }
        
        body.pico-activo #countdownTitle {
            color: #ff6b6b;
        }
        
        body.pico-activo .countdown-item {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);
        }
        
        body.pico-activo .countdown-separator {
            color: #ff6b6b;
        }
        
        body.sin-pico #countdownContainer {
            border-color: #27ae60;
            box-shadow: 0 10px 40px rgba(39, 174, 96, 0.2);
        }
        
        body.sin-pico #countdownTitle {
            color: #27ae60;
        }
        
        body.sin-pico .countdown-item {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            box-shadow: 0 8px 25px rgba(39, 174, 96, 0.3);
        }
        
        body.sin-pico .countdown-separator {
            color: #27ae60;
        }
        
        .no-restriction { color: #28a745; font-weight: 700; }
        .has-restriction { color: #dc3545; font-weight: 700; }
        
        footer { text-align: center; color: white; padding: 15px; opacity: 0.9; }
        
        @media (max-width: 480px) {
            .date-search-section, .search-box, .restrictions-today { padding: 15px; }
            input, select { font-size: 16px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <button class="install-btn" id="installBtn">⬇️ Instalar</button>
            <h1 id="pageTitle">
                <?php if ($isDatePage): ?>
                    🚗 Pico y Placa en <?php echo htmlspecialchars($dateData['cityName']); ?>
                <?php else: ?>
                    🚗 Pico y Placa hoy en Bogotá
                <?php endif; ?>
            </h1>
            <p class="subtitle">Consulta restricciones vehiculares en tiempo real</p>
        </header>
        
        <?php if (!$isDatePage): ?>
        
        <div class="date-search-section">
            <h2 style="margin-bottom: 12px;">📅 Buscar por Fecha</h2>
            <form onsubmit="searchByDate(event)" style="display: flex; gap: 10px; flex-wrap: wrap;">
                <input type="date" id="dateInput" required>
                <select id="citySelect" style="flex: 1; min-width: 100px;">
                    <?php foreach ($ciudades as $codigo => $info): ?>
                    <option value="<?php echo $codigo; ?>"><?php echo $info['nombre']; ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn-search">Buscar</button>
            </form>
        </div>
        
        <div class="today-info">
            <div class="info-card"><h3>📅 Hoy</h3><p id="today-date">--</p></div>
            <div class="info-card"><h3>🚫 Restricción</h3><p id="today-status">--</p></div>
            <div class="info-card"><h3>🕐 Horario</h3><p id="city-schedule">--</p></div>
        </div>
        
        <div id="countdownContainer">
            <h3 id="countdownTitle">⏰ Pico y Placa Activo</h3>
            <div class="countdown-display">
                <div class="countdown-item">
                    <div id="countdownHours">00</div>
                    <small>Horas</small>
                </div>
                <span class="countdown-separator">:</span>
                <div class="countdown-item">
                    <div id="countdownMinutes">00</div>
                    <small>Minutos</small>
                </div>
                <span class="countdown-separator">:</span>
                <div class="countdown-item">
                    <div id="countdownSeconds">00</div>
                    <small>Segundos</small>
                </div>
            </div>
        </div>
        
        <div class="main-content">
            <div class="search-box">
                <div class="slider-container">
                    <div class="slider-header">
                        <h2>Tu ciudad</h2>
                    </div>
                    <div class="slider-wrapper">
                        <button type="button" class="slider-btn" id="citiesPrev" onclick="scrollCities('left')" title="Anterior">‹</button>
                        <div class="slider-content" id="citiesSlider">
                            <?php foreach ($ciudades as $codigo => $info): ?>
                            <button type="button" class="city-btn" id="btn-<?php echo $codigo; ?>" onclick="selectCity('<?php echo $codigo; ?>')">
                                <?php echo $info['nombre']; ?>
                            </button>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="slider-btn" id="citiesNext" onclick="scrollCities('right')" title="Siguiente">›</button>
                    </div>
                </div>
                
                <label style="display: block; margin: 15px 0 12px 0; font-weight: 700;">Última placa (0-9)</label>
                <div style="display: flex; gap: 10px;">
                    <input type="text" id="plate-input" placeholder="5" maxlength="1" inputmode="numeric">
                    <button type="button" class="btn-search" onclick="searchPlate()">Consultar</button>
                </div>
                
                <div id="result-box" class="result-box"></div>
            </div>
            
            <div class="restrictions-today">
                <h2 style="margin-bottom: 12px;">Restricciones HOY</h2>
                <h3 id="city-today" style="color: #667eea; margin-bottom: 10px;">Bogotá</h3>
                <p style="margin-bottom: 10px; font-weight: 600;" id="restriction-label">🚫 Con restricción:</p>
                <div class="plates-list" id="plates-restricted-today"></div>
                <p style="margin: 15px 0 10px 0; font-weight: 600;">✅ Habilitadas:</p>
                <div class="plates-list" id="plates-allowed-today"></div>
            </div>
        </div>
        
        <div class="info-section">
            <h2>ℹ️ Información</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                <div><strong>🚗 Exentos:</strong><p style="margin: 5px 0 0 0; opacity: 0.9;">Eléctricos, híbridos, gas natural</p></div>
                <div><strong>📅 Fin de Semana:</strong><p style="margin: 5px 0 0 0; opacity: 0.9;">Sin restricción</p></div>
                <div><strong>🎉 Festivos:</strong><p style="margin: 5px 0 0 0; opacity: 0.9;">Sin restricción</p></div>
                <div><strong>⚠️ Multas:</strong><p style="margin: 5px 0 0 0; opacity: 0.9;">$600K - $900K</p></div>
            </div>
        </div>
        
        <?php else: ?>
        
        <button class="back-btn" onclick="backToHome()" style="display: inline-block; margin-bottom: 20px; padding: 10px 20px; background: white; color: #667eea; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; min-height: 44px;">← Volver</button>
        
        <div style="background: white; padding: 20px; border-radius: 15px; margin-bottom: 20px;">
            <h2 style="margin-bottom: 15px;">📅 <?php echo htmlspecialchars($dateData['dayNum'] . ' de ' . $dateData['monthName'] . ' de ' . $dateData['year']); ?></h2>
            <h3 style="color: #667eea; margin-bottom: 15px;">🚗 Pico y Placa en <?php echo htmlspecialchars($dateData['cityName']); ?></h3>
            
            <div style="background: #f0f0f0; padding: 12px; border-radius: 8px; margin-bottom: 15px;">
                <p><strong>📅 Día:</strong> <?php echo ucfirst($dateData['dayNameEs']); ?></p>
                <p><strong>🕐 Horario:</strong> <?php echo htmlspecialchars($dateData['horario']); ?></p>
                <p><strong>📊 Estado:</strong> 
                    <?php 
                    if ($dateData['isWeekend']) {
                        echo '<span class="no-restriction">✅ Sin restricción (Fin de semana)</span>';
                    } elseif ($dateData['isHoliday']) {
                        echo '<span class="no-restriction">✅ Sin restricción (Día festivo)</span>';
                    } else {
                        echo count($dateData['restrictions']) > 0 ? '<span class="has-restriction">⚠️ Hay restricción</span>' : '<span class="no-restriction">✅ Hoy no hay restricción</span>';
                    }
                    ?>
                </p>
            </div>
            
            <p style="margin-bottom: 10px; font-weight: 600;">🚫 Placas con restricción:</p>
            <div class="plates-list">
                <?php
                if ($dateData['isWeekend']) {
                    echo '<p class="no-restriction">✅ Fin de semana</p>';
                } elseif ($dateData['isHoliday']) {
                    echo '<p class="no-restriction">✅ Día festivo</p>';
                } elseif (count($dateData['restrictions']) > 0) {
                    foreach ($dateData['restrictions'] as $p) echo '<span class="plate-badge restricted">' . $p . '</span>';
                } else {
                    echo '<p class="no-restriction">✅ Hoy no hay restricción</p>';
                }
                ?>
            </div>
            
            <p style="margin: 15px 0 10px 0; font-weight: 600;">✅ Placas habilitadas:</p>
            <div class="plates-list">
                <?php
                if ($dateData['isWeekend'] || $dateData['isHoliday']) {
                    echo '<p class="no-restriction">✅ Todas (0-9)</p>';
                } elseif (count($dateData['restrictions']) > 0) {
                    foreach ($dateData['allowed'] as $p) echo '<span class="plate-badge">' . $p . '</span>';
                } else {
                    echo '<p class="no-restriction">✅ Todas (0-9)</p>';
                }
                ?>
            </div>
        </div>
        
        <?php endif; ?>
        
        <footer>
            <p><strong>Pico y PL</strong> - Colombia 2025 | Versión 2.0</p>
        </footer>
    </div>
    
    <script>
        let selectedCity = 'bogota';
        const datosHoy = JSON.parse('<?php echo $datos_hoy_json; ?>');
        let countdownInterval;
        
        function updateTodayInfo() {
    const data = datosHoy[selectedCity];
    
    if (!data) {
        console.error('❌ Ciudad no encontrada:', selectedCity);
        return;
    }
    
    console.log('\n📍 Actualizando:', selectedCity);
    
    const today = new Date();
    const options = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};
    const dateStr = today.toLocaleDateString('es-CO', options);
    
    document.getElementById('today-date').textContent = dateStr.charAt(0).toUpperCase() + dateStr.slice(1);
    document.getElementById('city-today').textContent = data.nombre;
    document.getElementById('city-schedule').textContent = data.horario;
    document.getElementById('pageTitle').textContent = '🚗 Pico y Placa hoy en ' + data.nombre;
    
    const diaSemana = today.getDay();
    const esFinDeSemana = diaSemana === 0 || diaSemana === 6;
    
    const restricciones = data.restricciones;
    const permitidas = data.permitidas;
    
    // ✅ CONVERTIR A NÚMEROS ANTES DE PASAR
    const horarioInicio = parseInt(data.horarioInicio, 10);
    const horarioFin = parseInt(data.horarioFin, 10);
    
    console.log('   Inicio:', horarioInicio, 'Fin:', horarioFin);
    
    updateCountdown(horarioInicio, horarioFin);
    
    if (esFinDeSemana) {
        document.getElementById('today-status').textContent = 'Libre - Fin de Semana';
        document.getElementById('restriction-label').innerHTML = '✅ Sin restricción';
        document.getElementById('plates-restricted-today').innerHTML = '<p class="no-restriction">✅ Fin de Semana - Sin restricción</p>';
        document.getElementById('plates-allowed-today').innerHTML = '<p class="no-restriction">✅ Todos los vehículos (0-9)</p>';
        document.body.className = 'sin-pico';
    } else {
        if (restricciones && restricciones.length > 0) {
            document.getElementById('today-status').textContent = restricciones.join(', ');
            document.getElementById('restriction-label').innerHTML = '🚫 Con restricción:';
            document.getElementById('plates-restricted-today').innerHTML = restricciones.map(p => '<span class="plate-badge restricted">' + p + '</span>').join('');
            document.getElementById('plates-allowed-today').innerHTML = permitidas.map(p => '<span class="plate-badge">' + p + '</span>').join('');
        } else {
            document.getElementById('today-status').textContent = 'Libre';
            document.getElementById('restriction-label').innerHTML = '✅ Hoy no hay restricción';
            document.getElementById('plates-restricted-today').innerHTML = '<p class="no-restriction">✅ Hoy no hay restricción</p>';
            document.getElementById('plates-allowed-today').innerHTML = permitidas.map(p => '<span class="plate-badge">' + p + '</span>').join('');
            document.body.className = 'sin-pico';
        }
    }
}
        
        function updateCountdown(inicio, fin) {
    clearInterval(countdownInterval);
    
    // ✅ CONVERTIR A NÚMEROS EXPLÍCITAMENTE
    inicio = parseInt(inicio, 10);
    fin = parseInt(fin, 10);
    
    console.log('🕐 Countdown iniciado');
    console.log('   Inicio:', inicio, 'tipo:', typeof inicio);
    console.log('   Fin:', fin, 'tipo:', typeof fin);
    
    function calcularTiempo() {
        const ahora = new Date();
        const horaActual = ahora.getHours();
        
        console.log('⏰ Hora actual:', horaActual);
        console.log('   ¿' + horaActual + ' >= ' + inicio + '?', horaActual >= inicio);
        console.log('   ¿' + horaActual + ' < ' + fin + '?', horaActual < fin);
        
        let proximoTiempo = 0;
        let titulo = '';
        let mensaje = '';
        
        // ESTÁ ACTIVO AHORA
        if (horaActual >= inicio && horaActual < fin) {
            console.log('✅ PICO ACTIVO AHORA');
            titulo = '🚨 PICO Y PLACA ACTIVO';
            mensaje = '⏱️ Falta para terminar:';
            
            const ahora_ms = ahora.getTime();
            const fin_hoy = new Date(ahora);
            fin_hoy.setHours(fin, 0, 0, 0);
            
            proximoTiempo = Math.max(0, (fin_hoy.getTime() - ahora_ms) / 1000);
            document.body.className = 'pico-activo';
        } 
        // INICIA HOY
        else if (horaActual < inicio) {
            console.log('✅ INICIA HOY A LAS', inicio + ':00');
            titulo = '✅ PICO Y PLACA HOY';
            mensaje = '⏳ Falta para iniciar:';
            
            const ahora_ms = ahora.getTime();
            const inicio_hoy = new Date(ahora);
            inicio_hoy.setHours(inicio, 0, 0, 0);
            
            proximoTiempo = (inicio_hoy.getTime() - ahora_ms) / 1000;
            document.body.className = 'sin-pico';
        } 
        // INICIA MAÑANA
        else {
            console.log('✅ INICIA MAÑANA A LAS', inicio + ':00');
            titulo = '✅ PRÓXIMO PICO Y PLACA';
            mensaje = '📅 Falta para iniciar mañana:';
            
            const ahora_ms = ahora.getTime();
            const inicio_mañana = new Date(ahora);
            inicio_mañana.setDate(inicio_mañana.getDate() + 1);
            inicio_mañana.setHours(inicio, 0, 0, 0);
            
            proximoTiempo = (inicio_mañana.getTime() - ahora_ms) / 1000;
            document.body.className = 'sin-pico';
        }
        
        const horas = Math.floor(proximoTiempo / 3600);
        const minutos = Math.floor((proximoTiempo % 3600) / 60);
        const segundos = Math.floor(proximoTiempo % 60);
        
        console.log('⏱️ Tiempo:', horas + 'h ' + minutos + 'm ' + segundos + 's');
        
        const titleEl = document.getElementById('countdownTitle');
        if (titleEl) {
            titleEl.innerHTML = titulo + '<br><small style="font-size: 0.8rem; font-weight: 500;">' + mensaje + '</small>';
        }
        
        document.getElementById('countdownHours').textContent = String(horas).padStart(2, '0');
        document.getElementById('countdownMinutes').textContent = String(minutos).padStart(2, '0');
        document.getElementById('countdownSeconds').textContent = String(segundos).padStart(2, '0');
        
        const container = document.getElementById('countdownContainer');
        if (container && !container.classList.contains('show')) {
            container.classList.add('show');
        }
    }
    
    calcularTiempo();
    countdownInterval = setInterval(calcularTiempo, 1000);
}
        
        function selectCity(ciudad) {
    console.log('\n🏙️ Cambiando a ciudad:', ciudad);
    
    selectedCity = ciudad;
    
    document.querySelectorAll('.city-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('btn-' + ciudad).classList.add('active');
    
    updateTodayInfo();
    
    document.getElementById('result-box').innerHTML = '';
    document.getElementById('plate-input').value = '';
}
        
        function searchPlate() {
            const plate = document.getElementById('plate-input').value;
            if (!plate || isNaN(plate)) return alert('Solo 0-9');
            
            const data = datosHoy[selectedCity];
            const tiene_restriccion = data.restricciones.includes(parseInt(plate));
            
            const box = document.getElementById('result-box');
            if (tiene_restriccion) {
                box.className = 'result-box show result-restricted';
                box.innerHTML = '<h3>⚠️ ¡RESTRICCIÓN!</h3><p>Tu placa ' + plate + ' NO puede circular hoy en ' + data.nombre + '</p>';
            } else {
                box.className = 'result-box show result-success';
                box.innerHTML = '<h3>✅ Puedes circular</h3><p>Tu placa ' + plate + ' puede circular hoy en ' + data.nombre + '</p>';
            }
        }
        
        function searchByDate(e) {
            e.preventDefault();
            const date = document.getElementById('dateInput').value;
            const city = document.getElementById('citySelect').value;
            if (date) {
                const [year, month, day] = date.split('-');
                window.location.href = `/pico-y-placa/${year}-${month}-${day}-${city}`;
            }
        }
        
        function backToHome() {
            window.location.href = '/';
        }
        
        // ==========================================
        // SLIDERS - FUNCIONES
        // ==========================================
        
        function initSliders() {
            const citiesSlider = document.getElementById('citiesSlider');
            const citiesPrevBtn = document.getElementById('citiesPrev');
            const citiesNextBtn = document.getElementById('citiesNext');
            
            if (citiesSlider && citiesPrevBtn && citiesNextBtn) {
                citiesPrevBtn.onclick = () => {
                    citiesSlider.scrollBy({ left: -150, behavior: 'smooth' });
                };
                citiesNextBtn.onclick = () => {
                    citiesSlider.scrollBy({ left: 150, behavior: 'smooth' });
                };
                updateScrollButtons();
            }
        }
        
        function scrollCities(direction) {
            const slider = document.getElementById('citiesSlider');
            if (!slider) return;
            
            if (direction === 'left') {
                slider.scrollBy({ left: -150, behavior: 'smooth' });
            } else {
                slider.scrollBy({ left: 150, behavior: 'smooth' });
            }
        }
        
        function updateScrollButtons() {
            const citiesSlider = document.getElementById('citiesSlider');
            const citiesPrevBtn = document.getElementById('citiesPrev');
            const citiesNextBtn = document.getElementById('citiesNext');
            
            if (!citiesSlider || !citiesPrevBtn || !citiesNextBtn) return;
            
            citiesPrevBtn.disabled = citiesSlider.scrollLeft === 0;
            citiesNextBtn.disabled = citiesSlider.scrollLeft >= (citiesSlider.scrollWidth - citiesSlider.clientWidth);
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            // CORRECCIÓN: Llamamos directamente a selectCity('bogota') para inicializar
            // toda la interfaz y el contador de forma robusta.
            selectCity('bogota');
            initSliders();
            
            const plateInput = document.getElementById('plate-input');
            if (plateInput) {
                plateInput.addEventListener('input', function() { this.value = this.value.replace(/[^0-9]/g, ''); });
                plateInput.addEventListener('keypress', function(e) { if (e.key === 'Enter') searchPlate(); });
            }
        });
        
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js').catch(e => console.log('SW:', e));
        }
    </script>
</body>
</html>