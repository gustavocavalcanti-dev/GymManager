# Deploy do GymManager no Render

Este projeto usa Docker para publicar PHP 8.2 com Apache no Render. O banco precisa
ser um MySQL externo, pois o Render não fornece MySQL gerenciado nativo para este app.

## 1. Preparar o banco MySQL

Crie um banco MySQL acessível pela internet e importe:

```text
database/schema.sql
```

Guarde estes dados:

```text
DB_HOST=host-do-mysql
DB_PORT=3306
DB_DATABASE=gym_manager
DB_USERNAME=usuario
DB_PASSWORD=senha
DB_CHARSET=utf8mb4
```

O provedor do banco precisa aceitar conexões externas. Se houver whitelist de IP,
configure-a conforme as instruções do provedor.

## 2. Enviar o projeto para o GitHub

O repositório precisa conter estes arquivos:

```text
Dockerfile
render.yaml
public/index.php
app/Config/Database.php
database/schema.sql
```

Não envie `.env` nem senhas no código. As credenciais serão cadastradas como variáveis
secretas no Render.

## 3. Criar o serviço no Render

1. Acesse o painel do Render e escolha **New > Blueprint**.
2. Conecte o repositório do GymManager.
3. Selecione a branch `main`.
4. Confirme o serviço definido em `render.yaml`.
5. Inicie o deploy.

O Render usará o `Dockerfile`, instalará `pdo_mysql` e publicará somente o diretório
`public/`. O health check configurado é `/login`.

## 4. Configurar variáveis

No serviço, abra **Environment** e preencha:

| Variável | Valor |
|---|---|
| `DB_HOST` | Host do MySQL externo |
| `DB_PORT` | `3306` |
| `DB_DATABASE` | `gym_manager` |
| `DB_USERNAME` | Usuário do banco |
| `DB_PASSWORD` | Senha do banco, como secret |
| `DB_CHARSET` | `utf8mb4` |

Salve e faça um novo deploy após alterar variáveis.

## 5. Testar a publicação

Quando o deploy ficar verde, abra:

```text
https://SEU-SERVICO.onrender.com/login
```

Teste:

- Login: `admin@gymmanager.com`
- Senha inicial: `admin123`
- Acesso à página inicial após o login
- Logout
- Redirecionamento de páginas protegidas sem sessão

Troque a senha do administrador imediatamente após o primeiro acesso.

## 6. Solução de problemas

**Health check falha:** confirme que `/login` responde e que o deploy está usando a
branch correta.

**Erro de conexão com banco:** revise `DB_HOST`, porta, usuário, senha e permissão
de conexões externas no provedor MySQL.

**Erro “table doesn't exist”:** importe `database/schema.sql` no banco configurado.

**Login volta para a própria tela:** confirme o banco, o usuário administrador e se
o token CSRF está sendo mantido pelos cookies do navegador.

**Deploy não encontra arquivos:** confirme que `Dockerfile`, `render.yaml` e
`app/Config/Database.php` foram enviados ao GitHub.
