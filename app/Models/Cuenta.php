<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    protected $table = 'cuentas';

    protected $fillable = [
        'org_id', 
        'nombre', 
        'tipo', 
        'saldo_actual', 
        'banco_id', 
        'nombre_banco', // ← NUEVO CAMPO
        'numero_cuenta', 
        'responsable'
    ];

    protected $casts = [
        'saldo_actual' => 'decimal:2',
    ];

    // Relaciones
    public function banco()
    {
        return $this->belongsTo(Banco::class, 'banco_id');
    }

    public function movimientosOrigen()
    {
        return $this->hasMany(Movimiento::class, 'cuenta_origen_id');
    }

    public function movimientosDestino()
    {
        return $this->hasMany(Movimiento::class, 'cuenta_destino_id');
    }

    // Scopes
    public function scopePorOrganizacion($query, $orgId)
    {
        return $query->where('org_id', $orgId);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    // Métodos auxiliares
    public function getNombreBancoAttribute()
    {
        return $this->banco ? $this->banco->nombre : null;
    }
}