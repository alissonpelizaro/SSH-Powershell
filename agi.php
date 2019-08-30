#!/usr/bin/php
<?php

require __DIR__.'/ssh_reset/core.php';
require __DIR__.'/phpagi.php';

$host = "172.16.100.140";
$user = "dev";
$pass = "enter@cbs";

$agi=new AGI();
$ssh = new SSH_Conn($host, $user, $pass, false);
$powershell = new PowerShell($ssh);

/*
ARGV[1] = Numero digitado
ARGV[2] = Stage do usuario
ARGV[3] = Nome do usuário no AD (a partir do stage 2)
ARGV[4] = Loopcount para controle de erros
*/

$_stage = $argv[2];
if(!$_stage) $_stage = 1;
$agi->set_variable("STAGERESET", $_stage);
$agi->set_variable("RESETRESPONSE", "Estamos com problema nesse serviço no momento. Por favor, tente novamente mais tarde.");

switch ($_stage) {
  case '1':
  // Verificação de matricula
  $argv[1] = limpaString($argv[1);
  $user = $powershell->searchUser('HomePhone', $argv[1]);
  if($user){
    $agi->set_variable("RESETRESPONSE","Localizei sua matrícula. Agora digite o seu C P F.");
    $agi->set_variable("RESETUSER", $user->CN);
    $agi->set_variable("STAGERESET", '2');
    $agi->set_variable("loopreset", 1);

  } else {
    $agi->set_variable("RESETRESPONSE","Não encontramos essa matrícula. Digite novamente.");
    $agi->set_variable("loopreset",((int) $argv[4]) + 1);
  }

  // Fim da verificação de matrícula
  break;
  case '2':
  // Verificação do CPF
  $argv[3] = limpaString($argv[3]);
  $user = $powershell->getUser($argv[3]);
  if($user && (int) $argv[1] == (int) $user->HomePhone){
    $agi->set_variable("RESETRESPONSE","Certo. Digite o número 1 para confirmar o reset de sua senha, ou qualquer outro número para cancelar.");
    $agi->set_variable("RESETCPF", $argv[1]);
    $agi->set_variable("STAGERESET", '3');
    $agi->set_variable("loopreset", 1);
  } else {
    $agi->set_variable("RESETRESPONSE","O C P F informado não confere, digite novamente.");
    $agi->set_variable("loopreset",((int) $argv[4]) + 1);
  }

  // Fim da verificação do CPF
  break;

  case '3':
  // Confirmação
  $argv[3] = limpaString($argv[3]);

  if($argv[1] == '1'){
    $senha = rand(100,999);
    $powershell->setExpiredPass($argv[3]);
    $powershell->resetPassword($argv[3], "veracel@".$senha);
    $powershell->askNewPassword($argv[3]);

    $agi->set_variable("RESETRESPONSE","Sua senha foi resetada para: veracel, @");
    $agi->set_variable("RESETRESPONSEKEYS", $senha);
    $agi->set_variable("RESETFINISHED","true");
  } else {
    $agi->set_variable("RESETRESPONSE","A operação foi cancelada");
    $agi->set_variable("RESETRESPONSEKEYS","false");
    $agi->set_variable("RESETFINISHED","true");

  }
  // Fim da verificação do CPF
  break;
}

function limpaString($string){
  return str_replace("\n", "". $string);
}

?>
