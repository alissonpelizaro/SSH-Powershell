# SSH_Powershell
Aplicação em PHP pronta para realizar comandos powershell em um servidor Windows remotamente a partir de um Servidor Linux.

## Requisitos
* Servidor Windows precisa ter o serviço SSH habilitado
* PHP >= 5.3.3

## Instalação
```
composer require alissonpelizaro/ssh_powershell
```

## Dependências
* phpseclib/phpseclib >=  2.0.21

## Executar em CLI
(parâmetro "-d" habilita DEBUG-MODE)
```
php service.php -d
```

## Executar em browser

```php
require __DIR__.'/core.php';

$host = "host_remoto";
$user = "usuario";
$pass = "senha";

$ssh = new SSH_Conn($host, $user, $pass, $debug);
$powershell = new PowerShell($ssh);
```

## Exemplos de comandos
```php
//Exemplo para obter a lista de todos os usuários
print_r($powershell->getUsers());

//Exemplo para procurar um usuário especifico
print_r($powershell->getUser('alisson'));

//Exemplo para procurar um usuário
print_r($powershell->searchUser('HomePhone', '4130305525'));

//Exemplo para resetar a senha de um usuário
$powershell->resetPassword('alisson', 'novaSenha123');

//Exemplo para executar qualquer comando powershell
$powershell->exec('powershell Set-ADUser -Identity alisson -PasswordNeverExpires $true');
```

## Utilização de LOG
Por permitir ser executado em CLI e em alguns casos de forma não assistida, a melhor forma de monitoração é o LOG. A aplicação já salva todos os comandos em LOG no arquivo `ssh.log`. Para setar um log adicional basta chamar o seguinte método estático:

`Log::create('Descrição do log', {true para log de erro}, {true para matar a aplicação após regitro});`

Exemplos:
```php
//Grava LOG como informativo
Log::create('Processo executado com sucesso');

//Grava LOG como erro
Log::create('Erro ao executar comando', true);

//Grava LOG como erro e mata a aplicação
Log::create('Erro ao executar o comando', true, true);

//Grava LOG como informativo e mata a aplicação
Log::create('Comando executado', false, true);
```

## Comandos PowerShell

### getUsers()
Trás um array com todos os usuários do servidor.
```php
$users = $powershell->getUsers();
```

### searchUser()
Procura usuários com base nos filtros passados
```php
$users = $powershell->searchUser('HomePhone', '554130304545');
```

### getUser()
Pega dados de um usuário específico de acordo com seu CN
```php
$user = $powershell->getUser('alisson');
```

### exec()
Executa qualquer comando powershell passado como parâmetro
```php
$comando = $powershell->exec('powershell Get-ADuser joao.silva -properties *');
```

### resetPassword()
Troca a senha de acesso de um usuário
```php
$powershell->resetPassword('alisson', 'nova$enha123');
```

### askNewPassword()
Define pedido de nova senha no próximo logon (verdadeiro ou falso)
```php
$powershell->askNewPassword('alisson', true);
```

### setExpiredPass()
Seta a configuração "Senha nunca expira"  de um usuário (verdadeiro ou falso)
```php
$powershell->setExpiredPass('alisson', true);
```
