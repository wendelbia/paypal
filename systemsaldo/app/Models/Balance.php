<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\User;

class Balance extends Model
{
    //declarando o não uso do timestamps
    public $timestamps = false;

    /*#19 método da lógica para inserir o depósito, vou no BalanceController e insiro o Balance $balance no params
    public function deposit($value)*/
	#20 posso especificar q tipo de retorno para o usu no caso Array e que tipo var
    public function deposit(float $value) : Array
    {
    #22 begin Transaction, uso o DB e chamo o método beginTransaction, caso insira o deposit e o historic aí irá comitar señ rollback
    DB::beginTransaction();

    #21 valor anterior ao deposito, a atualização, caso o vl do hist estiver vzio o null dará um erro, p q sja evitado isso acrecentamos um condição e isso solve the problem $totalBefore = $this->amount
    $totalBefore = $this->amount ? $this->amount : 0;
    /*#19 dou um dd no vl observamos q entrou o vl com sucesso	
    dd($value);*/
    /*#20 se eu fizer dd($this->amount); já tenho o próprio saldo do usu no caso zero o q será feito: pego o $vl e somo com o amount, formato o $value*/
    $this->amount += number_format($value, 2, '.', '');
    /*#20 faço um retorno para o usu armazenado em uma var */
    $deposit = $this->save();

    #21 histórico do usu, chamo auth=autenticado user=usu e historics=a relação q acabamos de criar e o método create q passamos um array com os valores criados, quando passo um método como esse ou update ou delete preciso ir na model e cria a var fillable = [] info qual colum da tb qro inserir, isso evita uma inserção inválida go historic
    $historic = auth()->user()->historics()->create([
    	//copio e colo dou ctrl+d seleciono oq qro e seta pro lado e enter
    	#21 I=input, amount=$value, total_before=amount antes da atualização 
    	'type' => 'I',
    	 'amount' => $value,
    	 'total_before' => $totalBefore,
    	 #21 total_after he o vl atualizado c entrada do usu
    	 'total_after' => $this->amount,
    	 'date' => date('Ymd'),
    ]);

	    #20if ($deposit) 
    	/*21 acrescento o historic
    	if($deposit && $historic)
	    	return [
	    		'success' => true,
	    		'message' => 'Sucesso ao depositar.'
	    	];
		return [
	    	'success' => false,
	    	'message' => 'Falha ao depositar.'
	    ];
		#21 inserindo o float e : Array vou no controller da um dd para ver o q se passa
	    */

	    #22 se o deposit e historic comitarem faço commit señ rollback
	    if($deposit && $historic){
	    	DB::commit();

	    	return [
	    		'success' => true,
	    		'message' => 'Sucesso ao depositar.'
	    	];
	    } else {


	    return [
	    	'success' => false,
	    	'message' => 'Falha ao depositar.'
	    ];
	    #23 agora vamos para a validação, vou no cdm para buscar o Request: php artisan make:request MoneyValidationFormRequest e vou lá para configurar MoneyValidationFormRequest
		}   
    }

    public function withdraw(float $value) : Array
    {
    	#26 verifico se a pessoa vai tirar mais do q ela tem, se o amount for menor q o vl informado então retorno impedimento 
    	if($this->amount < $value)
    		return [
    			'success' => false,
    			'message' => 'Saldo insuficiente',
    		];
	    	#26 begin Transaction, uso o DB e chamo o método beginTransaction, caso insira o deposit e o historic aí irá comitar señ rollback
	    DB::beginTransaction();

	    #26 valor anterior ao deposito, a atualização, caso o vl do hist estiver vzio o null dará um erro, p q sja evitado isso acrecentamos um condição e isso solve the problem $totalBefore = $this->amount
	    $totalBefore = $this->amount ? $this->amount : 0;
	    /*#26 agora vou decrementar em vez de incrementar*/
	    $this->amount -= number_format($value, 2, '.', '');
	    /*#26 faço um retorno para o usu armazenado em uma var */
	    $withdraw = $this->save();

	    #26 histórico do usu, chamo auth=autenticado user=usu e historics=a relação q acabamos de criar e o método create q passamos um array com os valores criados, quando passo um método como esse ou update ou delete preciso ir na model e cria a var fillable = [] info qual colum da tb qro inserir, isso evita uma inserção inválida go historic
	    $historic = auth()->user()->historics()->create([
	    	//copio e colo dou ctrl+d seleciono oq qro e seta pro lado e enter
	    	#26 O=outinput, amount=$value, total_before=amount antes da atualização
	    	'type' => 'O',
	    	 'amount' => $value,
	    	 'total_before' => $totalBefore,
	    	 #26 total_after he o vl atualizado c entrada do usu
	    	 'total_after' => $this->amount,
	    	 'date' => date('Ymd'),
	    ]);
    	/*26 acrescento o withdraw*/
	    if($withdraw && $historic){
	    	DB::commit();

	    	return [
	    		'success' => true,
	    		'message' => 'Sucesso ao sacar.'
	    	];
	    } else {


	    return [
	    	'success' => false,
	    	'message' => 'Falha ao sacar.'
	    ];
	    #26 agora testo e depois fazer a parte de transferência, vou na view index.blade.php para adicionar o icon#
		} 
    }

