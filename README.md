# SSH_Powershell
Aplicação pronta para realizar comandos powershell em um servidor Windows remotamente a partir de um Servidor Linux.

## Requisitos
* Servidor Windows precisa ter o serviço SSH habilitado
* PHP >= 5.3.3

## Dependencias
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
//Exemplo para opter a lista de todos os usuários
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

