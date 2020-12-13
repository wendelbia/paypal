<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use Session;

class CartController extends Controller
{
    //
    public function index()
    {
    	$title = 'Meu Carrinho de Compras';
    	$cart = Session::has('cart') ? Session::get('cart') : new Cart;
    	//dd($cart->getitems());
    	//e coloco na url adiconar-carrinho/id

    	$total = $cart->total();

	    //armazeno esses produtos na var e mostro na view
	    $products = $cart->getitems();    		
    	return view('store.cart.index', compact('title', 'products', 'total'));
    }

    public function add(Request $request, $id)
    {
    	//recupero o produto pelo seu id 
        $product = Product::find($id);
        //s ñ encontrar o produto (a var produtct) redireciono para home
        if( !$product )
            return redirect()->route('home');
        
        $cart = new Cart;
        //para salvar o carrinho uso a sessão
        $cart->add($product);
        //dd($cart);
        //put para adicionar um item, passo o próprio objeto 
        $request->session()->put('cart', $cart);
        
        return redirect()->route('cart');
    }
    public function decrement(Request $request, $id)
    {
        $product = Product::find($id);
        if( !$product )
            return redirect()->route('home');
        
        $cart = new Cart;
        $cart->decrementItem($product);
        //só até aqui não irá functionar por tanto preciso atualizar com a sessão
        $request->session()->put('cart', $cart);
        
        return redirect()->route('cart');
    }
}
