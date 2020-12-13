<?php

return [
    
    'client_id' => env('PAYPAL_CLIENT_ID'),
    'secret_id' => env('PAYPAL_SECRET_ID'),
    
    
    'settings' => [
    	//quando estiver em teste uso sandbox quando tiver em produção live
        'mode'                      => 'sandbox',
        //tempo de conexão
        'http.ConnectionTimeOut'    => 30,
        //trabalhar com arquivo de log q fica em config/storage/logs
        'log.LogEnabled'            => true,
        //onde vai ficar o arquivo de log
        'log.FileName'              => storage_path().'/logs/paypal.log',
        //configuração de log, fine dá mais detalhes
        'log.LogLevel'              => 'FINE',
    ]
];





?>