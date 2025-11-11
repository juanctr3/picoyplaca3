<?php
/**
 * Configuración de Ciudades - Pico y Placa Colombia
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
// CONFIGURACIÓN DE CIUDADES
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
    // BARRANQUILLA - Por día de semana
    // ==========================================
    'barranquilla' => [
        'nombre' => 'Barranquilla',
        'pais' => 'Colombia',
        'departamento' => 'Atlántico',
        'tipo' => 'dia-semana',
        'horario' => '6:00 a.m. - 9:00 p.m.',
        'horarioInicio' => 6,
        'horarioFin' => 21,
        'latitud' => 10.9639,
        'longitud' => -74.7964,
        'poblacion' => '1.2 millones',
        'descripcion' => 'Restricción por día de la semana',
        'restricciones' => [
            'Monday' => [1, 2],
            'Tuesday' => [3, 4],
            'Wednesday' => [5, 6],
            'Thursday' => [7, 8],
            'Friday' => [9, 0],
            'Saturday' => [],
            'Sunday' => []
        ],
    ],
    
    // ==========================================
    // CARTAGENA - Por día de semana
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
            'Monday' => [0, 1],
            'Tuesday' => [2, 3],
            'Wednesday' => [4, 5],
            'Thursday' => [6, 7],
            'Friday' => [8, 9],
            'Saturday' => [],
            'Sunday' => []
        ],
    ],
    
    // ==========================================
    // BUCARAMANGA - Por día de semana
    // ==========================================
    'bucaramanga' => [
        'nombre' => 'Bucaramanga',
        'pais' => 'Colombia',
        'departamento' => 'Santander',
        'tipo' => 'dia-semana',
        'horario' => '6:30 a.m. - 8:30 p.m.',
        'horarioInicio' => 6,
        'horarioFin' => 20,
        'latitud' => 7.1269,
        'longitud' => -73.1122,
        'poblacion' => '520 mil',
        'descripcion' => 'Restricción por día de la semana',
        'restricciones' => [
            'Monday' => [0, 1],
            'Tuesday' => [2, 3],
            'Wednesday' => [4, 5],
            'Thursday' => [6, 7],
            'Friday' => [8, 9],
            'Saturday' => [],
            'Sunday' => []
        ],
    ],
    
    // ==========================================
    // SANTA MARTA - Por día de semana
    // ==========================================
    'santa_marta' => [
        'nombre' => 'Santa Marta',
        'pais' => 'Colombia',
        'departamento' => 'Magdalena',
        'tipo' => 'dia-semana',
        'horario' => '6:00 a.m. - 7:00 p.m.',
        'horarioInicio' => 6,
        'horarioFin' => 19,
        'latitud' => 11.2456,
        'longitud' => -74.2301,
        'poblacion' => '480 mil',
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
    
];

// ==========================================
// RETORNAR CONFIGURACIONES
// ==========================================

return [
    'ciudades' => $ciudades,
    'festivos' => $festivosColombia
];

?>
