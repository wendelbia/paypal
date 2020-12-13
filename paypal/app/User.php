<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    //preenchimento abrigatório no mínimo 2 caracteres máximo 100
    public $rulesProfile = [
        'name' => 'required|min:3|max:100',
    ];
    //deve ser confirmada
    public $rulesPassword = [
        'password' => 'required|min:5|max:15|confirmed',
    ];
}
