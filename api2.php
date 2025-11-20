<?php
/**
 * API de Pico y Placa - Endpoints JSON
 * Versión 2.1 - Con soporte para próximos 60 días personalizados
 * 
 * Endpoints disponibles:
 * GET /api.php?action=info&ciudad=bogota
 * GET /api.php?action=placa&ciudad=bogota&placa=5
 * GET /api.php?action=fecha&ciudad=bogota&fecha=2025-12-25
 * GET /api.php?action=ciudades
 * GET /api.php?action=activo&ciudad=bogota
 * GET /api.php?action=tiempo&ciudad=bogota
 * GET /api.php?action=comparar&ciudades=bogota,medellin,cali
 * GET /api.php?action=rango&ciudad=bogota&fecha=2025-11-16&dias=7
 * GET /api.php?action=rango-personalizado&ciudad=bogota&placa=5&fecha=2025-11-16
 * GET /api.php?action=status
 */

// Headers para API REST
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Cache-Control: no-cache, no-store, must-revalidate'); // FORZAR NO CACHE
header('X-API-Version: 2.1');

// Incluir clases y configuración
require_once 'clases/PicoYPlaca.php';
$config = require_once 'config-ciudades.php';

$ciudades = $config['ciudades'];
$festivos = $config['festivos'];

// Parámetros de entrada
$action = $_GET['action'] ?? 'info';
$ciudad = $_GET['ciudad'] ?? 'bogota';
$placa = $_GET['placa'] ?? null;
$fecha = $_GET['fecha'] ?? null;

