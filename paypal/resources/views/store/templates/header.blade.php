<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
        <a class="navbar-brand" href="{{route('home')}}">
            <img src="{{url('assets/imgs/logo-especializati.png')}}" alt="EspecializaTi" class="logo">
        </a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      
      <ul class="nav navbar-nav navbar-right">
        <li>
            <a href="{{route('cart')}}">
                Meu Carrinho <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                <span class="badge">
                    @if( Session::has('cart') )
                        {{Session::get('cart')->totalItems()}}
                    @else
                        0
                    @endif
                </span>
            </a>
        </li>
        <!--verificando se o usu está logado se tiver mostrar essa opção senão mostra a outra-->
        @if( auth()->check() )
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            <!--mostra o nome do usu-->
              {{auth()->user()->name}}
              <span class="caret"></span>
          </a>
          <ul class="dropdown-menu">
            <li><a href="{{route('user.profile')}}">Perfil</a></li>
            <li><a href="{{route('user.password')}}">Alterar Senha</a></li>
            <li><a href="{{route('orders')}}">Meus Pedidos</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="{{route('user.logout')}}">Sair</a></li>
          </ul>
        </li>
        @else
        <li>
            <a href="{{route('login')}}">Entrar</a>
        </li>
        @endif
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>