@extends('adminlte::page')
<!--title se eu tiro fica o título padrão-->
@section('title', 'Transferência')

@section('content_header')
    <h1>Transferência</h1>

    <ol class="breadcrumb">
    	<li><a href="">Dashboard</a></li>
    	<li><a href="">Saldo</a></li>
        <li><a href="">Transferir</a></li>
    </ol>
@stop

@section('content')
    <div class="box">
    	<div class="box-header">
    		<h3>Transferir Saldo (Informe o Recebedor)</h3>
    	</div>
    	<div class="box-body">
    		<!--#27 incluo o alert.blade.php e depois em balande/index.blade.php-->
    		@include('admin.includes.alerts')
    		<!--#27 faço o formulário uso o post e faço a rota-->
    		<form method="POST" action="{{ route('confirm.transfer')}}">
    			{!! csrf_field() !!}
    			<div class="form-group">
    				<input name="sender" type="text" placeholder="Informação de quem vai receber a transferência (Nome ou E-mail)" class="form-control">
    			</div>
    			<div class="form-group">
    				<button type="submit" class="btn btn-success">Próxima Etapa</button>
    			</div>
    		</form>
    	</div>
    </div>
@stop