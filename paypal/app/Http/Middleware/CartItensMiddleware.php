<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App\Models\Cart;

class CartItensMiddleware
{
    /**
     * Preciso ir em Kernel dar um nome para esse filtro
     */
    public function handle($request, Closure $next)
    {
        //não existe a sessão redireciona para a home
        if( !Session::has('cart') )
            return redirect()->route('home');
        //se for igual a zero via para home
        $cart = new Cart;
        //verificação da quantidade de itens, se for igual a zero redireciona
        if( $cart->totalItems() == 0 )
            return redirect()->route('home');
        
        //caso contrário deixa seguir a tragetória de onde veio
        return $next($request);
    }
}

