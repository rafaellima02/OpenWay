<?php

class Feedback
{
    /**
     * Grava um feedback e devolve o id criado.
     *
     * @param array<string,mixed> $dados já validados por validarFeedback()
     */
    public static function criar(PDO $pdo, array $dados): int
    {
        $sql = 'INSERT INTO feedbacks (rota_id, nota, pontos_positivos, pontos_melhorar, comentario, foto)
                VALUES (:rota_id, :nota, :positivos, :melhorar, :comentario, :foto)';

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':rota_id'    => $dados['rota_id'],
            ':nota'       => $dados['nota'],
            ':positivos'  => json_encode($dados['positivos'], JSON_THROW_ON_ERROR),
            ':melhorar'   => json_encode($dados['melhorar'], JSON_THROW_ON_ERROR),
            ':comentario' => $dados['comentario'],
            ':foto'       => $dados['foto'],
        ]);

        return (int) $pdo->lastInsertId();
    }
}
