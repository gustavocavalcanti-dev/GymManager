# GymManager 🏋️

> Sistema de gerenciamento de academia desenvolvido em PHP puro com arquitetura **MVC**, banco de dados **MySQL** e autenticação segura com controle de perfis.

---

## 📋 Funcionalidades

| Módulo | Create | Read | Update | Delete |
|---|:---:|:---:|:---:|:---:|
| Alunos | ✅ | ✅ | ✅ | ✅ |
| Professores | ✅ | ✅ | ✅ | ✅ |
| Planos | ✅ | ✅ | ✅ | ✅ |
| Matrículas | ✅ | ✅ | ✅ | ✅ |
| Pagamentos | ✅ | ✅ | ✅ | ✅ |
| Treinos | ✅ | ✅ | ✅ | ✅ |
| Usuários | ✅ | ✅ | ✅ | ✅ |

**Outros recursos:**
- 🔐 Login / Logout com sessão segura e `session_regenerate_id`
- 🛡️ Proteção CSRF em todos os formulários POST
- 👤 Controle de acesso por perfil: `admin`, `professor`, `recepcionista`
- 🗄️ Conexão com banco via **PDO** (preparada para variáveis de ambiente)
- 📊 Dashboard com totais e resumo financeiro
- 📈 Relatórios consolidados
- ⚙️ Página de configurações do sistema
- 🔒 Senhas armazenadas com `password_hash()` / `bcrypt`

---

## 🏗️ Arquitetura MVC

```
gymmanager/
├── app/
│   ├── Config/         # Configurações (DB, constantes)
│   ├── Controllers/    # Lógica de negócio (11 controllers)
│   ├── Core/           # Framework base (App, Router, Model, Controller, Database)
│   ├── Helpers/        # Utilitários (Security, Formatter, UploadHelper)
│   ├── Middleware/     # Controle de acesso (Auth, Admin, Professor, Recepcionista)
│   ├── Models/         # Camada de dados com PDO (8 models)
│   ├── Routes/         # Definição de rotas (web.php)
│   └── Views/          # Templates HTML (12 módulos)
├── database/
│   └── schema.sql      # Script completo de criação do banco
├── public/             # Document root público (index.php + assets)
├── storage/            # Uploads e logs
├── .env.example        # Template de variáveis de ambiente
└── .htaccess           # Reescrita de URL (Apache)
```

---

## ⚙️ Requisitos

- **PHP** 8.1 ou superior
- **MySQL** 8.0 / **MariaDB** 10.5 ou superior
- **Apache** 2.4 com `mod_rewrite` habilitado (ou Nginx)
- Extensões PHP: `pdo_mysql`, `mbstring`, `json`, `session`, `openssl`

---

## 🚀 Instalação local (XAMPP)

```bash
# 1. Clone o repositório dentro de htdocs
git clone https://github.com/seu-usuario/gymmanager.git C:/xampp/htdocs/gymmanager

# 2. Importe o banco de dados
#    Abra http://localhost/phpmyadmin
#    Crie o banco: gym_manager
#    Importe: database/schema.sql

# 3. Configure as credenciais
#    Edite app/Config/Database.php com seu host/usuário/senha do XAMPP

# 4. Acesse no navegador
#    http://localhost/gymmanager
```

**Credencial padrão de administrador:**

| Campo | Valor |
|---|---|
| E-mail | `admin@gymmanager.com` |
| Senha | `admin123` |

> ⚠️ Troque a senha do admin imediatamente após o primeiro acesso em produção.

---

## 🌐 Deploy em Produção (Linux/Apache)

Consulte o guia completo em [`DEPLOY.md`](DEPLOY.md).

**Resumo rápido:**

```bash
# No servidor
git clone https://github.com/seu-usuario/gymmanager.git /var/www/gymmanager
cd /var/www/gymmanager

# Configure variáveis de ambiente
cp .env.example .env
nano .env   # preencha DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD

# Importe o banco
mysql -u root -p gym_manager < database/schema.sql

# Ajuste permissões
chown -R www-data:www-data /var/www/gymmanager/storage
chmod -R 775 /var/www/gymmanager/storage
```

---

## 🔐 Perfis de Usuário

| Perfil | Acesso |
|---|---|
| `admin` | Acesso total (usuários, configurações, relatórios, todos os módulos) |
| `recepcionista` | Alunos, Planos, Matrículas, Pagamentos |
| `professor` | Visualização de alunos e treinos; cadastro de treinos |

---

## 🗃️ Banco de Dados

O schema completo está em [`database/schema.sql`](database/schema.sql).

**Entidades:**
- `usuarios` – contas de acesso ao sistema
- `professores` – cadastro de professores
- `planos` – planos de assinatura da academia
- `alunos` – cadastro de alunos
- `matriculas` – vínculo aluno ↔ plano com datas e status
- `pagamentos` – registro de pagamentos por matrícula
- `treinos` – fichas de treino vinculadas a aluno e professor

---

## 🛡️ Segurança

- Senhas protegidas com `bcrypt` via `password_hash()` / `password_verify()`
- Proteção contra CSRF em todos os formulários
- Regeneração de ID de sessão no login
- Destruição segura de sessão no logout
- Prepared statements PDO em todas as queries (anti SQL Injection)
- Middleware de controle de acesso por rota e perfil
- Mensagens de erro de banco mascaradas em produção

---

## 👥 Equipe

| Nome | Função |
|---|---|
| *Adicione os integrantes aqui* | Desenvolvimento |

---

## 📄 Licença

Este projeto foi desenvolvido para fins acadêmicos.
