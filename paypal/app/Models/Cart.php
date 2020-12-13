<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;

class Cart extends Model
{
	//recebe um array vazio
    private $items = [];
    //construtor para fazer a verificação
    public function __construct()
    {
    	//se já existe a sessão cart então pego a sessão e adiciono mais um para o carrinho
        if( Session::has('cart') ){
            $cart = Session::get('cart');
            //atribuo aqui os items do carrinho
            $this->items = $cart->items;
        }
    }
    
    public function add(Product $product)
    {
    	//estou adicionando os produtos no carrnho
    	//se existe produto, item produto foi inicializado (foi adicionado)
        if( isset($this->items[$product->id]) ) {
        	//verificando se esta pegando a quatidade de item 
        	//dd($this->items[$product->id]['qtd']);
        	//então items product recebe item e qtd+1
            $this->items[$product->id] = [
                'item'  => $product,
                //então pego a quantidade q já existe e somo mais 1
                'qtd'   => $this->items[$product->id]['qtd'] + 1,
            ];
        } else {
            $this->items[$product->id] = [
                'item'  => $product,
                'qtd'   => 1,
            ];
        }
    }
    //passo como params o produto q qro decrrementar
    public function decrementItem(Product $product)
    {
    	//se existe esse item
        if( isset($this->items[$product->id]) ) {
            //verifico se a qtd é igual a 1
            if( $this->items[$product->id]['qtd'] == 1 ) {
            	//dou um unset(removendo) pelo posição dele e qual é a posição? é items[$product->id]
                unset($this->items[$product->id]);
            } else {
            	//caso contrário pego a posição menos 1
                $this->items[$product->id] = [
                    'item'  => $product,
                    //pego a quantidade menos 1
                    'qtd'   => $this->items[$product->id]['qtd'] - 1,
                ];
            }
            
        }
    }
    
    public function getItems()
    {
        return $this->items;
    }
    
    public function total()
    {

        $total = 0;
        //se qtd de items for igual a zero
        if( count($this->items) == 0 )
        	//total
            return $total;
        //faz um loop para passar por todos os itens 
        foreach($this->items as $item) {
        	//recebe o subtotal
            $subTotal = $item['item']->price * $item['qtd'];
            //esse é o total q é o total + qtd de item mais ele mesmo
            $total += $subTotal;
        }
        //retorna o total
        return $total;
    }
    //retorn a qtd de itens q tem no carrinho, será colocado no navbar
    public function totalItems()
    {
        return count($this->items);
    }

    //para esvaziar o carrinho
    public function emptyItems()
    {
        //se existe a sessão cart 
        if( Session::has('cart') )
            //limpo o carrinho
            Session::forget('cart');
    }
}
