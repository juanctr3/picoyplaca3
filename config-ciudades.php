<?php
/**
 * Configuración de Ciudades - Pico y Placa Colombia
 * VERSIÓN CORREGIDA - Noviembre 2025
 * 
 * Define todas las ciudades, sus reglas de pico y placa,
 * horarios y festivos colombianos 2025+
 */

// ==========================================
// FESTIVOS COLOMBIANOS
// ==========================================

$festivosColombia = [
    // 2025
    '2025-01-01', // Año Nuevo
    '2025-01-06', // Reyes Magos
    '2025-03-24', // San José
    '2025-04-17', // Jueves Santo
    '2025-04-18', // Viernes Santo
    '2025-04-20', // Domingo de Pascua (no laboral pero incluido)
    '2025-05-01', // Día del Trabajo
    '2025-05-29', // Ascensión
    '2025-06-19', // Corpus Christi
    '2025-06-23', // Sagrado Corazón
    '2025-06-30', // San Pedro y San Pablo
    '2025-07-20', // Grito de Independencia
    '2025-08-07', // Batalla de Boyacá
    '2025-08-18', // Asunción de la Virgen
    '2025-10-13', // Día de la Raza
    '2025-11-03', // Todos los Santos
    '2025-11-17', // Independencia de Cartagena
    '2025-12-08', // Inmaculada Concepción
    '2025-12-25', // Navidad
    
    // 2026
    '2026-01-01',
    '2026-01-06',
    '2026-05-01',
    '2026-07-20',
    '2026-08-07',
    '2026-12-25',
];

// ==========================================
// CONFIGURACIÓN DE CIUDADES - ACTUALIZADO
// ==========================================

