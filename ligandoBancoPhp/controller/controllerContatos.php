<?php
/**********************************************************************
 * Objetivo: Arquivo responsável pela manipulação de dados de contatos
 *      obs(Este arquivo a ponte entre a view e a model)
 * Autor:
 *  Data: 04/03/22  11/03/2022
 *  Versão: 1.0     2.0
**********************************************************************/

//Todos os tratamentos são feitos através da controller


//Função para receber dados da view e encaminhar para a model(inserir)
function  inserirContato ($dadosContatos)
{
    //Validação para verificar se o objeto está vazio
    if(!empty($dadosContatos))
    {
        //os nomes dados no input no html, se tornam chaves do array e o conteúdo digitado se torna o valor
        //Validação de caixa vazias dos elementos nome, celular e email, pois são obrigatórios no BD
        if(!empty($dadosContatos['txtNome']) && !empty($dadosContatos['txtCelular']) && !empty($dadosContatos['txtEmail']))
        {
            //Criação do array de dados que será encaminhado a model para inserir no BD
            //importante criar este array conforme as necessidades de manipulação do array
            /*OBS: criar as chaves do array conforme os nomes dos atributos do BD */

            $arrayDados = array(
                "nome"     => $dadosContatos['txtNome'],
                "telefone" => $dadosContatos['txtTelefone'],
                "celular"  => $dadosContatos['txtCelular'],
                "email"    => $dadosContatos['txtEmail'],
                "obs"      => $dadosContatos['txtObs']
            );

            require_once('model/bd/contato.php');
            insertContato($arrayDados);

        }
        else
            echo('Dados incompletos');
    }
}

//Função para receber dados da view e encaminhar para a model(atualizar)
function  atualizarContato ()
{

}

//Função para realizaar a exclusão de um contato
function  excluirContato ()
{

}

//Função para solicitar os dados da model e encaminhar a lista de contatos para a view
function  listarContato ()
{

}


?>