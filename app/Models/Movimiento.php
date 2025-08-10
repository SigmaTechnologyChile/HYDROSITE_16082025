<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $table = 'movimientos';
    public $timestamps = false;

    protected $fillable = [
        'org_id', 'tipo', 'subtipo', 'fecha', 'monto', 'descripcion', 'nro_dcto',
        'categoria_id', 'cuenta_origen_id', 'cuenta_destino_id',
        'proveedor', 'rut_proveedor', 'transferencia_id', 'creado_en',
        // Columnas del libro tabular
        'total_consumo', 'cuotas_incorporacion', 'otros_ingresos', 'giros',
        'energia_electrica', 'sueldos_leyes', 'otros_gastos_operacion',
        'gastos_mantencion', 'gastos_administracion', 'gastos_mejoramiento',
        'otros_egresos', 'depositos'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function cuentaOrigen()
    {
        return $this->belongsTo(Cuenta::class, 'cuenta_origen_id');
    }

    public function cuentaDestino()
    {
        return $this->belongsTo(Cuenta::class, 'cuenta_destino_id');
    }
}