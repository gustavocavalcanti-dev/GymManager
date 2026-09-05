<?php

declare(strict_types=1);

class ConfiguracaoController extends Controller
{
    public function index(): void
    {
        $model = new ConfiguracaoModel();
        $this->view('Configuracoes/index', [
            'config' => $model->todos(),
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
            'session_status' => session_status() === PHP_SESSION_ACTIVE ? 'Ativa' : 'Inativa',
            'base_path' => defined('BASE_PATH') ? BASE_PATH : 'N/A',
        ]);
    }

    public function salvar(): void
    {
        if (!Security::validarTokenCSRF((string)($_POST['csrf_token'] ?? ''))) {
            $this->setFlash('erro', 'Token de segurança inválido.');
            $this->redirect('/configuracoes');
            return;
        }

        $permitidos = ['nome_fantasia','cnpj','telefone','email','endereco','instagram','facebook','tema','cor_primaria'];
        $dados = [];
        foreach ($permitidos as $chave) {
            $dados[$chave] = trim((string)($_POST[$chave] ?? ''));
        }

        if ($dados['email'] !== '' && !filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('erro', 'Informe um e-mail válido.');
            $this->redirect('/configuracoes');
            return;
        }

        if ($dados['cor_primaria'] !== '' && !preg_match('/^#[0-9a-fA-F]{6}$/', $dados['cor_primaria'])) {
            $this->setFlash('erro', 'A cor principal deve estar no formato hexadecimal, por exemplo #2164E9.');
            $this->redirect('/configuracoes');
            return;
        }

        try {
            if (isset($_FILES['logo']) && (int)($_FILES['logo']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                $file = $_FILES['logo'];
                if ((int)$file['error'] !== UPLOAD_ERR_OK) {
                    throw new RuntimeException('Falha ao receber o arquivo da logo.');
                }
                if ((int)$file['size'] > 5 * 1024 * 1024) {
                    throw new RuntimeException('A logo deve ter no máximo 5 MB.');
                }

                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime = (string)$finfo->file((string)$file['tmp_name']);
                $extensoes = [
                    'image/png' => 'png',
                    'image/jpeg' => 'jpg',
                ];
                if (!isset($extensoes[$mime])) {
                    throw new RuntimeException('Formato de logo inválido. Envie PNG ou JPG.');
                }

                $uploadDir = dirname(__DIR__, 2) . '/assets/uploads';
                if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
                    throw new RuntimeException('Não foi possível criar a pasta de uploads.');
                }

                $ext = $extensoes[$mime];
                $fileName = 'logo-academia.' . $ext;
                $destino = $uploadDir . '/' . $fileName;
                if (!move_uploaded_file((string)$file['tmp_name'], $destino)) {
                    throw new RuntimeException('Não foi possível salvar a logo enviada.');
                }
                $dados['logo_path'] = '/assets/uploads/' . $fileName;
            }

            (new ConfiguracaoModel())->salvarVarios($dados);
            $this->setFlash('sucesso', 'Configurações salvas com sucesso!');
        } catch (Throwable $e) {
            $this->setFlash('erro', 'Erro ao salvar configurações: ' . $e->getMessage());
        }

        $this->redirect('/configuracoes');
    }
}
