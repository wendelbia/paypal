<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Balance;
use App\Models\Historic;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //método
    public function balance()
    {
        /*#17 lá em cima chamo o Balance, faço o relacionamento de um pra um, com isso já consigo retornar o saldo do usu através desse método vou em BalanceController para recuperar esses dados*/
        return $this->hasOne(Balance::class);
    }
    #21 faço um relacionamento de 1 para muitos de One to many um usu terá vários históricos, cada histórico representa 1 usu
    public function historics()
    {
        return $this->hasMany(Historic::class);
        #21 feito isso vou na model Balance para registar o historico do usu logo abaixo do $this->amount...
    }

    #28 busco os dados do sender
    public function getSender($sender)
    {
        #28 faço this q é instância dessa própria classe, filtro pelo nome e uso o LIKE para pegar o nome igual e uso % para buscar tando pelo começo do nome quanto pelo fim dele
        return $this->where('name', 'LIKE', "%$sender%")
                #28 uso o orWhere (ou outro dado) e filtro pelo email e ñ preciso uso o 'email' ==  '$sender' apenas virgula sender
                    ->orWhere('email', $sender)
                    #28 posso usar esse método para saber qual query está sendo rodada
                    #->toSql();
                    #28 get p recuperar
                    ->get()
                    ->first();
    }
}
