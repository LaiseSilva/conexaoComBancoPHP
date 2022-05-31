<?php

/***********************************************************
 * $request: recebe dados do corpo da requisição(json, form/data,xml,etc)
 * $response: envia dados de retorno da api
 * $args: permite receber dados de atributos na api
 * 
 * Os metodos de requisição para uma API
 * GET       - buscar dados
 * POST      - inserir um novo dado
 * DELETE    - para apagar dados
 * PUT/PATCH - para editar um dado já existente. PUT mais utilizado 
 ***********************************************************/

//Importe do arquivo autoload, que fará as instancias do slim
require_once('vendor/autoload.php');

//Criando um objeto do slim chamado app, para configurar os EndPoint
$app = new \Slim\App();

//Endpoint: Requisição para listar todos os contatos
$app->get('/contatos', function ($request, $response, $args) {
    //importa do arquivo de configuração
    require_once('../modulo/config.php');
    //importe da controller de contatos, que fará a busca de dados
    require_once('../controller/controllerContatos.php');

    //Solicita os dados para a controller
    if ($dados = listarContato()) {
        //realiza a conversão do array de dados em formato json
        if ($dadosJSON = createJSON($dados)) {
            //caso exista dados, retornamos o status code e enviamos os dados em json
            return $response->withStatus(200)
                ->withHeader('Content-Type', 'application/json')
                ->write($dadosJSON);
        }
    } else {
        //retorna um status code caso a solicitação dê errado
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json')
            ->write('{"id-erro": "404", "message": "Não foi possivel encontrar registros."}');
    }
});

//Endpoint: Requisição para listar o contato por id
$app->get('/contatos/{id}', function ($request, $response, $args) {
    //recebe o id do registro que deverá ser retornado pela api, ele está chegando pela váriavel criada no endpoint
    $id = $args['id'];

    //importa do arquivo de configuração
    require_once('../modulo/config.php');
    //importe da controller de contatos, que fará a busca de dados
    require_once('../controller/controllerContatos.php');

    //Solicita os dados para a controller
    if ($dados = buscarContato($id)) {

        //Verifica se houve algum tipo de erro dos dados da controller
        if (!isset($dados['idErro'])) {
            //realiza a conversão do array de dados em formato json
            if ($dadosJSON = createJSON($dados)) {
                //caso exista dados, retornamos o status code e enviamos os dados em json
                return $response->withStatus(200)
                    ->withHeader('Content-Type', 'application/json')
                    ->write($dadosJSON);
            }
        } else {

            //Converte para JSON o erro, pois a controller retorna um array
            $dadosJSON = createJSON($dados);

            //Retorna um erro que significa que o cliente passo dados errados
            return $response->withStatus(404)
                ->withHeader('Content-Type', 'application/json')
                ->write('{"message": "Dados inválidos",
                                       "Erro": ' . $dadosJSON . '}');
        }
    } else {
        //retorna um status code caso a solicitação dê errado
        return $response->withStatus(204);
    }
});