try {
    switch ($action) {
        
        // ==========================================
        // ACTION: INFO - Información general hoy
        // ==========================================
        case 'info':
            if (!isset($ciudades[$ciudad])) {
                throw new Exception("Ciudad '{$ciudad}' no encontrada");
            }
            
            $fechaObj = $fecha ? new DateTime($fecha) : new DateTime();
            $pyp = new PicoYPlaca($ciudad, $fechaObj, $ciudades, $festivos);
            
            $respuesta = [
                'success' => true,
                'data' => $pyp->getInfo()
            ];
            break;
        
        // ==========================================
        // ACTION: PLACA - Consultar placa específica
        // ==========================================
        case 'placa':
            if ($placa === null) {
                throw new Exception("Parámetro 'placa' requerido (0-9)");
            }
            if (!preg_match('/^[0-9]$/', $placa)) {
                throw new Exception("Placa debe ser un dígito 0-9");
            }
            
            $fechaObj = $fecha ? new DateTime($fecha) : new DateTime();
            $pyp = new PicoYPlaca($ciudad, $fechaObj, $ciudades, $festivos);
            
            $respuesta = [
                'success' => true,
                'data' => $pyp->consultarPlaca($placa)
            ];
            break;
        
        // ==========================================
        // ACTION: FECHA - Consultar fecha específica
        // ==========================================
        case 'fecha':
            if (!$fecha) {
                throw new Exception("Parámetro 'fecha' requerido (YYYY-MM-DD)");
            }
            
            $fechaObj = new DateTime($fecha);
            $pyp = new PicoYPlaca($ciudad, $fechaObj, $ciudades, $festivos);
            
            $respuesta = [
                'success' => true,
                'data' => $pyp->getInfo()
            ];
            break;
        
        // ==========================================
        // ACTION: ACTIVO - Verificar si está activo
        // ==========================================
        case 'activo':
            $pyp = new PicoYPlaca($ciudad, null, $ciudades, $festivos);
            
            $respuesta = [
                'success' => true,
                'data' => [
                    'ciudad' => $pyp->getNombreCiudad(),
                    'esta_activo' => $pyp->estaActivo(),
                    'restricciones' => $pyp->getRestricciones(),
                    'horario' => $pyp->getHorario()
                ]
            ];
            break;
        
        // ==========================================
        // ACTION: TIEMPO - Tiempo hasta pico y placa
        // ==========================================
        case 'tiempo':
            $pyp = new PicoYPlaca($ciudad, null, $ciudades, $festivos);
            
            $tiempo = $pyp->getTiempoHastaPicoYPlaca();
            
            $respuesta = [
                'success' => true,
                'data' => [
                    'ciudad' => $pyp->getNombreCiudad(),
                    'horas' => $tiempo['horas'],
                    'minutos' => $tiempo['minutos'],
                    'segundos' => $tiempo['segundos'],
                    'total_segundos' => $tiempo['timestamp'],
                    'formateado' => sprintf('%02d:%02d:%02d', $tiempo['horas'], $tiempo['minutos'], $tiempo['segundos'])
                ]
            ];
            break;
        
        // ==========================================
        // ACTION: CIUDADES - Listar todas las ciudades
        // ==========================================
        case 'ciudades':
            $listaCiudades = array_map(function($codigo, $info) {
                return [
                    'codigo' => $codigo,
                    'nombre' => $info['nombre'],
                    'departamento' => $info['departamento'] ?? '',
                    'horario' => $info['horario'],
                    'tipo' => $info['tipo'],
                    'latitud' => $info['latitud'] ?? null,
                    'longitud' => $info['longitud'] ?? null,
                    'poblacion' => $info['poblacion'] ?? ''
                ];
            }, array_keys($ciudades), $ciudades);
            
            $respuesta = [
                'success' => true,
                'data' => [
                    'total' => count($listaCiudades),
                    'ciudades' => $listaCiudades
                ]
            ];
            break;
        
        // ==========================================
        // ACTION: COMPARAR - Comparar múltiples ciudades
        // ==========================================
        case 'comparar':
            $ciudadesComparar = explode(',', $_GET['ciudades'] ?? 'bogota,medellin,cali');
            $ciudadesComparar = array_map('trim', $ciudadesComparar);
            
            $comparacion = [];
            foreach ($ciudadesComparar as $cd) {
                if (isset($ciudades[$cd])) {
                    $pyp = new PicoYPlaca($cd, null, $ciudades, $festivos);
                    $comparacion[] = [
                        'ciudad' => $pyp->getNombreCiudad(),
                        'codigo' => $cd,
                        'restricciones' => $pyp->getRestricciones(),
                        'permitidas' => $pyp->getPermitidas(),
                        'esta_activo' => $pyp->estaActivo()
                    ];
                }
            }
            
            $respuesta = [
                'success' => true,
                'data' => [
                    'fecha' => date('Y-m-d'),
                    'total_ciudades' => count($comparacion),
                    'comparacion' => $comparacion
                ]
            ];
            break;
        
        // ==========================================
        // ACTION: RANGO - Consultar rango de fechas
        // ==========================================
        case 'rango':
            if (!$fecha) {
                throw new Exception("Parámetro 'fecha' requerido como fecha inicial (YYYY-MM-DD)");
            }
            
            $dias = (int)($_GET['dias'] ?? 7);
            $dias = min($dias, 90); // Máximo 90 días
            
            $resultado = [];
            $fechaInicio = new DateTime($fecha);
            
            for ($i = 0; $i < $dias; $i++) {
                $fechaActual = clone $fechaInicio;
                $fechaActual->modify("+$i days");
                
                $pyp = new PicoYPlaca($ciudad, $fechaActual, $ciudades, $festivos);
                
                $resultado[] = [
                    'fecha' => $fechaActual->format('Y-m-d'),
                    'dia' => $pyp->getDiaEnEspanol(),
                    'restricciones' => $pyp->getRestricciones(),
                    'es_fin_semana' => $pyp->esFinDeSemana(),
                    'es_festivo' => $pyp->esFestivo()
                ];
            }
            
            $respuesta = [
                'success' => true,
                'data' => [
                    'ciudad' => $pyp->getNombreCiudad(),
                    'fecha_inicio' => $fecha,
                    'dias_consultados' => $dias,
                    'resultados' => $resultado
                ]
            ];
            break;
        
        // ==========================================
        // ACTION: RANGO-PERSONALIZADO - Próximos 60 días para una placa específica
        // ==========================================
        case 'rango-personalizado':
            if (!$fecha) {
                throw new Exception("Parámetro 'fecha' requerido como fecha inicial (YYYY-MM-DD)");
            }
            if ($placa === null) {
                throw new Exception("Parámetro 'placa' requerido (0-9)");
            }
            
            // Validar placa
            if (!preg_match('/^[0-9]$/', $placa)) {
                throw new Exception("Placa debe ser un dígito 0-9");
            }
            
            // Validar ciudad
            if (!isset($ciudades[$ciudad])) {
                throw new Exception("Ciudad '{$ciudad}' no encontrada");
            }
            
            $dias = 60; // 60 días fijos
            $resultado = [];
            $fechaInicio = new DateTime($fecha);
            
            for ($i = 0; $i < $dias; $i++) {
                $fechaActual = clone $fechaInicio;
                $fechaActual->modify("+$i days");
                
                $pyp = new PicoYPlaca($ciudad, $fechaActual, $ciudades, $festivos);
                
                // Solo agregar si hay restricción para esa placa
                $restricciones = $pyp->getRestricciones();
                if (in_array((int)$placa, $restricciones)) {
                    // Formatear fecha en español: "19 Diciembre de 2025"
$diaNum = $fechaActual->format('d');
$mesNum = $fechaActual->format('m');
$ano = $fechaActual->format('Y');
$meses = [
    '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo',
    '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio',
    '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre',
    '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
];
$fechaFormateada = "{$diaNum} {$meses[$mesNum]} de {$ano}";

$resultado[] = [
    'fecha' => $fechaFormateada, // ← NUEVO FORMATO
    'dia' => $pyp->getDiaEnEspanol(),
    'es_fin_semana' => $pyp->esFinDeSemana(),
    'es_festivo' => $pyp->esFestivo()
];
                }
            }
            
            $respuesta = [
                'success' => true,
                'data' => [
                    'ciudad' => $pyp->getNombreCiudad(),
                    'placa' => $placa,
                    'fecha_inicio' => $fecha,
                    'dias_consultados' => $dias,
                    'total_dias_con_restriccion' => count($resultado),
                    'proximas_restricciones' => $resultado
                ]
            ];
            break;
        
        // ==========================================
        // ACTION: STATUS - Estado general del sistema
        // ==========================================
        case 'status':
            $status = [
                'servidor' => 'activo',
                'timestamp' => time(),
                'fecha_actual' => date('Y-m-d H:i:s'),
                'total_ciudades' => count($ciudades),
                'total_festivos' => count($festivos),
                'version' => '2.1',
                'endpoints_disponibles' => 10
            ];
            
            $respuesta = [
                'success' => true,
                'data' => $status
            ];
            break;
        
        // ==========================================
        // DEFAULT - Acción no encontrada
        // ==========================================
        default:
            http_response_code(400);
            $respuesta = [
                'success' => false,
                'error' => "Acción '{$action}' no válida. Usa: info, placa, fecha, activo, tiempo, ciudades, comparar, rango, rango-personalizado, status",
                'code' => 'INVALID_ACTION'
            ];
    }
    
} catch (Exception $e) {
    http_response_code(400);
    $respuesta = [
        'success' => false,
        'error' => $e->getMessage(),
        'code' => 'ERROR_PROCESAMIENTO'
    ];
}

// Salida JSON con formato bonito
echo json_encode($respuesta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

// Forzar sin caché
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() - 3600) . ' GMT');

// Opcional: Log de depuración
if (isset($_GET['debug']) && $_GET['debug'] === 'true') {
    error_log("API Request: {$action} - Ciudad: {$ciudad} - Placa: {$placa} - Fecha: {$fecha}");
}
?>