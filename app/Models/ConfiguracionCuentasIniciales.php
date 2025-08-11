<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionCuentasIniciales extends Model
{
    protected $table = 'configuracion_cuentas_iniciales';
    
    protected $fillable = [
        'org_id',
        'tipo_cuenta',
        'saldo_inicial',
        'responsable',
        'banco_id',
        'nombre_banco', // ← NUEVO CAMPO
        'numero_cuenta',
        'observaciones',
        'copiado_a_cuentas',
        'cuenta_id'
    ];

    protected $casts = [
        'saldo_inicial' => 'decimal:2',
        'copiado_a_cuentas' => 'boolean',
    ];

    // Relaciones
    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'org_id');
    }

    public function banco()
    {
        return $this->belongsTo(Banco::class, 'banco_id');
    }
}
