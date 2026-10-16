<?php

/* ---------- CSRF ---------- */

function gerarCsrf(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf'];
}

function validarCsrf(string $token): bool
{
    return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}

/* ---------- Feedback ---------- */

/**
 * Valida os dados do formulário de feedback.
 *
 * @param array<string,mixed>      $post          conteúdo de $_POST
 * @param array<string,mixed>|null $arquivo       conteúdo de $_FILES['foto'] (se existir)
 * @param string[]                 $pontosValidos chaves permitidas para os pontos (rampa, calcada...)
 * @return array{0: array<string,mixed>, 1: array<string,string>} [dados limpos, erros por campo]
 */
function validarFeedback(array $post, ?array $arquivo, array $pontosValidos): array
{
    $erros = [];

    $nota = filter_var($post['nota'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 5]]);
    if ($nota === false) {
        $erros['nota'] = 'Escolha de 1 a 5 estrelas para continuar.';
    }

    $rotaId = filter_var($post['rota_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($rotaId === false) {
        $erros['geral'] = 'Rota inválida. Volte ao mapa e escolha a rota novamente.';
    }

    $comentario = str_replace("\r\n", "\n", trim((string) ($post['comentario'] ?? '')));
    if (mb_strlen($comentario) > COMENTARIO_MAX) {
        $erros['comentario'] = 'O comentário pode ter no máximo ' . COMENTARIO_MAX . ' caracteres.';
    }

    $erroFoto = validarFoto($arquivo);
    if ($erroFoto !== null) {
        $erros['foto'] = $erroFoto;
    }

    $dados = [
        'rota_id'    => $rotaId,
        'nota'       => $nota,
        'positivos'  => filtrarPontos($post['positivos'] ?? [], $pontosValidos),
        'melhorar'   => filtrarPontos($post['melhorar'] ?? [], $pontosValidos),
        'comentario' => $comentario !== '' ? $comentario : null,
        'foto'       => null,
    ];

    return [$dados, $erros];
}

/**
 * Mantém só as chaves permitidas (ignora qualquer valor inventado no formulário).
 *
 * @param mixed    $valores
 * @param string[] $validos
 * @return string[]
 */
function filtrarPontos(mixed $valores, array $validos): array
{
    if (!is_array($valores)) {
        return [];
    }

    $textos = array_filter($valores, 'is_string');

    return array_values(array_unique(array_intersect($textos, $validos)));
}

/**
 * Confere a foto enviada. Retorna a mensagem de erro, ou null se estiver tudo certo
 * (a foto é opcional).
 *
 * @param array<string,mixed>|null $arquivo
 */
function validarFoto(?array $arquivo): ?string
{
    if ($arquivo === null || ($arquivo['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    $limiteMb = FOTO_MAX_BYTES / 1024 / 1024;

    if (in_array($arquivo['error'], [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true)) {
        return "A foto é grande demais. Escolha uma imagem de até {$limiteMb} MB.";
    }

    if ($arquivo['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($arquivo['tmp_name'])) {
        return 'Não foi possível receber a foto. Tente escolher outra imagem.';
    }

    if ($arquivo['size'] > FOTO_MAX_BYTES) {
        return "A foto é grande demais. Escolha uma imagem de até {$limiteMb} MB.";
    }

    $info = @getimagesize($arquivo['tmp_name']);
    if ($info === false || !isset(TIPOS_FOTO[$info['mime']])) {
        return 'Use uma foto nos formatos JPG, PNG ou WebP.';
    }

    return null;
}
