<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'exchange_entity';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'recv_amount', 'final_value', 'id_user', 'id_rate'
    ];

    public function rate() {
        return $this->belongsTo(Rate::class, 'id_rate', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
