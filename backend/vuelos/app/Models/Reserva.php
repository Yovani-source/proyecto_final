<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'reservas';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'vuelo_id',
        'usuario_id',
        'fecha_reserva'
    ];

    // Relación: una reserva pertenece a un vuelo
    public function vuelo()
    {
        return $this->belongsTo(Vuelo::class, 'vuelo_id', 'id');
    }

    // Relación: una reserva pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_id', 'id');
    }
}
