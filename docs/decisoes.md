# Decisões técnicas

## Tela "Cadastro do feedback" (issue #2)

- **Uma única página com 6 etapas.** As etapas 1 a 4 ficam dentro de um `<form>` e a navegação (Próximo/Voltar) é feita em JavaScript; o envio acontece só na etapa 4, via `fetch` para `index.php?pagina=feedback/salvar`, que responde em JSON.
- **Etapas 5 e 6** são mostradas depois do envio com sucesso. A etapa 5 avança sozinha para a 6 após alguns segundos (`TEMPO_AGRADECIMENTO` em `feedback.js`).
- **Acessibilidade:** campos são `radio`/`checkbox` reais (funcionam com teclado e leitor de tela), foco vai para o título a cada etapa, barra de progresso com `role="progressbar"` e animações respeitam `prefers-reduced-motion`.
- **Banco:** tabela `feedbacks` (veja `database/schema.sql`). Os pontos marcados são guardados como JSON.
- **Foto:** opcional, JPG/PNG/WebP até 2 MB, salva em `storage/uploads/feedback/` (fora da pasta pública) com nome aleatório.
- **Segurança:** token CSRF na sessão, PDO com prepared statements, whitelist dos pontos permitidos e validação no servidor.
- **Pendências:** `URL_INICIO` e `URL_MAPA` em `config/config.php` ainda estão como `#` e a rota exibida (Centro → Museu de Arte) é fixa no `FeedbackController`; trocar quando as telas de início e de mapa existirem.
