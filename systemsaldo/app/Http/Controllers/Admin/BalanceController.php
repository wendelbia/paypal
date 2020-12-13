<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Balance;
#23 chamo a classe
use App\Http\Requests\MoneyValidationFormRequest;
#28 insiro o use
use App\User;
#33 Historic para filtragem
use App\Models\Historic;

class BalanceController extends Controller
{
	private $totalPage = 5;
    public function index()
    {	
    	/*#16 crio views/admin/balance
    	return view('admin.balance.index');*/
    	
    	/*#17 aqui eu injeto a model do usu para criar o objeto do usu ou posso simplesmente pego os dados do usu logado, e como faço?
    	dd( auth()->user() );
    	dd( auth()->user()->name );
    	e pra retornar o saldo do usu?
    	dd( auth()->user()->balance ); 
    	posso chamá-lo em formato de método também que retorna uma colection no caso vazia
    	dd( auth()->user()->balance()->get());
    	e vou passar esse var através do compact, verifico se o amout é null, observamos q lá no bd o amout é uma coluna, se tem algo no amout então retorno ele, senão retorno zero e em index.blade.php exibo o valor dela*/
    	$balance = auth()->user()->balance;
    	$amount = $balance ? $balance->amount : 0;
    	
    	return view('admin.balance.index', compact('amount'));
    }
    /*#18 método para depósito, crio a view e formato*/
    public function deposit()
    {
    	return view('admin.balance.deposit');
    }
    /*#19 aqui usando Balance eu crio o objeto e jogo na var $balance, lá em cima chamo o use Balance
    pela variável $balance ser um objeto de Balance ñ peciso injetá-la como params
    public function depositStore(Request $request, Balance $balance)*/

    #23 troco Request pela classe de validação
    #public function depositStore(Request $request) e vou em deposit.blade.php para fazer as mensagens de erro
    public function depositStore(MoneyValidationFormRequest $request)
    {
    	/*#19 método para depositar, injeto o Request, para pegar o valor uso esse método ->all() q pega todo o array de dados ou armazeno o all() em uma var ou dd($request->all()); e levamos essa lógiaca lá para o Balance.php
    	dd($request->value); e assim $balance->deposit($request->value); e agora chamo o método q foi criado no Balance e passo o valor pego no input lá na model q tem a responsabilidade de fazer a lógica,o q precisa ser feito aqui: recuperar o saldo do usu por tanto pego o usu logado(auth) e o usuário(user) e os dados da conta (balance) dou um dd
    	Vamo nos atentar para o seguinte detalhe: se eu pegar o saldo de um usu preciso somar o deposito com o saldo atual, mas se ñ há nada depositado então dará erro, para isso não acontecer uso um método chamado firstOrCreate([]) q recebe um array c/ os vls q qro passar, ele verifica se existe esse registro, se existe então retorna pra mim sñ existe ele cria e retorna pra mim também
    	dd(auth()->user()->balance()->get()); portanto uso assim, o q faria aqui, no balance ele buscaria o depósito e no firtOrCreate tendo amount como zero ele verificaria se assim é e criaria um novo vl isso não qero por isso coloco um array vazio
    	dd(auth()->user()->balance()->firstOrCreate(['amount' => 0]);
    	; isso dará um erro e para corrigí-lo precisamos mudar a estrutura da nossa tabela definindo o vl do amount como default o vl zero vou no migration balance e no $table->doble(amount) acrescento o ->default(0) vou em seeders em UsersTableSeeder.php para transferência apenas, ou no cdm e rodo (php artisan migrate:refresh) --seed q apaga e cria uma nova agora podemos ver q na tb de balance no amount temos o zero pois o firstOrCreate criou para o balance assim não dando mais erro
    	dd(auth()->user()->balance()->firstOrCreate([]));
    	armazeno esse saldo em uma var e $balance e chamo o método que vai fazer o depósito que é o deposit($request...), o $balance é um objeto de Balance por isso não preciso injetar como params (Balance $balance) agora vou no Balance implementar o método deposit() agora vou desenvolver o historic lá na model User.php*/
        //sobre firstOrCreate([]): https://imasters.com.br/back-end/um-pouco-mais-sobre-criacao-de-models-com-eloquent
    	$balance = auth()->user()->balance()->firstOrCreate([]);
    	#21 dou um dd() 
    	#dd($balance->deposit($request->value));
    	#22 e agora vou no Balance para desenv. o begin transaction q é uma sincronia do deposit com o historic na inserção com o banco de dados

    	#24 armazeno em uma var esse comando
    	$response = $balance->deposit($request->value);
    	#24 e verifico se $response é igual a true
    	if($response['success'])
    		return redirect()
    				->route('admin.balance')
    				->with('success', $response['message']);
    	#24 caso contrário
    	return redirect()
    				->back()
    				->with('error', $response['message']);
    	#24 crio uma pasta views/admim/includes/alerts.blade.php
    }
    #25 pág que saque
    public function withdraw()
    {
    	return view('admin.balance.withdraw');
    }

    #25 lógica do saque
    public function withdrawStore(MoneyValidationFormRequest $request)
    {
    	/*25 dou um dd e vamos implementar
    	dd($request->all());*/
        //sobre firstOrCreate([]): https://imasters.com.br/back-end/um-pouco-mais-sobre-criacao-de-models-com-eloquent
    	$balance = auth()->user()->balance()->firstOrCreate([]);
    	
    	#26 armazeno em uma var esse comando
    	$response = $balance->withdraw($request->value);
    	#26 e verifico se $response é igual a true
    	if($response['success'])
    		return redirect()
	    				->route('admin.balance')
	    				->with('success', $response['message']);
    	#26 caso contrário
    	return redirect()
    				->back()
    				->with('error', $response['message']);
    	#26 vou no Balance implementar esse método crio uma pasta views/admim/includes/alerts.blade.php
    }

