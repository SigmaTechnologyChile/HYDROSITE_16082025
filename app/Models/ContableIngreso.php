<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContableIngreso extends Model
{
    protected $table = 'contable_ingresos';
    protected $fillable = [
        'order_id',
        'reading_id',
        'fecha',
        'monto',
        'categoria',
        'tipo',
        'grupo',
        'detalle',
        'usuario_id',
    ];
}
