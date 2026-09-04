# Deploy simples do GymManager

O GymManager é uma aplicação PHP com MySQL. Para publicar sem Docker, use uma
hospedagem com PHP, Apache e MySQL, como cPanel, Hostinger ou Locaweb.

> O Render não oferece execução nativa de PHP. Sem Docker, este projeto não roda como
> Web Service no Render. Para usar Render seria necessário Docker ou migrar o sistema
> para outra tecnologia.

## 1. Criar o banco

No painel da hospedagem, crie um banco MySQL e um usuário com acesso a ele.
Depois importe o arquivo:

```text
database/schema.sql
```

## 2. Enviar os arquivos

Envie o projeto por Git, FTP ou pelo gerenciador de arquivos da hospedagem.
Configure o domínio para apontar para:

```text
public/
```

O servidor precisa ter:

- PHP 8.1 ou superior
- Apache com `mod_rewrite`
- Extensão PHP `pdo_mysql`
- MySQL 8 ou MariaDB 10.5+

## 3. Configurar o banco

Edite `app/Config/Database.php` com os dados fornecidos pela hospedagem:

```php
return [
    'host'     => 'localhost',
    'port'     => '3306',
    'dbname'   => 'nome_do_banco',
    'user'     => 'usuario_do_banco',
    'password' => 'senha_do_banco',
    'charset'  => 'utf8mb4',
];
```

Não publique esse arquivo com a senha em repositórios públicos.

## 4. Abrir o sistema

Acesse:

```text
https://seu-dominio.com/login
```

Login inicial:

```text
E-mail: admin@gymmanager.com
Senha: admin123
```

Troque a senha imediatamente após o primeiro acesso.

## 5. Verificação final

- A tela `/login` abre sem erro.
- O login leva ao dashboard.
- O logout volta para `/login`.
- Páginas protegidas redirecionam usuários sem sessão.
- Cadastro de aluno e listagens funcionam.
- O banco não apresenta erro de conexão.

Se aparecer erro de conexão, revise host, porta, banco, usuário, senha e a extensão
`pdo_mysql` no painel da hospedagem.
