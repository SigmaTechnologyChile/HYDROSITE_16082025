<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'org_id',
        'member_id',
        'service_id',
        'reading_id',
        'locality_id',
        'folio',
        'type_dte',
        'price',
        'total',
        'qty',
        'status',
        'payment_method_id',
        'description',
        'payment_status'
    ];

    protected $casts = [
        'price' => 'integer',
        'total' => 'integer',
        'qty' => 'integer',
        'status' => 'integer',
        'payment_status' => 'integer'
    ];

    /**
     * Relación con Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relación con Service
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Relación con Reading
     */
    public function reading()
    {
        return $this->belongsTo(Reading::class);
    }

    /**
     * Relación con Member
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Relación con Org
     */
    public function org()
    {
        return $this->belongsTo(Org::class);
    }
}
