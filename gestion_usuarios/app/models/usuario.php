<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class usuario extends Model
{
    protected $table = 'users'; // nombre exacto de tu tabla
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'token'
    ];
}
