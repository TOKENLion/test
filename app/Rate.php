<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'exchange_rates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'currency_code', 'ammount', 'value'
    ];

    public function entity() {
        return $this->hasMany(Entity::class, 'id_rate', 'id');
    }
}
