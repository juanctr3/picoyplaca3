<?php
/**
 * Clase PicoYPlaca
 * Calcula las restricciones de pico y placa según ciudad y fecha
 */

class PicoYPlaca {
    private $ciudad;
    private $fecha;
    private $ciudades;
    private $festivos;
    private $tipo;
    private $restricciones;
    
    public function __construct($ciudad, DateTime $fecha, $ciudades, $festivos) {
        $this->ciudad = $ciudad;
        $this->fecha = $fecha;
        $this->ciudades = $ciudades;
        $this->festivos = $festivos;
        $this->tipo = $ciudades[$ciudad]['tipo'] ?? 'dia-semana';
        $this->restricciones = $ciudades[$ciudad]['restricciones'] ?? [];
    }
    
    /**
     * Obtiene las placas con restricción para la fecha
     */
    public function getRestricciones() {
        if ($this->esFinDeSemana() || $this->esFestivo()) {
            return [];
        }
        
        if ($this->tipo === 'dia-impar-par') {
            return $this->getRestriccionesPorImparPar();
        } else {
            return $this->getRestriccionesPorDiaSemana();
        }
    }
    
    /**
     * Obtiene las placas permitidas
     */
    public function getPermitidas() {
        $restricciones = $this->getRestricciones();
        $todas = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        return array_diff($todas, $restricciones);
    }
    
    /**
     * Calcula restricciones por día impar/par
     */
    private function getRestriccionesPorImparPar() {
        $dia = (int)$this->fecha->format('d');
        $esImpar = ($dia % 2) === 1;
        
        if ($esImpar) {
            return $this->restricciones['impar'] ?? [];
        } else {
            return $this->restricciones['par'] ?? [];
        }
    }
    
    /**
     * Calcula restricciones por día de semana
     */
    private function getRestriccionesPorDiaSemana() {
        $diaSemana = $this->fecha->format('l'); // Monday, Tuesday, etc.
        return $this->restricciones[$diaSemana] ?? [];
    }
    
    /**
     * Verifica si es fin de semana
     */
    public function esFinDeSemana() {
        $dia = $this->fecha->format('w');
        return in_array($dia, [0, 6]); // 0 = Domingo, 6 = Sábado
    }
    
    /**
     * Verifica si es festivo
     */
    public function esFestivo() {
        $fechaStr = $this->fecha->format('Y-m-d');
        return in_array($fechaStr, $this->festivos);
    }
    
    /**
     * Obtiene el nombre del día en español
     */
    public function getDiaEnEspanol() {
        $dias = [
            'Monday' => 'lunes',
            'Tuesday' => 'martes',
            'Wednesday' => 'miércoles',
            'Thursday' => 'jueves',
            'Friday' => 'viernes',
            'Saturday' => 'sábado',
            'Sunday' => 'domingo'
        ];
        $diaIngles = $this->fecha->format('l');
        return $dias[$diaIngles] ?? 'desconocido';
    }
    
    /**
     * Obtiene el nombre de la ciudad
     */
    public function getNombreCiudad() {
        return $this->ciudades[$this->ciudad]['nombre'] ?? 'Desconocida';
    }
    
    /**
     * Obtiene el horario de pico y placa
     */
    public function getHorario() {
        return $this->ciudades[$this->ciudad]['horario'] ?? '6:00 a.m. - 9:00 p.m.';
    }
    
    /**
     * Obtiene el estado (activo/inactivo)
     */
    public function getEstado() {
        if ($this->esFinDeSemana()) {
            return 'sin_restriccion_fin_semana';
        }
        if ($this->esFestivo()) {
            return 'sin_restriccion_festivo';
        }
        if (count($this->getRestricciones()) > 0) {
            return 'con_restriccion';
        }
        return 'sin_restriccion';
    }
    
    /**
     * Calcula cuánto tiempo falta para activar/desactivar pico y placa
     */
    public function getTiempoHastaCambio() {
        $ahora = new DateTime();
        $horarioInicio = $this->ciudades[$this->ciudad]['horarioInicio'] ?? 6;
        $horarioFin = $this->ciudades[$this->ciudad]['horarioFin'] ?? 21;
        
        $horaActual = (int)$ahora->format('H');
        
        if ($horaActual >= $horarioInicio && $horaActual < $horarioFin) {
            $proximoTiempo = new DateTime();
            $proximoTiempo->setTime($horarioFin, 0, 0);
            return $proximoTiempo->diff($ahora);
        } else {
            $proximoTiempo = new DateTime();
            $proximoTiempo->modify('+1 day');
            $proximoTiempo->setTime($horarioInicio, 0, 0);
            return $proximoTiempo->diff($ahora);
        }
    }
    
    /**
     * Verifica si pico y placa está activo ahora
     */
    public function estaActivo() {
        if ($this->esFinDeSemana() || $this->esFestivo()) {
            return false;
        }
        
        $ahora = new DateTime();
        $horaActual = (int)$ahora->format('H');
        $horarioInicio = $this->ciudades[$this->ciudad]['horarioInicio'] ?? 6;
        $horarioFin = $this->ciudades[$this->ciudad]['horarioFin'] ?? 21;
        
        return $horaActual >= $horarioInicio && $horaActual < $horarioFin;
    }
    
    /**
     * Obtiene tiempo hasta próximo cambio (activación/desactivación)
     */
    public function getTiempoHastaPicoYPlaca() {
        $ahora = new DateTime();
        $horarioInicio = $this->ciudades[$this->ciudad]['horarioInicio'] ?? 6;
        $horarioFin = $this->ciudades[$this->ciudad]['horarioFin'] ?? 21;
        $horaActual = (int)$ahora->format('H');
        
        if ($horaActual >= $horarioInicio && $horaActual < $horarioFin) {
            $proximoTiempo = new DateTime();
            $proximoTiempo->setTime($horarioFin, 0, 0);
        } else {
            $proximoTiempo = new DateTime();
            $proximoTiempo->modify('+1 day');
            $proximoTiempo->setTime($horarioInicio, 0, 0);
        }
        
        $diff = $proximoTiempo->diff($ahora);
        $totalSegundos = ($diff->days * 86400) + ($diff->h * 3600) + ($diff->i * 60) + $diff->s;
        
        return [
            'horas' => $diff->h + ($diff->days * 24),
            'minutos' => $diff->i,
            'segundos' => $diff->s,
            'timestamp' => $totalSegundos
        ];
    }
    
    /**
     * Obtiene información general
     */
    public function getInfo() {
        return [
            'ciudad' => $this->getNombreCiudad(),
            'fecha' => $this->fecha->format('Y-m-d'),
            'dia' => $this->getDiaEnEspanol(),
            'restricciones' => $this->getRestricciones(),
            'permitidas' => $this->getPermitidas(),
            'horario' => $this->getHorario(),
            'es_fin_semana' => $this->esFinDeSemana(),
            'es_festivo' => $this->esFestivo(),
            'estado' => $this->getEstado(),
            'esta_activo' => $this->estaActivo()
        ];
    }
}
?>