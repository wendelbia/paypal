@extends('store.templates.master')

@section('content')

<h1 class="title">Meu Perfil:</h1>
<!--mensagem de confirme sucesso-->
@if( session('success') )
<div class="alert alert-success">
    {{session('success')}}
</div>
@endif

@if( isset($errors) && count($errors) > 0  )
    <div class="alert alert-warning">
        @foreach($errors->all() as $error)
            <p>{{$error}}</p>
        @endforeach
    </div>
@endif

<form class="form" action="{{route('update.profile')}}" method="post">
    <!--gerando o token-->
    {!! csrf_field() !!}
    
    <div class="form-group">
        <label>Nome:</label>
        <input type="text" name="name" placeholder="Meu Nome" class="form-control" value="{{auth()->user()->name}}">
    </div>

    <div class="form-group">
        <label>E-mail:</label>
        <input type="email" name="email" placeholder="Meu e-mail" disabled="disabled"  class="form-control" value="{{auth()->user()->email}}">
    </div>

    <div class="form-group">
        <input type="submit" class="btn btn-default">
    </div>

</form>

@endsection