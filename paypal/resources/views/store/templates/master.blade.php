<html>
    <head>
        <meta charset="UTF-8">
        <title>{{$title or 'Curso Laravel PayPal'}}</title>
        
        <!--Bootstrap CSS-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        
        <!--Font Awesome-->
        <link rel="stylesheet" href="{{url('assets/store/css/font-awesome.min.css')}}">
        
        <!--Fonts-->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        
        <!--CSS Person-->
        <link rel="stylesheet" href="{{url('assets/css/especializati.css')}}">
        
        <!--CSS Reset-->
        <link rel="stylesheet" href="{{url('assets/css/reset.css')}}">
        
        <!--Favicon-->
        <link rel="icon" type="image/png" href="{{url('assets/imgs/favicon.png')}}">
    </head>
    <body>
        
        @include('store.templates.header')

        <div class="container">
            @yield('content')
        </div>
        
        <!--jQuery-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        
        <!--Bootstrap-->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        
        <!--JS dinâmicos-->
        @stack('scripts')
    </body>
</html>