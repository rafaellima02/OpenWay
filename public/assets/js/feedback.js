/* OpenWay - tela "Cadastro do feedback": navegação entre as 6 etapas, validação e envio */
(() => {
    'use strict';

    const TEMPO_AGRADECIMENTO = 4500;       // ms na etapa 5 antes de ir para a 6
    const FOTO_MAX_BYTES = 2 * 1024 * 1024; // mesmo limite do servidor (2 MB)

    const form = document.getElementById('form-feedback');
    if (!form) return;

    const cartao = form.closest('.cartao');
    const etapas = Array.from(cartao.querySelectorAll('.etapa'));
    const total = etapas.length;

    const textoProgresso = document.getElementById('progresso-texto');
    const barra = document.getElementById('progresso-barra');
    const preenchido = barra.querySelector('.progresso__preenchido');

    let atual = 1;

    /* ---------- Navegação ---------- */

    function irPara(numero, moverFoco = true) {
        atual = numero;

        etapas.forEach((etapa, indice) => {
            etapa.hidden = indice + 1 !== numero;
        });

        textoProgresso.textContent = `${numero}/${total}`;
        barra.setAttribute('aria-valuenow', String(numero));
        barra.setAttribute('aria-valuetext', `Etapa ${numero} de ${total}`);
        preenchido.style.width = `${(numero / total) * 100}%`;

        // O foco vai para o título: leitores de tela anunciam a nova etapa
        if (moverFoco) {
            const titulo = etapas[numero - 1].querySelector('.titulo');
            if (titulo) titulo.focus();
        }
    }

    /* ---------- Mensagens de erro ---------- */

    function mostrarErro(nome, mensagem) {
        const alvo = cartao.querySelector(`[data-erro="${nome}"]`);
        if (!alvo) return;
        alvo.textContent = mensagem;
        alvo.hidden = false;
    }

    function limparErros() {
        cartao.querySelectorAll('[data-erro]').forEach((el) => {
            el.textContent = '';
            el.hidden = true;
        });
    }

    /* ---------- Validação por etapa ---------- */

    function etapaValida(numero) {
        limparErros();

        if (numero === 1 && !form.elements.nota.value) {
            mostrarErro('nota', 'Escolha de 1 a 5 estrelas para continuar.');
            return false;
        }

        return true;
    }

    /* ---------- Botões Próximo / Voltar / Enviar ---------- */

    cartao.addEventListener('click', (evento) => {
        const botao = evento.target.closest('[data-acao]');
        if (!botao) return;

        const acao = botao.dataset.acao;

        if (acao === 'proximo' && etapaValida(atual)) {
            irPara(atual + 1);
        } else if (acao === 'voltar') {
            limparErros();
            irPara(atual - 1);
        } else if (acao === 'enviar') {
            enviar(botao);
        }
    });

    // O envio é feito por fetch (abaixo); impede o envio padrão do navegador
    form.addEventListener('submit', (evento) => evento.preventDefault());

    /* ---------- Contador do comentário ---------- */

    const comentario = document.getElementById('comentario');
    const contador = document.getElementById('contador');

    comentario.addEventListener('input', () => {
        contador.textContent = `${comentario.value.length}/${comentario.maxLength}`;
    });

    /* ---------- Foto ---------- */

    const campoFoto = document.getElementById('foto');
    const miniatura = document.getElementById('foto-miniatura');
    const iconeFoto = document.getElementById('foto-icone');
    const tituloFoto = document.getElementById('foto-titulo');
    const dicaFoto = document.getElementById('foto-dica');
    const botaoRemover = document.getElementById('foto-remover');

    const textoTituloPadrao = tituloFoto.textContent;
    const textoDicaPadrao = dicaFoto.textContent;
    let urlMiniatura = null;

    function limparFoto() {
        campoFoto.value = '';
        if (urlMiniatura) {
            URL.revokeObjectURL(urlMiniatura);
            urlMiniatura = null;
        }
        miniatura.hidden = true;
        miniatura.removeAttribute('src');
        iconeFoto.hidden = false;
        tituloFoto.textContent = textoTituloPadrao;
        dicaFoto.textContent = textoDicaPadrao;
        botaoRemover.hidden = true;
    }

    campoFoto.addEventListener('change', () => {
        limparErros();
        const arquivo = campoFoto.files[0];

        if (!arquivo) {
            limparFoto();
            return;
        }

        if (!/^image\/(jpeg|png|webp)$/.test(arquivo.type)) {
            limparFoto();
            mostrarErro('foto', 'Use uma foto nos formatos JPG, PNG ou WebP.');
            return;
        }

        if (arquivo.size > FOTO_MAX_BYTES) {
            limparFoto();
            mostrarErro('foto', 'A foto é grande demais. Escolha uma imagem de até 2 MB.');
            return;
        }

        if (urlMiniatura) URL.revokeObjectURL(urlMiniatura);
        urlMiniatura = URL.createObjectURL(arquivo);

        miniatura.src = urlMiniatura;
        miniatura.hidden = false;
        iconeFoto.hidden = true;
        tituloFoto.textContent = arquivo.name;
        dicaFoto.textContent = 'Toque para trocar a foto.';
        botaoRemover.hidden = false;
    });

    botaoRemover.addEventListener('click', limparFoto);

    /* ---------- Envio ---------- */

    async function enviar(botao) {
        limparErros();
        botao.disabled = true;
        botao.setAttribute('aria-busy', 'true');

        try {
            const resposta = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { Accept: 'application/json' },
            });

            let json = null;
            try {
                json = await resposta.json();
            } catch (e) {
                json = null;
            }

            if (resposta.ok && json && json.ok) {
                irPara(5);
                setTimeout(() => {
                    if (atual === 5) irPara(6);
                }, TEMPO_AGRADECIMENTO);
                return;
            }

            if (json && json.erros) {
                Object.entries(json.erros).forEach(([campo, mensagem]) => mostrarErro(campo, mensagem));
                if (json.erros.nota) irPara(1);
            } else {
                mostrarErro('geral', (json && json.erro) || 'Não foi possível enviar agora. Tente novamente em instantes.');
            }
        } catch (e) {
            mostrarErro('geral', 'Sem conexão com o servidor. Confira sua internet e tente de novo.');
        } finally {
            botao.disabled = false;
            botao.removeAttribute('aria-busy');
        }
    }
})();
