<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vuelo extends Model
{
    protected $table = 'vuelos';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'origen',
        'destino',
        'fecha',
        'hora',
        'precio',
        'nave_id'
    ];

    // Relación: un vuelo pertenece a una nave
    public function nave()
    {
        return $this->belongsTo(Nave::class, 'nave_id', 'id');
    }

    // Un vuelo puede tener muchas reservas
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'vuelo_id', 'id');
    }
}
