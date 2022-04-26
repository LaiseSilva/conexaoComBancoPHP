<?php
/**************************************************************************************
 * Objetivo: Arquivo responsável pela manipulação de dados de contatos
 *      obs(Este arquivo a ponte entre a view e a model)
 * Autor:
 *  Data: 04/03/2022  11/03/2022  18/03/2022  25/03/2022  01/04/2022   08/04/2022   26/04/2022
 *  Versão: 1.0       2.0         3.0         4.0         5.0          6.0          7.0
****************************************************************************************/

//Todos os tratamentos são feitos através da controller


//Função para receber dados da view e encaminhar para a model(inserir)
function  inserirContato ($dadosContatos, $file)
{
    //Caso não tenha uma imagem ela se mantém null e não da erro
    $nomeFoto = (string) null;

    //Validação para verificar se o objeto está vazio
    if(!empty($dadosContatos))
    {
        //os nomes dados no input no html, se tornam chaves do array e o conteúdo digitado se torna o valor
        //Validação de caixa vazias dos elementos nome, celular e email, pois são obrigatórios no BD
        if(!empty($dadosContatos['txtNome']) && !empty($dadosContatos['txtCelular']) && !empty($dadosContatos['txtEmail']))
        {
            //Validação para identificar se chegou um arquivo de upload 
            if($file != null)
            {
                //Importe da função upload
                require_once('modulo/upload.php');

                //Chama a função de upload
                $nomeFoto = uploadFile($file['fleFoto']);
                
                if(is_array($nomeFoto))
                {
                    //Caso acontece algum erro no processo de upload, a função irá retornar um array com a possível mensagem de erro.
                    //Esse array será retornado para a router e ela íra exibir a mensagem para o usuário
                    return $nomeFoto;
                }

            }

            //Criação do array de dados que será encaminhado a model para inserir no BD
            //importante criar este array conforme as necessidades de manipulação do array
            /*OBS: criar as chaves do array conforme os nomes dos atributos do BD */

            $arrayDados = array(
                "nome"     => $dadosContatos['txtNome'],
                "telefone" => $dadosContatos['txtTelefone'],
                "celular"  => $dadosContatos['txtCelular'],
                "email"    => $dadosContatos['txtEmail'],
                "obs"      => $dadosContatos['txtObs'],
                "foto"     => $nomeFoto
            );

            //Importe do arquivo de modelagem para manipular o BD
            require_once('model/bd/contato.php');

            //Chama a função que fará o insert no BD (está função esta na model)
            if(insertContato($arrayDados))
                return true;
            else
                //retorna o array com uma mensagem e o tipo de erro, melhor forma
                return array('idErro'  => 1,
                             'message' => 'Não foi possível inserir os dados no Banco de dados');

        }
        else
            return array('idErro'  => 2,
                         'message' => 'Existem campos obrigatórios que não foram preenchidos');
    }
}

//Função para receber dados da view e encaminhar para a model(atualizar)
function  atualizarContato ($dadosContatos, $id)
{
    //Validação para verificar se o objeto está vazio
    if(!empty($dadosContatos))
    {
        //Validação de caixa vazias dos elementos nome, celular e email, pois são obrigatórios no BD
        if(!empty($dadosContatos['txtNome']) && !empty($dadosContatos['txtCelular']) && !empty($dadosContatos['txtEmail']))
        {
            //Validação para garantir que o id seja válido
            if(!empty($id) && $id!=0 && is_numeric($id))
            {
                //Criação do array de dados que será encaminhado a model para inserir no BD
                //importante criar este array conforme as necessidades de manipulação do array
                /*OBS: criar as chaves do array conforme os nomes dos atributos do BD */

                $arrayDados = array(
                    "id"       => $id,
                    "nome"     => $dadosContatos['txtNome'],
                    "telefone" => $dadosContatos['txtTelefone'],
                    "celular"  => $dadosContatos['txtCelular'],
                    "email"    => $dadosContatos['txtEmail'],
                    "obs"      => $dadosContatos['txtObs']
                );

                //Importe do arquivo de modelagem para manipular o BD
                require_once('model/bd/contato.php');

                //Chama a função que fará a atualização no BD (está função esta na model)
                if(uptadeContato($arrayDados))
                    return true;
                else
                    //retorna o array com uma mensagem e o tipo de erro, melhor forma
                    return array('idErro'  => 1,
                                 'message' => 'Não foi possível atualizar os dados no Banco de dados'); 
            }else 
                return array ('idErro'  => 3,
                              'message' => 'Não é possível atualizar um registro sem informar um id  válido');

        }else
            return array('idErro'  => 2,
                         'message' => 'Existem campos obrigatórios que não foram preenchidos');
        
    }   
}

//Função para realizaar a exclusão de um contato
function  excluirContato ($id)
{

    //Validação para verificar se id contém um número válido
    if($id != 0 && !empty($id) && is_numeric($id))
    {
        //Import arquivo de contato
        require_once('model/bd/contato.php');

        //Chama a função da model e valida se o retorno foi verdadeiro ou falso
        if (deleteContato($id))
            return true;
        else
            return array ('idErro'  => 3,
                          'message' => 'O banco de dados não pode excluir o registro');
    }else
        return array ('idErro'  => 3,
                      'message' => 'Não é possível excluir um registro sem informar um id  válido');
}

//Função para solicitar os dados da model e encaminhar a lista de contatos para a view
function  listarContato ()
{
    //Importe do arquivo que vai buscar os dados no BD
    require_once('model/bd/contato.php');

    //Chama a função que vai buscar os dados no BD
    $dados = selectAllContatos();

    if(!empty($dados))
        return $dados;
    else
        return $false;
}

//Função para buscar um contato através do id do registro
function buscarContato($id)
{
        //Validação para verificar se id contém um número válido
        if($id != 0 && !empty($id) && is_numeric($id))
        {
            //Import arquivo de contato
            require_once('model/bd/contato.php');

            //Chama a função na model que vai buscar no BD
            $dados = selectByIdContato($id);

            //Valida se existem dados para serem devolvidos
            if(!empty($dados))
                return $dados;
            else
                return false;
        }else
            return array ('idErro'     => 4,
                          'message'    => 'Não é possível buscar um registro sem informar um id válido');

}
