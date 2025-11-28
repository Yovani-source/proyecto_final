<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nave extends Model
{
    protected $table = 'naves';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'capacidad',
        'tipo'
    ];

    // Una nave puede tener muchos vuelos
    public function vuelos()
    {
        return $this->hasMany(Vuelo::class, 'nave_id', 'id');
    }
}

