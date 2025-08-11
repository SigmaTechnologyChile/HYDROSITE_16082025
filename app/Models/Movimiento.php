<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $table = 'movimientos';
    public $timestamps = true; // Habilitado para usar created_at y updated_at

    protected $fillable = [
        // Campos Core/Básicos
        'org_id', 'fecha', 'tipo', 'monto', 'descripcion', 'numero_documento',
        
        // Campos de Categorización
        'categoria_id', 'categoria', 'grupo',
        
        // Campos de Cuentas/Bancarios
        'cuenta_origen_id', 'cuenta_destino_id', 'cuenta', 'banco_id',
        
        // Campos Tabulares existentes en la tabla (según estructura proporcionada)
        'total_consumo', 'cuotas_incorporacion', 'energia_electrica', 
        'giros', 'depositos', 'saldo_inicial', 'saldo_final',
        
        // Campos de Proveedor
        'proveedor', 'rut_proveedor',
        
        // Campos de Estado y Control
        'estado', 'conciliado', 'observaciones',
        
        // Campos de Auditoría
        'created_by', 'updated_by'
    ];

    protected $casts = [
        'fecha' => 'date',
        'monto' => 'decimal:2',
        'total_consumo' => 'decimal:2',
        'cuotas_incorporacion' => 'decimal:2',
        'energia_electrica' => 'decimal:2',
        'giros' => 'decimal:2',
        'depositos' => 'decimal:2',
        'saldo_inicial' => 'decimal:2',
        'saldo_final' => 'decimal:2',
        'conciliado' => 'boolean',
    ];

    // Relaciones
    public function org()
    {
        return $this->belongsTo(Org::class);
    }

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

    public function banco()
    {
        return $this->belongsTo(Banco::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeDeOrganizacion($query, $orgId)
    {
        return $query->where('org_id', $orgId);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopeEntreFechas($query, $fechaDesde, $fechaHasta)
    {
        return $query->whereBetween('fecha', [$fechaDesde, $fechaHasta]);
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }
}