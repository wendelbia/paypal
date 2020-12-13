@extends('store.templates.master')

@section('content')

<h1 class="title">Atualizar Senha:</h1>

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

<form class="form" action="{{route('update.password')}}" method="post">
    {!! csrf_field() !!}
    
    <div class="form-group">
        <label>Senha:</label>
        <input type="password" name="password" placeholder="Senha" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Confirmar Senha:</label>
        <input type="password" class="form-control" name="password_confirmation" required>
    </div>

    <div class="form-group">
        <input type="submit" class="btn btn-default" value="Atualizar Senha">
    </div>

</form>

@endsection