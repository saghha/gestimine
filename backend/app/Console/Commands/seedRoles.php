<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Hash;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class seedRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $this->info('Creando Perfiles y usuario SysAdmin');
        $user = \App\Models\User::updateOrCreate([
            'name' => 'SysAdmin',
            'rut' => '11111111',
            'password' => Hash::make('asdfasdf')
        ]);
        $sysadmin = Role::updateOrCreate(['name' => 'sysadmin', 'guard_name' => 'sanctum']);

        $planificador = Role::updateOrCreate(['name' => 'planificador', 'guard_name' => 'sanctum']);
        $jefe_mina = Role::updateOrCreate(['name' => 'jefe_mina', 'guard_name' => 'sanctum']);
        $jefe_turno = Role::updateOrCreate(['name' => 'jefe_turno', 'guard_name' => 'sanctum']);
        $perforador = Role::updateOrCreate(['name' => 'perforador', 'guard_name' => 'sanctum']);
        $transporte = Role::updateOrCreate(['name' => 'transporte', 'guard_name' => 'sanctum']);
        $explosivista = Role::updateOrCreate(['name' => 'explosivista', 'guard_name' => 'sanctum']);

        $user->assignRole($sysadmin);
        $this->info('usuario sysadmin creado');
        $this->info('rut: 11.111.111-1');
        $this->info('password: asdfasdf');

    }
}
