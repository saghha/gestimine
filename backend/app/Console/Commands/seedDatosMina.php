<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DatosMina\DatosMina;
use \Carbon\Carbon;

class seedDatosMina extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:datosmina {arg}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea los datos iniciales de los datos mina generales';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Creando Datos Mina Inicial");
        $value = 0.00;
        $tiempo = Carbon::now();
        $activo = DatosMina::updateOrCreate([
            'id_usuario' => intval($this->argument('arg')),
            'periodo_por_ano' => 0,
            'meses_por_periodo' => 0,
            'dias_por_mes' => 0,
            'turnos_por_dia' => 0,
            'fecha_inicio' => $tiempo,
            'avance_tronadura' => $value,
            'toneladas_incorporadas_tronadura' => $value,
            'ritmo_extraccion' => $value,
            'mineral_recuperado_modulo' => $value,
            'mineral_recuperado_pilares' => $value,
            'densidad_esteril' => $value,
            'densidad_mineral' => $value,
            'densidad_dilusion' => $value,
            'ley_esteril' => $value,
            'ley_mineral' => $value,
            'ley_diluida' => $value,
            'tiros_por_m2' => $value,
            'profundidad_tiro' => $value
        ]);
        $this->info("Datos Mina Inicial Creados Satisfactoriamente");
    }
}
