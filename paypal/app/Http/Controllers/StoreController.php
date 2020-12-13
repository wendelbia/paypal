<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use Gate;

class StoreController extends Controller
{
    //
    public function index()
    {
    	$title = 'Home Page Laravel PayPal';
    	//deixando pelo id decrescente
    	$products = Product::orderBy('id', 'DESC')->get();
    	return view('store.home.index', compact('title', 'products'));
    }

    public function orders()
    {
        $title = 'Meus Pedidos';
        
        $orders = Order::where('user_id', auth()->user()->id)->get();
        
        return view('store.orders.orders', compact('title', 'orders'));
    }

    public function orderDetail($idOrder)
    {
        $order = Order::find($idOrder);
        if( !$order )
            return redirect()->route('orders');
        //dd($order->user_id == auth);
        //essa é uma alternativa sem usar o gate q já não permitiria usu intruzo
        //$this->authorize('owner-order', $order);

        //gate é um recurso de authorization do laravel, em providers/authServiceProvider/boot(){} depois de fazer o gate faço a condição sñ tiver autorização entra no if
        if( Gate::denies('owner-order', $order) )
        	//retorna para onde vc estava
            return redirect()->back();
        //aqui recupera os produtos da ordem
        $products = $order->products()->get();
        
        $title = "Produtos do Pedido: {$order->id}";
        
        return view('store.orders.items', compact('order', 'products', 'title'));
    }
}
