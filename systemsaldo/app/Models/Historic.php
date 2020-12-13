<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
#31 chamo carbon
use Carbon\Carbon;
use App\User;

class Historic extends Model
{
    //criamos e voltemos o balance
    protected $fillable = ['type', 'amount', 'total_before', 'total_after', 'user_id_transaction', 'date'];

    #31 mutation, pega o valor q é a data
    public function getDateAttribute($value)
    {
    	#vamos da um use lá em cima chamo o método parse
    	return Carbon::parse($value)->format('d/m/Y');
    }

    #31 substitui os tipos pelos nomes
    public function type($type = null)
    {
    	$types = [
	    	'I' => 'Entrada',
	    	'O' => 'Saque',
	    	'T' => 'Transferência',
    	];

    	#31 se type ñ for null então:
    	if(!$type)
    		return $types;
    	#31 mudamos o transferência por Recebido pelo tipo, então se recebedor for diferente de nulo e de input retorna Recebido, vamos na blade fazer um @if($historic->user_id...)
    	if($this->user_id_transaction != null && $type == 'I')
    		return 'Recebido';
    	return $types[$type];
    	#31 vmos na blade dev
    }

    #36 scope global, passo paams query
     public function scopeUserAuth($query)
    {
        return $query->where('user_id', auth()->user()->id);
    }

    #31 relacionamente inverso de vários para um, para buscar usu
    public function user()
    {
    	return $this->belongsTo(User::class);
    	#31 dev na blade
    }
    #31 relacionamente inverso de vários para um, para buscar usu
    public function userSender()
    {
    	#31 passo como segundo params a colum q qero relacionar, e vou no controller
    	return $this->belongsTo(User::class, 'user_id_transaction');
    	#31 dev na blade
    }

    #34 function de pesquisa, params do tipo array junto com item por pág.
    public function search(Array $data, $totalPage)
    {
    	#34 retorna uma query avançada já q são muitos itens a serem pesquisados, uso um callback q tem um params com as opções que eu quero trabalhar ñ passo a var $data dentro do callback pq dentro ñ aceita então chamo usando o use ($data) retorno um filtro mais avançado, mas nem sempre todos os valores serão informados, será escolhido 1 ou 2 vl ou até todos para serem filtrados
    	#34 pra debugar tiro o return e troco pela var $historic e no lugar de ->paginate($total) ->toSql()->dd($historics); e vou BalanceController e acrescento $historic->seach(); 
    	#34return $this->where(function ($query) use ($data) {
    	return $this->where(function ($query) use ($data) {
    			#34 se $data existe dentro de id então passo um filtro para ele
    		if (isset($data['id']))
    				$query->where('id', $data['id']);
    		if (isset($data['date']))
    				$query->where('date', $data['date']);
    		if (isset($data['type']))
    			$query->where('type', $data['type']);
    	})
    	#36 faço o where no id do usu log para filtar apenas dados do usu logado
    	#36->where('user_id', auth()->user()->id)
    	#36 outra maneira de fazer isso e usando o scope, o laravel tem um isso scope global q utiliza essa quer onde se pode ter esse query no scope global ou posso utilizar o scope local para cria essa query e reeaproveitar onde quizer, então acima de function user() faço essa função scope, amarro essa função, não preciso passar os primeiros caracters "scope" apenas userAuth e sem o $query em menúsculo e posso usar um dd() para verificar tirando o ->paginate()
    	->userAuth()

    	#36 epara economizar consultas chamo também o userSender#
    	#37 agora vamos preparar a exibição do perfil em view/site/home/index.php
    	->with(['userSender'])
    	#34para testeno lugar de ->paginate($total) 
    	#34->toSql();dd($historics);
    	->paginate($totalPage); 
    	#34 retorno o resultado da pesquisa->paginate($totalPage);
    	return $historics;

    }
}
