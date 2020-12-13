@extends('adminlte::page')
<!--title se eu tiro fica o título padrão-->
@section('title', 'Saldo')

@section('content_header')
    <h1>Saldo</h1>

    <ol class="breadcrumb">
    	<li><a href="">Dashboard</a></li>
    	<li><a href="">Saldo</a></li>
    </ol>
@stop

@section('content')
<!--#16 crio a rota
    <p>Meu saldo</p> e depois de feito isso vamos exibir o saldo #17 vamos na model User implementar o método balance -->
    <div class="box">
    	<div class="box-header">
    		<!--#18 atualizo o link de Recarga e vamos no arquivo de rotas-->
    		<a href="{{ route('balance.deposit')}}" class="btn btn-primary"><i class="fa fa-cart-plus" aria-hidden></i>Recarregar</a>

            <!--#25 verifico se tem algum saldo, señ tem não aparece o butão -->
    		
            @if($amount > 0)
            <!--#25 vou na routes/web e faço então a rota-->
                <a href="{{ route('balance.withdraw') }}" class="btn btn-danger"><i class="fa fa-cart-arrow-down" aria-hidden></i> Sacar</a>
            @endif

            <!--#27 faço o icon-->
            @if($amount > 0)
            <!--#27 vou na routes/web e faço então a rota-->
                <a href="{{ route('balance.transfer') }}" class="btn btn-info"><i class="fas fa-exchange-alt" aria-hidden></i> Transferência</a>
            @endif

    	</div>
    	<div class="box-body">
            <!--#24 incluo o alert.blade.php e depois em balande/index.blade.php agora dev o saque no link a cima#-->

            @include('admin.includes.alerts')
    		<!--#10 copio o tamplate do https://adminlte.io no dashboar v1 clico em inspecionar e copio-->

    		<div class="small-box bg-green">
            <div class="inner">
            <!--#17 insiro o $amount e atualizo o link para recarga**-->
              <h3>R$ {{ number_format($amount, 2, ',', '')}}</h3>

              <p>Bounce Rate</p>
            </div>
            <div class="icon">
              <i class="ion ion-cash"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
    	</div>
    </div>
@stop
