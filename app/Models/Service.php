<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'id', 'org_id', 'member_id', 'nro', 'locality_id', 'sector', 'meter_number', 
        'invoice_type', 'meter_plan', 'percentage', 'diameter'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'locality_id');
    }

    public function locality()
    {
        return $this->belongsTo(Location::class, 'locality_id');
    }

    public function org()
    {
        return $this->belongsTo(Org::class, 'org_id');
    }
}