//endpoint: requisição para deletar um contato pelo id
$app->delete('/contatos/{id}', function ($request, $response, $args) {

    if (is_numeric($args['id'])) {
        //Recebe o id enviado no enpoint através da váriavel id 
        $id = $args['id'];
        //importa do arquivo de configuração
        require_once('../modulo/config.php');
        //importe da controller de contatos, que fará a busca de dados
        require_once('../controller/controllerContatos.php');

        //busca o nome da foto para ser excluída na controller
        if ($dados = buscarContato($id)) {
            //Recebe o nome da foto que a controller retornou
            $foto = $dados['foto'];

            //Cria um array com o id e nome da foto a ser enviado para controller para excluir o registro
            $arrayDados = array(
                "id"   => $id,
                "foto" => $foto
            );

            $resposta = excluirContato($arrayDados);
            //chama a função de excluir contato, encaminhando o id e a foto
            if (is_bool($resposta) && $resposta == true) {
                return $response->withStatus(200)
                    ->withHeader('Content-Type', 'application/json')
                    ->write('{"message": "Registro excluído com sucesso!"}');
            } elseif (is_array($resposta) && isset($resposta['idErro'])) {

                //Validação referente ao erro 5,  que significa o registro foi excluído do BD e a imagem não existia no servidor
                if ($resposta['idErro'] == 5) {
                    return $response->withStatus(200)
                        ->withHeader('Content-Type', 'application/json')
                        ->write('{"message": "Registro excluído com sucesso, porém houve um problema na exclusão da imagem na pasta do servidor"}');
                } else {
                    //Converte para JSON o erro, pois a controller retorna um array
                    $dadosJSON = createJSON($resposta);

                    return $response->withStatus(404)
                        ->withHeader('Content-Type', 'application/json')
                        ->write('{"message": "Houve um problema no processo de excluir",
                                              "Erro": ' . $dadosJSON . '}');
                }
            }
        } else {
            //Retorna que significa que o cliente informou um id inválido
            return $response->withStatus(404)
                ->withHeader('Content-Type', 'application/json')
                ->write('{"message": "O id informado não existe na base de dados"}');
        }
    } else {
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json')
            ->write('{"message": "É obrigatório informa um id com formato válido (número)"}');
    }
});

//endpoint: requisição para inserir um novo contato
$app->post('/contatos', function ($request, $response, $args) {
    //Recebe do header da requisição qual será o content-type
    $contentTypeHeader = $request->getHeaderLine('Content-Type');

    //Cria um array, pois depenedendo do content-type temos mais informações separadas pelo ";"
    $contentType = explode(";", $contentTypeHeader);

    switch ($contentType[0]) {
        case 'multipart/form-data':

            //Recebe os dados comuns enviados pelo corpo da requisição
            $dadosBody = $request->getParsedBody();

            //Recebe uma iagem enviada pelo corpo da requisição
            $uploadFiles = $request->getUploadedFiles();

            //Cria um array com todos os dados que chegaram pela requisição devido aos dados serem protegidos,
            //Criamos um array e recuperamos os dados pelos metódos do objeto
            $arrayFoto = array(
                "name"     => $uploadFiles['foto']->getClientFileName(),
                "type"     => $uploadFiles['foto']->getClientMediaType(),
                "size"     => $uploadFiles['foto']->getSize(),
                "tmp_name" => $uploadFiles['foto']->file
            );

            //Cria uma chave chamada "foto" para colocar todos os dados do objeto conforme gerado em form HTML 
            $file = array("foto" => $arrayFoto);

            //Cria um array com todos os dados(comum, arquivo), que será enviada pera o servidor
            $arrayDados = array(
                $dadosBody,
                "file" => $file
            );

            //importa do arquivo de configuração
            require_once('../modulo/config.php');
            //importe da controller de contatos, que fará a busca de dados
            require_once('../controller/controllerContatos.php');

            //Chama a função da controller para inserir os dados
            $resposta = inserirContato($arrayDados);

            if (is_bool($resposta) && $resposta == true) {
                return $response->withStatus(201)
                    ->withHeader('Content-Type', 'application/json')
                    ->write('{"message":"Registro inserido com sucesso" }');
            } elseif (is_array($resposta) && $resposta['idErro']) {
                //Converte para JSON o erro, pois a controller retorna um array
                $dadosJSON = createJSON($resposta);

                return $response->withStatus(400)
                    ->withHeader('Content-Type', 'application/json')
                    ->write('{"message": "Houve um problema no processo de inserir",
                                                    "Erro": ' . $dadosJSON . '}');
            }
            break;
        case 'application/json':
            return $response->withStatus(200)
                ->withHeader('Content-Type', 'application/json')
                ->write('{"message":"Formato selecionado JSON" }');
            break;

        default:
            return $response->withStatus(400)
                ->withHeader('Content-Type', 'application/json')
                ->write('{"message":"Formato do Content-Type não é válido" }');
            break;
    }
});

