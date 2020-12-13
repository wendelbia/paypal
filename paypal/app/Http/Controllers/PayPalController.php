<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PayPal;
use App\Models\Cart;
use App\Models\Order;
use Session;


class PayPalController extends Controller
{
	//então agora será passado pelo header q foi criado se existe os itens do carrinho
	public function __construct()
    {
    	//verifica os itens do carrinho 
        $this->middleware('cart-items');
    }
    

    public function paypal(Order $order)
    {
    	//tenho um objeto de cart vou na model de paypal de passo no params o obj de carrinho
    	$cart = new Cart;
    	//paypal q é o objeto da model
    	//aqui espera um objeto de carrinho
        $paypay = new PayPal($cart);
        //para redirecionar a uma url externa devo usar away
        //modifico para result
        //return redirect()->away($paypay->generate());
        $result = $paypay->generate();
        //dd($paypal->generate());
        //verifico se result status é igual a true  
        if( $result['status'] ) {
        	//se for redireciono para a url 
            $paymentId = $result['payment_id'];
            //essa lógica cria uma nova ordem de produtos
            $order->newOrderProducts($cart->total(), $paymentId, $result['identify'], $cart->getItems());
            
            //cria a sessão com esses valores
            Session::put('payment_id', $paymentId);
            
            return redirect()->away($result['url_paypal']);

        } else {

            return redirect()->route('cart')->with('message', 'Erro inesperado!');
        }
    }
    
    public function returnPayPal(Request $request, Order $order)
    {
    	//envio o pedido para o paypal ele processa o pagamento devolve o pedido e envia para ele para aprovar o pagamento, aprovando então é feito a liberação
        //dd($request->all());

        //poderia converte para (boolean) 
        $success    = ($request->success == 'true') ? true : false;
        $paymentId  = $request->paymentId;
        $token      = $request->token;
        $payerID    = $request->PayerID;

        //verifico se esses valores estão vazios enviando para o usu q eles estão 
        if( !$success )
            return redirect()->route('cart')->with('message', 'Pedido Cancelado!');
        //se não passar esses valores volta para essa rota
        if( empty($paymentId) || empty($token) || empty($payerID) )
            return redirect()->route('cart')->with('message', 'Não autorizado!');

        //verifico se existe a sessão ou se a sessão é diferente de paymentId
        if( !Session::has('payment_id') || Session::get('payment_id') != $paymentId)
        	return redirect()->route('cart')->with('message', 'Não autorizado');
        
        $cart = new Cart;
        $paypal = new PayPal($cart);
        //chamo o método execute com os params 
        $response = $paypal->execute($paymentId, $token, $payerID);
        //se aprovado mudo o status do pedido
        if( $response == 'approved' ){
        	//em vez de chamar tudo isso eu chamo o método changeStatus criado na Order
        	//recupero a order pelo payment_id poderia ser pelo identify
            //$order->where('payment_id', $paymentId)
            //update atualizo para aprovado
                //->update(['status' => 'approved']);

        	//passo a paymentId e o status q é approved
        	$order->changeStatus($paymentId, 'approved');

            //para limpar o carrinho :
                $cart->emptyItems();
                //deleto também o identificador da transação, o payment_id
                Session::forget('payment_id');

            return redirect()->route('home');
        } else {
        	//caso contrário:
            //dd('Pedido não aprovado!');
            return redirect()->route('cart')->with('message', 'Pedido não foi aprovado');
        }
    }
}
