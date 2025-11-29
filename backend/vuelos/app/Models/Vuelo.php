<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vuelo extends Model
{
    protected $table = 'flights';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nave_id',
        'origin',
        'destination',
        'departure',
        'arrival',
        'price'
    ];

    // Un vuelo pertenece a una nave
    public function nave()
    {
        return $this->belongsTo(Nave::class, 'nave_id', 'id');
    }

    // Un vuelo puede tener muchas reservas
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'flight_id', 'id');
    }
}
