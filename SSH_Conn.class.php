<?php

/*
* Classe de manipulação de comandos via SSH
* Autor: Alisson Pelizaro
*/
class SSH_Conn {

  private $ssh;

  function __construct($host, $user, $key, $debug = false){

    Log::setDebugMode($debug);
    Log::create('Nova chamada');

    $this->ssh =  new phpseclib\Net\SSH2($host);
    $this->login($user, $key);
  }

  /*
  * Executa a autenticação no servidor remoto
  */
  public function login($user, $key){
    if($this->ssh->login($user, $key)){
      return Log::create('Autenticado com sucesso');
    }
    Log::create('Falha na autenticação', true, true);
  }

  /*
  * Executa um comando no servidor remoto
  */
  public function command($cmd){
    if($this->ssh){
      Log::create("Executou comando (".$cmd.")");
      return $this->ssh->exec($cmd);
    }
    return false;
  }

}
?>
