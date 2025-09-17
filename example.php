<?php

/*
* Arquivo de exemplo para execução em CLI
*/

require __DIR__.'/core.php'

$host = "host_remoto";
$user = "usuario";
$pass = "senha";


/*
* Possibilidade de executar em CLI com o
* parametro "-d" para ligar o modo DEBUG */
$debug = isset($argv[1]) && strtolower($argv[1]) == '-d' ? true : false;

$ssh = new SSH_Conn($host, $user, $pass, $debug);
$powershell = new PowerShell($ssh);

//Exemplo para opter a lista de todos os usuários
print_r($powershell->getUsers());

//Exemplo para procurar um usuário especifico
print_r($powershell->getUser('dev'));

//Exemplo para procurar um usuário
print_r($powershell->searchUser('HomePhone', '4130305525'));

//Exemplo para resetar a senha de um usuário
$powershell->resetPassword('diego', 'novaSenha123');


Log::create('Processo executado com sucesso');

?>
