@extends('adminlte::page')
<!--#30 dev a blade #31 após fazer a vamos na model Historic para cria uma mutation q formata a data-->
@section('title', 'Histórico')

@section('content_header')
    <h1>Histórico</h1>

    <ol class="breadcrumb">
   	<li><a href="">Dashboard</a></li>
    	<li><a href="">Saldo</a></li>
        <li><a href="">Transferência</a></li>
    </ol>
@stop

@section('content')
    <div class="box">
    	<div class="box-header">
            <!--crio o método de pesquisa no controller-->
            <form action="{{ route('historic.search') }}" method="POST" class="form form-inline">
                {!! csrf_field() !!}
                <input type="text" name="id" class="form-control" placeholder="id">
                <input type="date" name="date" class="form-control" placeholder="data">

                <select name="type" class="form-control">
                    <option value="">-- Selecione o tipo --</option>
                    <!--#33 através do método type() da model Historic busco esses valores e faço a rota em web.php-->
                    @foreach ($types as $key => $type)
                        <option value="{{ $key }}">{{ $type }}</option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-primary">Pesquisar</button>
            </form>
    	</div>
    	<div class="box-body">
    		<table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Valor</th>
                        <th>Tipo</th>
                        <th>Data</th>
                        <th>?Sender?</th>
                    </tr>
                </thead>      
                <tbody>
                    @forelse($historics as $historic)
                    <tr>
                        <td>{{ $historic->id }}</td>
                        <td>{{ number_format($historic->amount, 2, ',', '.')}}</td>

                        <!--31 vamos buscar o texto q substitui os tipos <td>{{ $historic->type }}</td>-->

                        <td>{{ $historic->type($historic->type) }}</td>
                        <td>{{ $historic->date }}</td>

                        <!--31 vou na model para fazer um  relacionamento inverso para buscar usu
                        <td>{{ $historic->user_id_transaction }}</td>-->
                        <td>
                            <!--31 buscar o nome do recebedor, esse modelo funciona mas é desgastante pois faz uma busca no banco dentro do próprio looping, isso irá desgastar por isso vou no BalanceController e em historics dou um dd para ver a diferença-->

                            @if ($historic->user_id_transaction)
                            
                            <!--31 com a mudança da query na model historic mudo de:
                                {{$historic->user()->get()->first()->name}} para:
                            -->
                                {{ $historic->userSender->name }}
                            <!--31 q deixará a consulta muito mais leve #32 agora páginar esses dados no balanceController-->

                            @else

                            @endif  
                        </td>
                    </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
            <!--32 chama a paginação 
               #33 agora vamos fazer a filtragem no header coloco o form
               {!! $historics->links() !!} -->
            <!--35 o filtro é perdido pois a pesquisa é em post e o recebimento é em get para q não se perca o filtro quando passo de pág eu uso appends, vou na rota e mudo de post para any que aceita qualquer requisição, mas vejo que na url não fica amigável, então vou no BalanceController e no $dataForm modifico verifico se existe essa var se existe chomo a appends #-->
            <!--36 isso trais no filtro todos os dados dos usu, mas agora vamos amarrá-los para trazer panes do usu logado, para isso pode ser usa um join ou amarrar o auth do próprio usu logado, vamos na model Historic em search-->
            @if (isset($dataForm))
                    {!! $historics->appends($dataForm)->links() !!}
            @else
                    {!! $historics->links() !!}
            @endif
    	</div>
    </div>
@stop