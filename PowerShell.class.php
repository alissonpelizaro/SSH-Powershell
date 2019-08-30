<?php

/**
* Classe de possibilidades e comandos Powershell
* Autor: Alisson Pelizaro
*/
class PowerShell {

  private $ssh;
  private $manager;

  function __construct($ssh){
    $this->ssh = $ssh;
    $this->manager = new Manager;
  }

  /*
  * Busca e retorna dados de um usuário do Servidor
  */
  public function searchUser($field, $val){
    $user_data = $this->manager->getUser(
      $this->ssh->command('powershell Get-ADUser -filter {'.$field.' -like "'.$val.'"} -properties *')
    );

    if($user_data){
      return $user_data;
    }

    Log::create('Usuário não encontrado pelos filtros passados ('.$field.', '.$val.')', true);
    return false;
  }

  /*
  * Retorna todos os usuários do Servidor
  */
  public function getUsers(){
    return $this->manager->getUsers(
      $this->ssh->command('powershell Get-ADUser -filter *')
    );
  }

  /*
  * Executa um comando qualquer passado como parametro
  */
  public function exec($cmd){
    return $this->ssh->command($cmd);
  }

  /*
  * Define senha sem expiração
  */
  public function setExpiredPass($user, $stat = false){
    if($stat) $comp = 'true';
    else $comp = 'false';
    return $this->ssh->command('powershell Set-ADUser -Identity '.$user.' -PasswordNeverExpires $'.$comp);
  }

  /*
  * Define a troca da senha no proximo logon
  */
  public function askNewPassword($user, $stat = true){
    if($stat) $comp = 'true';
    else $comp = 'false';

    return $this->ssh->command('powershell Set-ADUser -Identity '.$user.' -ChangePasswordAtLogon $'.$comp);
  }

  /*
  * Troca a senha de um usuário
  */
  public function resetPassword($user, $new_pwd){
    return $this->ssh->command("powershell Set-ADAccountPassword -Identity ".$user." -Reset -NewPassword (ConvertTo-SecureString -AsPlainText '".$new_pwd."' -Force)");
  }

  /*
  * Retorna dados de um usuário do Servidor
  */
  public function getUser($user){
    $user_data = $this->manager->getUser(
      $this->ssh->command('powershell Get-ADuser '.$user.' -properties *')
    );

    if($user_data){
      return $user_data;
    }

    Log::create('Usuário "'.$user.'" não encontrado', true);
    return false;
  }

}

?>