$ciudades = [
    
    // ==========================================
    // BOGOTÁ - Por día impar/par del mes
    // ==========================================
    'bogota' => [
        'nombre' => 'Bogotá',
        'pais' => 'Colombia',
        'departamento' => 'Cundinamarca',
        'tipo' => 'dia-impar-par',
        'horario' => '6:00 a.m. - 9:00 p.m.',
        'horarioInicio' => 6,
        'horarioFin' => 21,
        'latitud' => 4.7110,
        'longitud' => -74.0055,
        'poblacion' => '8 millones',
        'descripcion' => 'Restricción por día impar/par del mes',
        'restricciones' => [
            'impar' => [6, 7, 8, 9, 0],
            'par' => [1, 2, 3, 4, 5]
        ],
    ],
    
    // ==========================================
    // MEDELLÍN - Por día de semana
    // ==========================================
    'medellin' => [
        'nombre' => 'Medellín',
        'pais' => 'Colombia',
        'departamento' => 'Antioquia',
        'tipo' => 'dia-semana',
        'horario' => '5:00 a.m. - 8:00 p.m.',
        'horarioInicio' => 5,
        'horarioFin' => 20,
        'latitud' => 6.2518,
        'longitud' => -75.5636,
        'poblacion' => '2.5 millones',
        'descripcion' => 'Restricción por día de la semana',
        'restricciones' => [
            'Monday' => [6, 9],
            'Tuesday' => [5, 7],
            'Wednesday' => [1, 8],
            'Thursday' => [0, 2],
            'Friday' => [3, 4],
            'Saturday' => [],
            'Sunday' => []
        ],
    ],
    
    // ==========================================
    // CALI - Por día de semana
    // ==========================================
    'cali' => [
        'nombre' => 'Cali',
        'pais' => 'Colombia',
        'departamento' => 'Valle del Cauca',
        'tipo' => 'dia-semana',
        'horario' => '6:00 a.m. - 7:00 p.m.',
        'horarioInicio' => 6,
        'horarioFin' => 19,
        'latitud' => 3.4372,
        'longitud' => -76.5197,
        'poblacion' => '2.2 millones',
        'descripcion' => 'Restricción por día de la semana',
        'restricciones' => [
            'Monday' => [3, 4],
            'Tuesday' => [5, 6],
            'Wednesday' => [7, 8],
            'Thursday' => [9, 0],
            'Friday' => [1, 2],
            'Saturday' => [],
            'Sunday' => []
        ],
    ],
    
    // ==========================================
    // ARMENIA - Por día de semana
    // ==========================================
    'armenia' => [
        'nombre' => 'Armenia',
        'pais' => 'Colombia',
        'departamento' => 'Quindío',
        'tipo' => 'dia-semana',
        'horario' => '6:00 a.m. - 7:00 p.m.',
        'horarioInicio' => 6,
        'horarioFin' => 19,
        'latitud' => 4.5347,
        'longitud' => -75.7304,
        'poblacion' => '330 mil',
        'descripcion' => 'Restricción por día de la semana',
        'restricciones' => [
            'Monday' => [5, 6],
            'Tuesday' => [7, 8],
            'Wednesday' => [9, 0],
            'Thursday' => [1, 2],
            'Friday' => [3, 4],
            'Saturday' => [],
            'Sunday' => []
        ],
    ],
    
    // ==========================================
    // BARRANQUILLA - SIN RESTRICCIONES (Particulares)
    // ==========================================
    'barranquilla' => [
        'nombre' => 'Barranquilla',
        'pais' => 'Colombia',
        'departamento' => 'Atlántico',
        'tipo' => 'dia-semana',
        'horario' => 'Sin restricción para particulares',
        'horarioInicio' => 6,
        'horarioFin' => 21,
        'latitud' => 10.9639,
        'longitud' => -74.7964,
        'poblacion' => '1.2 millones',
        'descripcion' => 'No hay restricción para vehículos particulares',
        'restricciones' => [
            'Monday' => [],
            'Tuesday' => [],
            'Wednesday' => [],
            'Thursday' => [],
            'Friday' => [],
            'Saturday' => [],
            'Sunday' => []
        ],
    ],
    
    // ==========================================
    // CARTAGENA - Por día de semana (CORREGIDO)
    // ==========================================
    'cartagena' => [
        'nombre' => 'Cartagena',
        'pais' => 'Colombia',
        'departamento' => 'Bolívar',
        'tipo' => 'dia-semana',
        'horario' => '7:00 a.m. - 6:00 p.m.',
        'horarioInicio' => 7,
        'horarioFin' => 18,
        'latitud' => 10.3932,
        'longitud' => -75.4830,
        'poblacion' => '880 mil',
        'descripcion' => 'Restricción por día de la semana',
        'restricciones' => [
            'Monday' => [3, 4],
            'Tuesday' => [5, 6],
            'Wednesday' => [7, 8],
            'Thursday' => [9, 0],
            'Friday' => [1, 2],
            'Saturday' => [],
            'Sunday' => []
        ],
    ],
    
    // ==========================================
    // BUCARAMANGA - Por día de semana (CORREGIDO)
    // Horario: 6:00am - 8:00pm de lunes a viernes
    // Sábados: 9:00am - 1:00pm con restricciones especiales por fecha
    // ==========================================
    'bucaramanga' => [
        'nombre' => 'Bucaramanga',
        'pais' => 'Colombia',
        'departamento' => 'Santander',
        'tipo' => 'dia-semana',
        'horario' => 'L-V: 6:00am-8:00pm | Sábados: 9:00am-1:00pm (fechas especiales)',
        'horarioInicio' => 6,
        'horarioFin' => 20,
        'latitud' => 7.1269,
        'longitud' => -73.1122,
        'poblacion' => '520 mil',
        'descripcion' => 'Restricción por día de la semana. Sábados con restricciones especiales por fecha.',
        'restricciones' => [
            'Monday' => [3, 4],
            'Tuesday' => [5, 6],
            'Wednesday' => [7, 8],
            'Thursday' => [9, 0],
            'Friday' => [1, 2],
            'Saturday' => [],
            'Sunday' => []
        ],
        'restricciones_sabados_especiales' => [
            // Formato: 'YYYY-MM-DD' => [placas]
            '2025-10-04' => [9, 0],
            '2025-10-11' => [1, 2],
            '2025-10-18' => [3, 4],
            '2025-10-25' => [5, 6],
            '2025-11-01' => [7, 8],
            '2025-11-08' => [9, 0],
            '2025-11-15' => [1, 2],
            '2025-11-22' => [3, 4],
            '2025-11-29' => [5, 6],
            '2025-12-06' => [7, 8],
            '2025-12-13' => [9, 0],
            '2025-12-20' => [1, 2],
            '2025-12-27' => [3, 4],
        ]
    ],
    
    // ==========================================
    // SANTA MARTA - Por día de semana (CORREGIDO)
    // Horario: 7:00am-9:00am, 11:30am-2:00pm, 5:00pm-8:00pm
    // Simplificado a 7:00am - 8:00pm para el sistema actual
    // ==========================================
    'santa_marta' => [
        'nombre' => 'Santa Marta',
        'pais' => 'Colombia',
        'departamento' => 'Magdalena',
        'tipo' => 'dia-semana',
        'horario' => '7:00am-9:00am | 11:30am-2:00pm | 5:00pm-8:00pm',
        'horarioInicio' => 7,
        'horarioFin' => 20,
        'latitud' => 11.2456,
        'longitud' => -74.2301,
        'poblacion' => '480 mil',
        'descripcion' => 'Restricción en 3 franjas horarias por día de la semana',
        'restricciones' => [
            'Monday' => [1, 2],
            'Tuesday' => [3, 4],
            'Wednesday' => [5, 6],
            'Thursday' => [7, 8],
            'Friday' => [9, 0],
            'Saturday' => [],
            'Sunday' => []
        ],
        'franjas_horarias' => [
            'franja_1' => ['inicio' => 7, 'fin' => 9],
            'franja_2' => ['inicio' => 11, 'fin' => 14],
            'franja_3' => ['inicio' => 17, 'fin' => 20]
        ]
    ],
    
];

// ==========================================
// RETORNAR CONFIGURACIONES
// ==========================================

return [
    'ciudades' => $ciudades,
    'festivos' => $festivosColombia
];

?>