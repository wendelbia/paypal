<?php

//crio um grupo de rotas para todos os controllers q vão passar pelo admin
//middleware fará a filtragem através do auth
//namespace fará a busca da pasta Admin
$this->group(['middleware' => ['auth'], 'namespace' => 'Admin', 'prefix' => 'admin'], function(){
	//#16 rota para banlance exibir saldo, carregou a url banlance, eu qero admin, vou em confg/adminlte em submenu do saldo e coloco admin/balance e em historic também e coloco em nosso grupo de configuração um prefixo admin, então não preciso passar admin/balance, agora crio um div no index
	$this->get('balance', 'BalanceController@index')->name('admin.balance');

	//#16 aqui por causo do prefixo não perciso passar mais admin e sim "/"
	$this->get('/', 'AdminController@index')->name('admin.home');

	/*#18 vou no controller cria esse método deposit e faço a view*/
	$this->get('deposit', 'BalanceController@deposit')->name('balance.deposit');
	/*#18 vou no controller cria esse método store e faço a view*/
	$this->post('deposit', 'BalanceController@depositStore')->name('deposit.store');

	/*#25 vou no controller criar esse método*/
	$this->get('withdraw', 'BalanceController@withdraw')->name('balance.withdraw');
	/*#25 vou no controller criar esse método */
	$this->post('withdraw', 'BalanceController@withdrawStore')->name('withdraw.store');

	/*#27 vou no controller criar esse método balance.transfer*/
	$this->get('transfer', 'BalanceController@transfer')->name('balance.transfer');
	/*#27 vou no controller criar esse método confirmTransfer*/
	$this->post('confirm-transfer', 'BalanceController@confirmTransfer')->name('confirm.transfer');

	/*#28 vou no controller criar esse método transferStore*/
	$this->post('transfer', 'BalanceController@transferStore')->name('transfer.store');

	/*#30 vou no controller criar esse método historic*/
	$this->get('historic-search', 'BalanceController@historic')->name('admin.historic');

	/*#33 vou na blade e passo essa rota historic search*/
	$this->any('historic', 'BalanceController@searchHistoric')->name('historic.search');


});
#37 crio a rota perfil fora do admin q tem com middleware de nível auth q dará acesso ao perfil só quem estive logado e dev o método profile.update
$this->get('meu-perfil', 'Admin\UserController@profile')->name('profile')->middleware('auth');

#37 crio a rota atualizar perfil fora do admin q tem com middleware de nível auth q dará acesso ao perfil só quem estive logado e dev o método 
$this->post('atualizar-perfil', 'Admin\UserController@profileUpdate')->name('profile.update')->middleware('auth');

$this->get('/', 'Site\SiteController@index')->name('home');

Auth::routes();


