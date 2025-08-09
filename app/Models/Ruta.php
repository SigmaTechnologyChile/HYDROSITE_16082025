<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    use HasFactory;

    protected $fillable = [
        'org_id',
        'location_id', 
        'service_id',
        'orden'
    ];

    // Relación con organización
    public function org()
    {
        return $this->belongsTo(Org::class);
    }

    // Relación con ubicación/sector
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // Scope para obtener rutas ordenadas por sector
    public function scopeOrdenadas($query, $orgId, $locationId)
    {
        return $query->where('org_id', $orgId)
                     ->where('location_id', $locationId)
                     ->orderBy('orden', 'ASC');
    }

    // Scope para una organización específica
    public function scopePorOrganizacion($query, $orgId)
    {
        return $query->where('org_id', $orgId);
    }
}
