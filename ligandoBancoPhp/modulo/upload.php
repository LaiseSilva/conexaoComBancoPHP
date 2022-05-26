<?php

 /*************************************************************************
     * Objetivo: Arquivo responsável em realiar uploads de arquivos
     * Autor: Laise na aula junto com o professor Marcel
     * Data:25/04/2022   
     * Versão: 1.0
 **************************************************************************/

 //Função para realizar upload de imagem
 function uploadFile($arrayFile)
 {
     //Importe do arquivo de configurações do projeto
     require_once(SRC .'modulo/config.php');

    $arquivo = $arrayFile;
    $sizeFile = (int) 0;
    $typeFile = (string) null;
    $nameFile = (string) null;
    $tempFile = (string) null;

    //Validação para identificar se existe um arquivo válido (maior que 0 e que tenha uma extensão)
    if($arquivo['size'] > 0 && $arquivo['type'] != "")
    {
        //Recupera o tamanho do arquivo que é em bytes e converte para kb ( /1024)
        $sizeFile = $arquivo['size']/1024;

        //Recupera o tipo do arquivo
        $typeFile = $arquivo['type'];

        //Recupera o nome do arquivo
        $nameFile = $arquivo['name'];

        //Recupera o caminho do diretório temporário que está no arquivo
        $tempFile = $arquivo['tmp_name'];

        //Validação para permitir o upload apenas de arquivos de no máimo 5mb 
        if($sizeFile <= MAX_FILE_UPLOAD)
        {
            //Validação para permitir somente as extensões válidas
            if(in_array($typeFile, EXT_FILE_UPLOAD))
            {
                //Separa somente o nome do arquivo sem a sua extensão
                $nome = pathinfo($nameFile, PATHINFO_FILENAME);

                //Separa somente a extensão do arquivo sem o nome
                $extensao = pathinfo($nameFile, PATHINFO_EXTENSION);

                /*Existem diversos algoritmos para criptografia de dados: são nativos do PHP
                    md5  () é bom mas tem melhores
                    sha1 () dificil
                    hash () quase impossivel de ser descoberta
                */

                //md5 () gerando uma criptografia de dados
                //uniqd gerando uma sequencia númerica diferente tendo como base, configurações da máquina
                //time pega a hora, minuto e segundo que está sendo feito o upload da foto
                $nomeCripty = md5($nome.uniqid(time()));

                //Montamos novamente o nome do arquivo com a extensão
                $foto = $nomeCripty.".".$extensao;

               //Envia o arquivo da pasta temporária do apache para a pasta criada no projeto
               if (move_uploaded_file($tempFile, SRC.DIRETORIO_FILE_UPLOAD.$foto))
               {
                   return $foto;
               }else
               {
                return array ('idErro'     => 13,
                              'message'    => 'Não foi possível mover o arquivo para o servidor');
               }

            }else{
                return array ('idErro'     => 12,
                              'message'    => 'A extensão do arquivo selecionado não é permitida no upload.');
            }

        }else{
            return array ('idErro'     => 10,
                          'message'    => 'Tamanho de arquivo inválido no upload.');
        }


    }else
        return array ('idErro'     => 11,
                      'message'    => 'Não é possível realizar o upload sem um arquivo selecionado.');
 }


?>