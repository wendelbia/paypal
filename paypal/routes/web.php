<?php

$this->get('decrementar-carrinho/{id}', 'CartController@decrement')->name('decrement.cart');
$this->get('adicionar-carrinho/{id}', 'CartController@add')->name('add.cart');

$this->get('meu-perfil', 'UserController@profile')->name('user.profile');

$this->get('carrinho', 'CartController@index')->name('cart');
//é recomendado colocar a rota de login antes da rota barra "/"
Auth::routes();
$this->get('logout', 'UserController@logout')->name('user.logout');

//grupo de rotas
$this->group(['middleware' => 'auth'], function(){
    $this->get('meu-perfil', 'UserController@profile')->name('user.profile');
    //atualiza perfil
    $this->post('atualizar-perfil', 'UserController@profileUpdate')->name('update.profile');
    //atualiza a senha
    $this->get('minha-senha', 'UserController@password')->name('user.password');
    $this->post('atualizar-senha', 'UserController@passwordUpdate')->name('update.password');
    $this->get('meus-pedidos', 'StoreController@orders')->name('orders');
    $this->get('detalhes-pedido/{id}', 'StoreController@orderDetail')->name('order.products');

	$this->get('paypal', 'PayPalController@paypal')->name('paypal');
	
    $this->get('return-paypal', 'PayPalController@returnPaypal')->name('return.paypal');

});


//deleto a rota home, HomeController e home.blade.php pois ñ vamos utilizar
//Route::get('/home', 'HomeController@index')->name('home');
$this->get('/', 'StoreController@index')->name('home');