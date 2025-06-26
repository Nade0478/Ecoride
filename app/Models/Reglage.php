<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reglage extends Model
{
    protected $table = 'reglages';

    protected $fillable = [
        'id_reglage',
    ];

    public function getValueAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setValueAttribute($value)
    {
        $this->attributes['valeur'] = json_encode($value);
    }
}
