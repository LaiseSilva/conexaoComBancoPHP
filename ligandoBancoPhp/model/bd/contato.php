<?php
    /*************************************************************************
     * Objetivo: Arquivo responsável por manipular os dados dentro do BD
     *          (insert,uptade,select e delete)
     * Autor: Laise na aula junto com o professor Marcel
     * Data:11/03/2022
     * Versão: 1.0
    **************************************************************************/

    //Estabelece a conexão com o BD
    require_once('conexaoMysql.php');

    //Função para realizar o insert no BD
    function insertContato($dadosContatos)
    {
        //Abre a conexão com o BD
        $conexao = conexaoMysql();

        //Monta o script para enviar para o BD
        //Obs: números inteiros(int) não precisa de aspas simples
        $sql = "insert into tblcontatos
                    (nome,
                     telefone,
                     celular,
                     email,
                     obs)
                values
                    ('".$dadosContatos['nome']."',
                     '".$dadosContatos['telefone']."',
                     '".$dadosContatos['celular']."',
                     '".$dadosContatos['email']."',
                     '".$dadosContatos['obs']."');";

        //Executa script no BD
        mysqli_query($conexao, $sql);
    }

    

    //Função para realizar o uptade no BD
    function uptadeContato()
    {

    }
    
    //Função para excluir no BD
    function deleteContato()
    {

    }
    
    //Função para listar todos os contatos do BD
    function selectAllContatos()
    {

    }


?>