<?php

/**
 * Escapa texto para exibir em HTML (evita XSS). Use em toda saída vinda de variável.
 */
function e(?string $valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Grava o erro técnico em storage/logs/erro.log (o usuário vê só uma mensagem genérica).
 */
function registrarErro(Throwable $erro): void
{
    $linha = sprintf(
        "[%s] %s em %s:%d\n",
        date('Y-m-d H:i:s'),
        $erro->getMessage(),
        $erro->getFile(),
        $erro->getLine()
    );

    @file_put_contents(BASE_PATH . '/storage/logs/erro.log', $linha, FILE_APPEND | LOCK_EX);
}
