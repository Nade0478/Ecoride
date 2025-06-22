<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    protected $table = 'configurations';

    protected $fillable = [
        'id_configuration',
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
