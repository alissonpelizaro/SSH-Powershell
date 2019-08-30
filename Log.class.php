<?php

/**
* Classe construtora de LOG
* Autor: Alisson Pelizaro
*/
class Log {

  private static $debug;

  /*
  * Gera um conteudo de log
  */
  public static function create($content, $err = false, $kill = false){
    $content = preg_replace("/\r?\n/","", $content);
    $cat = $err ? 'error' : 'info';
    $content = "[".strtoupper($cat)."][".date('Y-m-d H:i:s')."]: ".$content."\n";
    if(Log::$debug){
      echo $content;
    }
    Log::filePut($content);

    if($kill) {
      Log::create('Processo encerrado');
      die;
    }

  }

  /*
  * Seta o serviço para rodar em modo de DEBUG ou não
  */
  public static function setDebugMode($mode){
    Log::$debug = $mode;
  }

  /*
  * Método para gravar um conteudo no arquivo de LOG
  */
  private static function filePut($content){
    $file = fopen('ssh.log', 'a');
    fwrite($file, $content);
    fclose($file);
  }

}


?>
