<?php

    //Importe do arquivo autoload, que fará as instancias do slim
    require_once('vendor/autoload.php');

    //Criando um objeto do slim chamado app, para configurar os EndPoint
    $app = new \Slim\App();

    //Endpoint: Requisição para listar todos os contatos
    $app-> get('/contatos', function($request, $response, $args){
        $response->write('testando a api pelo get');
    });

     //Endpoint: Requisição para listar o contato por id
    $app-> get('/contatos/{$id}', function($request, $response, $args){

    });

?>