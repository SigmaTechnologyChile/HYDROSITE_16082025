<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategoriaSeeder extends Seeder
{
    public function run()
    {
        DB::table('categorias')->truncate();
        
        // Categorías de INGRESOS
        $ingresos = [
            ['nombre' => 'Venta de Agua (Total Consumo)', 'tipo' => 'ingreso', 'grupo' => 'total_consumo'],
            ['nombre' => 'Cuotas de Incorporación', 'tipo' => 'ingreso', 'grupo' => 'cuotas_incorporacion'],
            ['nombre' => 'Venta de Medidores', 'tipo' => 'ingreso', 'grupo' => 'otros_ingresos'],
            ['nombre' => 'Trabajos en Domicilio', 'tipo' => 'ingreso', 'grupo' => 'otros_ingresos'],
            ['nombre' => 'Subsidios', 'tipo' => 'ingreso', 'grupo' => 'otros_ingresos'],
            ['nombre' => 'Otros Aportes', 'tipo' => 'ingreso', 'grupo' => 'otros_ingresos'],
            ['nombre' => 'Multas Inasistencia', 'tipo' => 'ingreso', 'grupo' => 'otros_ingresos'],
            ['nombre' => 'Otras Multas', 'tipo' => 'ingreso', 'grupo' => 'otros_ingresos'],
        ];
        
        // Categorías de EGRESOS
        $egresos = [
            ['nombre' => 'Energía Eléctrica', 'tipo' => 'egreso', 'grupo' => 'energia_electrica'],
            ['nombre' => 'Sueldos y Leyes Sociales', 'tipo' => 'egreso', 'grupo' => 'sueldos_leyes'],
            ['nombre' => 'Otras Ctas. (Agua, Int. Cel.)', 'tipo' => 'egreso', 'grupo' => 'otros_gastos_operacion'],
            ['nombre' => 'Mantención y reparaciones Instalaciones', 'tipo' => 'egreso', 'grupo' => 'gastos_mantencion'],
            ['nombre' => 'Gastos por Trabajos en domicilio', 'tipo' => 'egreso', 'grupo' => 'gastos_mantencion'],
            ['nombre' => 'Insumos y Materiales (Oficina)', 'tipo' => 'egreso', 'grupo' => 'gastos_administracion'],
            ['nombre' => 'Materiales e Insumos (Red)', 'tipo' => 'egreso', 'grupo' => 'gastos_mejoramiento'],
            ['nombre' => 'Mejoramiento / Inversiones', 'tipo' => 'egreso', 'grupo' => 'gastos_mejoramiento'],
            ['nombre' => 'Viáticos / Seguros / Movilización', 'tipo' => 'egreso', 'grupo' => 'otros_egresos'],
        ];
        
        foreach ($ingresos as $categoria) {
            DB::table('categorias')->insert($categoria);
        }
        
        foreach ($egresos as $categoria) {
            DB::table('categorias')->insert($categoria);
        }
    }
}
