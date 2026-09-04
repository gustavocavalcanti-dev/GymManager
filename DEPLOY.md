# DEPLOY.md — Guia de Deploy do GymManager

> Instruções passo a passo para publicar o GymManager em ambiente de produção Linux (Apache) ou hospedagem compartilhada (cPanel/DirectAdmin).

---

## 1. Pré-requisitos do Servidor

| Requisito | Versão mínima |
|---|---|
| PHP | 8.1 |
| MySQL / MariaDB | 8.0 / 10.5 |
| Apache | 2.4 (com `mod_rewrite`) |
| Extensões PHP | `pdo_mysql`, `mbstring`, `json`, `session`, `openssl` |

Para verificar no servidor:
```bash
php -v
php -m | grep -E "pdo_mysql|mbstring|json|session|openssl"
mysql --version
apache2 -v
```

---

## 2. Clonar o Repositório

```bash
cd /var/www
git clone https://github.com/seu-usuario/gymmanager.git gymmanager
cd gymmanager
```

---

## 3. Configurar Variáveis de Ambiente

```bash
cp .env.example .env
nano .env
```

Preencha o arquivo `.env`:
```ini
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=gym_manager
DB_USERNAME=seu_usuario_db
DB_PASSWORD=sua_senha_db

APP_ENV=production
APP_DEBUG=false
APP_URL=https://seu-dominio.com
```

> ⚠️ **Nunca versione o arquivo `.env` real. Ele já está no `.gitignore`.**

---

## 4. Criar o Banco de Dados

Conecte ao MySQL e execute:
```bash
mysql -u root -p
```
```sql
CREATE DATABASE gym_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'gymuser'@'localhost' IDENTIFIED BY 'senha-forte-aqui';
GRANT ALL PRIVILEGES ON gym_manager.* TO 'gymuser'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Importe o schema:
```bash
mysql -u gymuser -p gym_manager < /var/www/gymmanager/database/schema.sql
```

> O script `schema.sql` já cria o usuário administrador padrão:
> - **E-mail:** `admin@gymmanager.com`
> - **Senha:** `admin123`
>
> ⚠️ Troque essa senha imediatamente após o primeiro acesso!

---

## 5. Configurar o Apache (Virtual Host)

Crie o arquivo `/etc/apache2/sites-available/gymmanager.conf`:

```apacheconf
<VirtualHost *:80>
    ServerName seu-dominio.com
    ServerAlias www.seu-dominio.com
    DocumentRoot /var/www/gymmanager/public

    <Directory /var/www/gymmanager/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/gymmanager_error.log
    CustomLog ${APACHE_LOG_DIR}/gymmanager_access.log combined
</VirtualHost>
```

Ative o site e reinicie o Apache:
```bash
a2enmod rewrite
a2ensite gymmanager.conf
systemctl reload apache2
```

---

## 6. Permissões de Arquivos

```bash
# Dono dos arquivos para o usuário do Apache
chown -R www-data:www-data /var/www/gymmanager

# Somente o diretório storage precisa de escrita
chmod -R 755 /var/www/gymmanager
chmod -R 775 /var/www/gymmanager/storage

# Proteja o .env
chmod 640 /var/www/gymmanager/.env
```

---

## 7. Ativar HTTPS (Recomendado — Let's Encrypt)

```bash
apt install certbot python3-certbot-apache -y
certbot --apache -d seu-dominio.com -d www.seu-dominio.com
```

O Certbot configura o redirecionamento HTTP → HTTPS automaticamente.

---

## 8. Deploy em Hospedagem Compartilhada (cPanel)

Se o seu servidor for uma hospedagem compartilhada (ex.: HostGator, Locaweb, Hostinger):

1. **Upload dos arquivos**: Faça upload de todo o projeto via FTP ou gerenciador de arquivos do cPanel para `public_html/gymmanager/`.
2. **Document Root**: Configure o domínio apontando para `public_html/gymmanager/public/`.
3. **Banco de dados**: Crie o banco pelo painel MySQL do cPanel, importe o `schema.sql` pelo phpMyAdmin.
4. **Config**: Edite diretamente `app/Config/Database.php` com as credenciais do cPanel (sem suporte a `.env`).
5. **`.htaccess`**: O arquivo já está configurado para mod_rewrite.

---

## 9. Checklist Pós-Deploy

Execute estes testes após o deploy:

- [ ] A página de login abre sem erros
- [ ] Login com `admin@gymmanager.com` / `admin123` funciona
- [ ] **Trocar a senha do admin imediatamente**
- [ ] Cadastro de aluno funciona
- [ ] Listagem de alunos retorna dados
- [ ] Cadastro de matrícula e pagamento funciona
- [ ] Logout destroi a sessão corretamente
- [ ] URL `https://` funciona (HTTPS ativo)
- [ ] Páginas protegidas redirecionam para login sem autenticação

---

## 10. Atualizar o Sistema (Após Correções)

```bash
cd /var/www/gymmanager
git pull origin main

# Se o schema tiver mudado, aplique manualmente via MySQL
# (sempre faça backup antes!)
```

---

## 11. Backup do Banco de Dados

```bash
# Exportar
mysqldump -u gymuser -p gym_manager > database/backup/backup_$(date +%Y%m%d_%H%M%S).sql

# Restaurar
mysql -u gymuser -p gym_manager < database/backup/backup_20260904.sql
```

---

## 12. Variáveis de Ambiente no XAMPP (Local)

No Windows com XAMPP, como não existe suporte a variáveis de ambiente de sistema facilmente, edite diretamente `app/Config/Database.php`:

```php
return [
    'host'     => 'localhost',
    'port'     => '3306',
    'dbname'   => 'gym_manager',
    'user'     => 'root',
    'password' => '', // XAMPP padrão não tem senha
    'charset'  => 'utf8mb4',
];
```

> Este arquivo está no `.gitignore` e não será versionado. ✅

---

**Fim do guia de deploy.**
