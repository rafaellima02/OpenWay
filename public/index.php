<?php
declare(strict_types=1);

/**
 * Ponto de entrada do OpenWay.
 *
 * Rotas disponíveis:
 *   index.php?pagina=feedback/cadastro   -> tela de avaliação da rota
 *   index.php?pagina=feedback/salvar     -> recebe o envio do formulário (POST)
 */

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/config/config.php';
require BASE_PATH . '/config/database.php';
require BASE_PATH . '/app/Helpers/geral.php';
require BASE_PATH . '/app/Helpers/icones.php';
require BASE_PATH . '/app/Helpers/validacao.php';

spl_autoload_register(static function (string $classe): void {
    foreach (['Controllers', 'Models'] as $pasta) {
        $arquivo = BASE_PATH . "/app/$pasta/$classe.php";
        if (is_file($arquivo)) {
            require $arquivo;
            return;
        }
    }
});

session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
]);

$pagina = is_string($_GET['pagina'] ?? null) ? $_GET['pagina'] : 'feedback/cadastro';

switch ($pagina) {
    case 'feedback/cadastro':
        (new FeedbackController())->cadastro();
        break;

    case 'feedback/salvar':
        (new FeedbackController())->salvar();
        break;

    default:
        http_response_code(404);
        header('Content-Type: text/plain; charset=utf-8');
        echo 'Página não encontrada.';
}
