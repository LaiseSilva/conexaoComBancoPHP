<?php
    /**********************************************************************************************************
     * 
     * Objetivo: Arquivo de rota, para segementar as ações encaminhadas pela View
     *           view: (dados de um form,listagem de dados, ação de excluir ou atualizar)
     *           Esse arquivo será responsável  para encaminhar as solicitações para a Controller
     * Autor: Laise junto com o professor Marcel
     * Data: 04/03/2022   11/03/2022
     * Versão: 1.0        2.0
     * 
     ***********************************************************************************************************/

     $action = (string) null; // a ação que irá acontecer
     $component = (string) null; // quem pediu a ação


    //Validação para verificar se a requisição é um POST de um formulário
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        //Recebendo dados via URL para saber quem está solicitando e qual ação será realizada
        $component = strtoupper($_GET['component']);
        $action = strtoupper($_GET['action']);

        //Estrutura condicional para validar quem está solicitando algo para o router
        switch ($component)
        {
            case 'CONTATOS':
                //Importe da controller contatos
                require_once('controller/controllerContatos.php');
        
                if($action == 'INSERIR')
                    inserirContato($_POST);           
                break;
        }

    }


?>