    #27 método para buscar a view
    public function transfer()
    {
    	return view('admin.balance.transfer');
    }

    #27 método para transferência
    #28 injeto o User $user como params
    public function confirmTransfer(Request $request, User $user)
    {
    	/*#27 verifico se está ok, e vou em User.php buscar os dados do sender, o recebedor da tranferência
    	dd($request->all());*/
    	#28 injeto o User $user como parâmetro e um use lá em cima e declaro a var q recebe a function da Use como params o $request
    	if (!$sender = $user->getSender($request->sender))
    	//dd($sender);
    	#28 caso usu ñ encontrado uso o return
			return redirect()
						->back()
						->with('error', 'Usuário informado não foi encontrado!');
		#28 para evitar do usu fazer tranferência para ele mesmo pegamos o id do sender e comparamos com o do id usu logado
		if($sender->id === auth()->user()->id)
			return redirect()
						->back()
						->with('error', 'Não pode tranferir para você mesmo!');
		#28 para mostar o vl do saldo do usu logado pego var q recebe usu logado e os dados da tb balance e passamos para nossa view pelo compact
		$balance = auth()->user()->balance;
    	#28 chamo a view de confirmação
		return view('admin.balance.transfer-confirm', compact('sender', 'balance'));
		#28 criamos a view transfer-confirm
    }
    #29 uso a validação e o User
    public function transferStore(MoneyValidationFormRequest $request, User $user)
    {
        /*#29 verifico
    	dd($request->all());*/ 

    	/*#29 como estou usando o User utilizo o find() para saber o id do user q qero fazer a recarga, q é exatamente o campo sender_id do input type="hidden" name="sender_id" value="{{ $sender->id }}"> da blade transfer-confirm
    	dd($user->find($request->sender_id));*/
    	#29 faço uma verificação caso não encontre o user com esse id se sim recupero o sender pra quem qro fazer a transfer
    	if (!$sender = $user->find($request->sender_id))
    		return redirect()
    					->route('balance.transfer')
    					->with('success', $response['Recebedor Não Encontrado']);
        //sobre firstOrCreate([]): https://imasters.com.br/back-end/um-pouco-mais-sobre-criacao-de-models-com-eloquent
        //fará a criação de um registro, mas primeiro ele validará se o registro já existe no banco. Caso já exista, a função retornará o resultado, sñ, ele criará o registro. como fica claro, este comando só será útil para realizar a criação de dados q ~s únicos. Já q ñ qro q existam mais de uma linha de mesmo nome
    	$balance = auth()->user()->balance()->firstOrCreate([]);
    	
    	#29 armazeno em uma var esse comando, se encontro o recebedor passo o método transfer os params q são o vl($request->value) q recebi e quem vai receber (sender) e vou no Balance cria a function transfer
    	$response = $balance->transfer($request->value, $sender);
    	#29 e verifico se $response é igual a true
    	if($response['success'])
    		return redirect()
    	#29 se ok vai para admin.balance
	    				->route('admin.balance')
	    				->with('success', $response['message']);
    	#29 caso contrário
    	return redirect()
    				->back()
    				->with('error', $response['message']);
    	#29 agora a execução do histórico do usu, faço a rota no web.php
    }

    #30 pag historic blade
    #33 pra filtragem não posso usar esse método para buscar o userSender que tem o type(os tipos de transações) pois ele retorna ->paginate() 
    #33 public function historic() então acrescento a model Historic
    public function historic(Historic $historic)
    {
    	#30 var recebe usu autenticado o método historics
    	/*#31 usa assim no 30
    	$historics = auth()->user()->historics()->get();*/

    	/*#32 Troco o get pelo paginate para paginação, crio um atributo para isso
    	$historics = auth()->user()->historics()->with(['userSender'])->get();*/
    	$historics = auth()
    					->user()
    					->historics()
    					->with(['userSender'])
    					->paginate($this->totalPage);
    					#32 vou na view para paginar
    	#33 type que busca type()
    	$types = $historic->type();

    	//31 no relation ñ mostra nada, mas acrescentado como params o método userSender ->with(['userSender']) busco a relation, como uso essa query de forma mais amigável? Vou na blade
    	#31 dd($historics);
    	#33 passo o $types para a view e acerscento lá no foreach
    	return view('admin.balance.historics', compact('historics','types'));
    	#30 e crio a view
    }
    #33 método de pesquisa# 
    #33public function searchHistoric(Request $request)
    #34 acrescento Historic para usar o seu objeto
    public function searchHistoric(Request $request, Historic $historic)
    {
    	#34 verifico se fez a busca e armazno esse dd em uma var
    		#34 dd($request->all());
    	#34 crio a var q recebe os dados de historic e na model Historic crio uma função q vai centralizar os comandos necessários para filtragem
    	#34 $dataForm = $request->all();
    	#35 uso o except p tirar da url o token
    	$dataForm = $request->except('_token');
    	#34 pego o objeto e passo os dados do formulário e o total das páginas
    	$historics = $historic->search($dataForm, $this->totalPage);

    	$types = $historic->type();
		#35 depois de filtrar quando acesso a pág 2 perco o filtro então vamos resolver isso, pego a $dataForm e passo para a view
    	return view('admin.balance.historics', compact('historics','types', 'dataForm'));

    }

}
