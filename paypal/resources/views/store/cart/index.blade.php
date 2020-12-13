@extends('store.templates.master')

@section('content')

<h1 class="title">Meu Carrinho de Compras:</h1>
@if( session('message') )
    <div class="alert alert-warning">
        {{session('message')}}
    </div>
@endif
<table class="table table-striped">
    <thead>
        <tr>
            <th>Item</th>
            <th>Preço</th>
            <th>Qtd</th>
            <th>Sub. Total</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products as $product)
        <tr>
            <td>
                <div>
                    <img src="{{url("assets/imgs/temp/{$product['item']->image}")}}" alt="" class="product-item-img-cart">
                    <p class="cart-name-item">{{$product['item']->name}}</p>
                </div>
            </td>
            <td>R$ {{$product['item']->price}}</td>
            <td>
                <a href="{{route('decrement.cart', $product['item']->id)}}" class="item-add-remove">-</a>
                {{$product['qtd']}}
                <a href="{{route('add.cart', $product['item']->id)}}" class="item-add-remove">+</a>
            </td>
            <td>R$ {{$product['qtd'] * $product['item']->price}}</td>
        </tr>
        @empty
        <tr>
            <td colspan="20">Carrinho Vazio!</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="total-cart">
    <p><strong>Total: </strong> R$ {{$total}}</p>
</div>
<!--se existir o carrinho ou item maior ou igual a 1 então aparece o botão señ ñ aparece-->
@if( Session::has('cart') && Session::get('cart')->totalItems() >= 1 )
<div class="cart-finish">
    <!--redireciono para rota de paypal-->
    <a href="{{ route('paypal')}}" class="btn-finish">Finalizar Compra!</a>
</div>
@endif
@endsection