    #29 function p transferência, tenho $value tipo float e dou use na model User e passo como segundo params e uso Array como retorno
    public function transfer(float $value, User $sender): Array
    {
    	#29 verifico se a pessoa vai transferirr mais do q ela tem, se o amount for menor q o vl informado então retorno impedimento 
    	if($this->amount < $value)
    		return [
    			'success' => false,
    			'message' => 'Saldo insuficiente',
    		];
	    	#29 https://medium.com/@mateusgalasso/laravel-db-transaction-d0eb0ae224b : begin Transaction, se não conseguir fazer algum comando no DB faz o rollback automático, ele pode retornar algum dado se vc quiser por exemplo o id do registro q acabou de ser gavado no banco de dados, posso fazer assim: $idRetorn = DB::tansaction(function () use ($request){ $cliente = \Auth::user()->cliente;}) uso o DB e chamo o método beginTransaction, caso insira o deposit e o historic aí irá comitar señ rollback
	    DB::beginTransaction();

	    #29 valor anterior ao deposito, a atualização, caso o vl do hist estiver vzio ou null então dará um erro, pra q sja evitado isso acrecentamos uma condição que solve the problem $totalBefore = $this->amount
	    //$totalBefore = se $this->amount for true então depois da interrogação será executado caso contrário logo depois dos dois(2) pontos será executado
	    $totalBefore = $this->amount ? $this->amount : 0;
	    /*#29 agora vou decrementar em vez de incrementar*/
	    $this->amount -= number_format($value, 2, '.' , '');
	    /*#29 faço um retorno para o usu armazenado em uma var */
	    $transfer = $this->save();

	    #29 histórico do usu, chamo auth=autenticado user=usu e historics=a relação q acabamos de criar e o método create q passamos um array com os valores criados, quando passo um método como esse ou update ou delete preciso ir na model e cria a var fillable = [] info qual colum da tb qro inserir, isso evita uma inserção inválida go historic
	    $historic = auth()->user()->historics()->create([
	    	//copio e colo dou ctrl+d seleciono oq qro e seta pro lado e enter
	    	#29 T=transference, amount=$value, total_before=amount antes da atualização
	    	'type' 					=> 'T',
	    	'amount' 				=> $value,
	    	'total_before' 			=> $totalBefore,
	    	 #29 total_after he o vl atualizado c entrada do usu
	    	'total_after' 			=> $this->amount,
	    	'date' 					=> date('Ymd'),
	    	 #29 id do usu q vai recebe essa transfer e passamos pra cá o id do usu q vai receber essa transfer q é o sender->id
	    	'user_id_transaction' 	=> $sender->id
	    ]);

	    #29 agora atualizamos o saldo do recebedor, var recebe o recebedor que o objeto da model User $sender, o firstOrCreate caso o recebedor tenha nada na conta
	    $senderBalance = $sender->balance()->firstOrCreate([]);
	    #29 totalBeforeSender recebe o vl do mesmo
	    $totalBeforeSender = $senderBalance->amount ? $senderBalance->amount : 0;
	    #29 vou incrementar
	    $senderBalance->amount += number_format($value, 2, '.', '') ;
	    $transferSender = $senderBalance->save();

	    $historicSender = $sender->historics()->create([
	    	 'type' 					=> 'I',
	    	 'amount' 					=> $value,
	    	 'total_before'             => $totalBeforeSender,
	    	 'total_after' 				=> $senderBalance->amount,
	    	 'date' 					=> date('Ymd'),
	    	 'user_id_transaction' 		=> auth()->user()->id,
	    ]);
    	/*29 verifico transfer, historic e usu q recebe*/
	    if($transfer && $historic && $transferSender && $historicSender){
	    	DB::commit();

	    	return [
	    		'success' => true,
	    		'message' => 'Sucesso ao Transferir.'
	    	];
	    }

	    DB::rollback();

	    return [
	    	'success' => false,
	    	'message' => 'Falha ao Transferir.'
	    ];
	    #29 confiro no Controller
		
    }
}





















