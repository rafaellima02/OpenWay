<?php
declare(strict_types=1);

/**
 * Configurações gerais do OpenWay.
 * Requer que BASE_PATH já esteja definido (feito em public/index.php).
 */

const APP_NOME = 'OpenWay';

// Regras do formulário de feedback
const COMENTARIO_MAX = 500;
const FOTO_MAX_BYTES = 2 * 1024 * 1024; // 2 MB (limite padrão do PHP é 2 MB)
const TIPOS_FOTO = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
];

// Pasta pública onde o site está rodando ('' quando o servidor aponta direto para public/)
define('BASE_URL', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/'));

// TODO: trocar pelos endereços reais quando essas telas existirem
define('URL_INICIO', '#');
define('URL_MAPA', '#');

/**
 * Lê o arquivo .env (CHAVE=valor, uma por linha) e guarda em $_ENV.
 */
function carregarEnv(string $arquivo): void
{
    if (!is_readable($arquivo)) {
        return;
    }

    $linhas = file($arquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];

    foreach ($linhas as $linha) {
        $linha = trim($linha);

        if ($linha === '' || $linha[0] === '#' || !str_contains($linha, '=')) {
            continue;
        }

        [$chave, $valor] = array_map('trim', explode('=', $linha, 2));
        $_ENV[$chave] = trim($valor, "\"'");
    }
}

function env(string $chave, string $padrao = ''): string
{
    return $_ENV[$chave] ?? $padrao;
}

carregarEnv(BASE_PATH . '/.env');
