<?php

class FeedbackController
{
    /** Pontos que o usuário pode marcar nas etapas 2 e 3 (chave => texto exibido). */
    public const PONTOS = [
        'rampa'      => 'Rampa de acesso',
        'calcada'    => 'Calçada boa',
        'sinalizacao' => 'Sinalização',
        'transporte' => 'Transporte público',
        'banheiro'   => 'Banheiro acessível',
        'elevador'   => 'Elevador',
        'piso_tatil' => 'Piso tátil',
        'outro'      => 'Outro',
    ];

    /**
     * Mostra a tela de avaliação (etapas 1 a 6).
     */
    public function cadastro(): void
    {
        // TODO: quando existir a tela do mapa, receber origem e destino reais da rota.
        $rotaId = filter_input(INPUT_GET, 'rota_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: 1;
        $rota = ['id' => $rotaId, 'origem' => 'Centro', 'destino' => 'Museu de Arte'];

        $pontos = self::PONTOS;
        $csrf = gerarCsrf();

        $tituloPagina = 'Avaliar rota';
        $css = ['feedback.css'];
        $js = ['feedback.js'];

        require BASE_PATH . '/app/Views/layouts/header.php';
        require BASE_PATH . '/app/Views/feedback/cadastro.php';
        require BASE_PATH . '/app/Views/layouts/footer.php';
    }

    /**
     * Recebe o formulário (POST), valida, grava e responde em JSON.
     */
    public function salvar(): never
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(405, ['ok' => false, 'erro' => 'Método não permitido.']);
        }

        if (!validarCsrf((string) ($_POST['csrf'] ?? ''))) {
            $this->json(419, ['ok' => false, 'erro' => 'Sua sessão expirou. Recarregue a página e tente de novo.']);
        }

        $foto = isset($_FILES['foto']) && is_array($_FILES['foto']) ? $_FILES['foto'] : null;

        [$dados, $erros] = validarFeedback($_POST, $foto, array_keys(self::PONTOS));

        if ($erros !== []) {
            $this->json(422, ['ok' => false, 'erros' => $erros]);
        }

        try {
            $dados['foto'] = $this->guardarFoto($foto);
            $id = Feedback::criar(conectar(), $dados);
        } catch (Throwable $erro) {
            registrarErro($erro);
            $this->json(500, ['ok' => false, 'erro' => 'Não foi possível enviar agora. Tente novamente em instantes.']);
        }

        $this->json(201, ['ok' => true, 'id' => $id]);
    }

    /**
     * Move a foto (já validada) para storage/uploads/feedback com um nome aleatório.
     *
     * @param array<string,mixed>|null $arquivo
     */
    private function guardarFoto(?array $arquivo): ?string
    {
        if ($arquivo === null || $arquivo['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $info = getimagesize($arquivo['tmp_name']);
        $extensao = TIPOS_FOTO[$info['mime']];

        $pasta = BASE_PATH . '/storage/uploads/feedback';
        if (!is_dir($pasta) && !mkdir($pasta, 0775, true) && !is_dir($pasta)) {
            throw new RuntimeException('Não foi possível criar a pasta de fotos.');
        }

        $nome = bin2hex(random_bytes(16)) . '.' . $extensao;

        if (!move_uploaded_file($arquivo['tmp_name'], $pasta . '/' . $nome)) {
            throw new RuntimeException('Não foi possível salvar a foto enviada.');
        }

        return $nome;
    }

    /**
     * @param array<string,mixed> $corpo
     */
    private function json(int $status, array $corpo): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($corpo, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
