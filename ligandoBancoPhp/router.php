<?php
    /**********************************************************************************************************
     * 
     * Objetivo: Arquivo de rota, para segementar as ações encaminhadas pela View
     *           view: (dados de um form,listagem de dados, ação de excluir ou atualizar)
     *           Esse arquivo será responsável  para encaminhar as solicitações para a Controller
     * Autor: Laise junto com o professor Marcel
     * Data: 04/03/2022   11/03/2022    18/03/2022    25/03/2022
     * Versão: 1.0        2.0           3.0           4.0
     ***********************************************************************************************************/

     $action = (string) null; // a ação que irá acontecer
     $component = (string) null; // quem pediu a ação


    //Validação para verificar se a requisição é um POST de um formulário
    if($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET')
    {
        //Recebendo dados via URL para saber quem está solicitando e qual ação será realizada
        $component = strtoupper($_GET['component']);
        $action = strtoupper($_GET['action']);

        /*echo($component);
        die; força uma parada, o programa chega até ali e pra*/

        //Estrutura condicional para validar quem está solicitando algo para o router
        switch ($component)
        {
            case 'CONTATOS':
                //Importe da controller contatos
                require_once('controller/controllerContatos.php');
                
                //Validação para identificar o tipo de ação que será realizada
                if($action == 'INSERIR')
                {
                    //Chama a função de inserir na controller
                    $resultado = inserirContato($_POST) ;

                    //Valida o tipo de dados que a controller retornou
                    if(is_bool($resultado)) //Se for booleano
                    {
                        //Verifica se o retorno é verdadeiro
                        if($resultado)
                            echo("<script>
                                    alert ('Registro inserido com sucesso!');
                                    window.location.href = 'index.php'; //volta para o caminho indicado
                                  </script>");
                                  
                    //Se o retorno for um array significa houve um erro no processo de inserção
                    }elseif(is_array($resultado)){
                        echo("<script>
                                alert ('".$resultado["message"]."');
                                window.history.back(); //volta para página anterior com os dados recuperados
                            </script>");
                    }
                        
                }elseif($action == 'DELETAR')
                {
                    //Recebe o id do registro que deverá ser excluido, que foi enviado pelo URL
                    // no link da imagem do excluir que foi acionado na index 
                    $idContato = $_GET['id'];

                   $resposta = excluirContato($idContato);

                   if(is_bool($resposta))
                   {
                       if($resposta)
                       {
                            echo("<script>
                                    alert ('Registro excluído com sucesso!');
                                    window.location.href = 'index.php'; //volta para o caminho indicado
                                 </script>");
                       }
                   }elseif(is_array($resposta))
                   {
                    echo("<script>
                            alert ('".$resultado["message"]."');
                            window.history.back(); //volta para página anterior com os dados recuperados
                         </script>");
                   }
                    
                }  
                break;
        }

    }


?>