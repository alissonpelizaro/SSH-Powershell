# SSH_Powershell
Aplicação pronta para realizar comando powershel em um servidor Windows remotamente a partir de um Linux

## Executar em CLI (parametro "-d" habilita DEBUDE-MODE)
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

A aplicação já salva todos os comandos em LOG no arquivo `ssh.log`. Para setar um log adicional basta chamar o seguinte método estatico:

`Log::create('Descricao do log', {true para log de erro}, {true para matar a aplicação após regitro});`

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