//endpoint: requisição para alterar um contato
//Simulando o put, pois ele tem um bug pelo slim para fazer o upload da imagem
$app->post('/contatos/{id}', function ($request, $response, $args) {

    if (is_numeric($args['id'])) {

        //Recebe o id enviado no enpoint através da váriavel id 
        $id = $args['id'];

        //Recebe do header da requisição qual será o content-type
        $contentTypeHeader = $request->getHeaderLine('Content-Type');

        //Cria um array, pois depenedendo do content-type temos mais informações separadas pelo ";"
        $contentType = explode(";", $contentTypeHeader);

        switch ($contentType[0]) {
            case 'multipart/form-data':

                //importa do arquivo de configuração
                require_once('../modulo/config.php');
                //importe da controller de contatos, que fará a busca de dados
                require_once('../controller/controllerContatos.php');

                //chama a função para buscar a foto que já está salva no banco de dados
                if ($dadosContatos = buscarContato($id)) {
                    $fotoAtual = $dadosContatos['foto'];

                    //Recebe os dados comuns enviados pelo corpo da requisição
                    $dadosBody = $request->getParsedBody();

                    //Recebe uma iagem enviada pelo corpo da requisição
                    $uploadFiles = $request->getUploadedFiles();

                    //Cria um array com todos os dados que chegaram pela requisição devido aos dados serem protegidos,
                    //Criamos um array e recuperamos os dados pelos metódos do objeto
                    $arrayFoto = array(
                        "name"     => $uploadFiles['foto']->getClientFileName(),
                        "type"     => $uploadFiles['foto']->getClientMediaType(),
                        "size"     => $uploadFiles['foto']->getSize(),
                        "tmp_name" => $uploadFiles['foto']->file
                    );

                    //Cria uma chave chamada "foto" para colocar todos os dados do objeto conforme gerado em form HTML 
                    $file = array("foto" => $arrayFoto);

                    //Cria um array com todos os dados(comum, arquivo), que será enviada pera o servidor
                    $arrayDados = array(
                        $dadosBody,
                        "file" => $file,
                        "id"   => $id,
                        "foto" => $fotoAtual
                    );
                    
                    //Chama a função da controller para inserir os dados
                    $resposta = atualizarContato($arrayDados);

                    if (is_bool($resposta) && $resposta == true) {
                        return $response->withStatus(200)
                            ->withHeader('Content-Type', 'application/json')
                            ->write('{"message":"Registro atualizado com sucesso" }');
                    } elseif (is_array($resposta) && $resposta['idErro']) {
                        //Converte para JSON o erro, pois a controller retorna um array
                        $dadosJSON = createJSON($resposta);

                        return $response->withStatus(400)
                            ->withHeader('Content-Type', 'application/json')
                            ->write('{"message": "Houve um problema no processo de atualizar",
                                                        "Erro": ' . $dadosJSON . '}');
                    }else{
                        
                    }
                } else {
                    //Retorna que significa que o cliente informou um id inválido
                    return $response->withStatus(404)
                        ->withHeader('Content-Type', 'application/json')
                        ->write('{"message": "O id informado não existe na base de dados"}');
                }
                break;
            case 'application/json':
                return $response->withStatus(200)
                    ->withHeader('Content-Type', 'application/json')
                    ->write('{"message":"Formato selecionado JSON" }');
                break;

            default:
                return $response->withStatus(400)
                    ->withHeader('Content-Type', 'application/json')
                    ->write('{"message":"Formato do Content-Type não é válido" }');
                break;
        }
    } else {
        //retorna um erro que significa que o cliente passou dados errados 
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json')
            ->write('{"message": "É obrigatório informa um id com formato válido (número)"}');
    }
});


//executa todos os endpoints
$app->run();
