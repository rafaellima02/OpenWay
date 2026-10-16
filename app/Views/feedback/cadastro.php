<?php
/**
 * Tela: Cadastro do feedback (issue #2) - 6 etapas.
 *
 * Variáveis recebidas do FeedbackController::cadastro():
 *   $rota   array  id, origem, destino
 *   $pontos array  chave => rótulo (rampa, calçada...)
 *   $csrf   string token de segurança do formulário
 */
$trajeto = e($rota['origem']) . ' → ' . e($rota['destino']);
?>
<div class="cartao">

    <header class="topo">
        <div class="marca">
            <?= icone('pin') ?>
            <span>Rotas</span>
        </div>

        <div class="progresso">
            <span class="progresso__texto" id="progresso-texto" aria-hidden="true">1/6</span>
            <div class="progresso__barra" id="progresso-barra" role="progressbar"
                 aria-label="Progresso da avaliação" aria-valuemin="1" aria-valuemax="6"
                 aria-valuenow="1" aria-valuetext="Etapa 1 de 6">
                <span class="progresso__preenchido"></span>
            </div>
        </div>

        <a class="fechar" href="<?= e(URL_INICIO) ?>" aria-label="Fechar avaliação">
            <?= icone('fechar') ?>
        </a>
    </header>

    <noscript>
        <p class="erro">Ative o JavaScript do navegador para enviar sua avaliação.</p>
    </noscript>

    <form id="form-feedback" action="<?= BASE_URL ?>/index.php?pagina=feedback/salvar"
          method="post" enctype="multipart/form-data" novalidate>

        <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
        <input type="hidden" name="rota_id" value="<?= (int) $rota['id'] ?>">

        <!-- ETAPA 1: nota geral -->
        <section class="etapa etapa--rota" data-etapa="1" aria-labelledby="titulo-1">
            <div class="etapa__corpo">
                <svg class="mapa-deco" viewBox="0 0 260 380" aria-hidden="true" focusable="false">
                    <defs>
                        <linearGradient id="mapa-fade" x1="0" y1="0" x2="1" y2="0">
                            <stop offset="0" stop-color="#fff" stop-opacity="0"/>
                            <stop offset=".45" stop-color="#fff" stop-opacity=".9"/>
                            <stop offset="1" stop-color="#fff"/>
                        </linearGradient>
                        <mask id="mapa-mascara">
                            <rect width="260" height="380" fill="url(#mapa-fade)"/>
                        </mask>
                    </defs>
                    <g mask="url(#mapa-mascara)">
                        <rect width="260" height="380" fill="#d6e8fb"/>
                        <g stroke="#fff" stroke-width="13" stroke-linecap="round" opacity=".9">
                            <line x1="10" y1="40" x2="270" y2="125"/>
                            <line x1="-10" y1="125" x2="270" y2="215"/>
                            <line x1="55" y1="-10" x2="205" y2="390"/>
                            <line x1="150" y1="-10" x2="262" y2="300"/>
                            <line x1="-10" y1="240" x2="240" y2="335"/>
                            <line x1="110" y1="95" x2="20" y2="330"/>
                        </g>
                    </g>
                    <path d="M70 58C42 110 58 150 88 178L180 292" fill="none" stroke="#0b6bde" stroke-width="6"
                          stroke-linecap="round" stroke-linejoin="round"/>
                    <g fill="#0b6bde" stroke="#fff" stroke-width="3">
                        <circle cx="70" cy="58" r="9"/>
                        <circle cx="88" cy="178" r="9"/>
                        <circle cx="180" cy="292" r="9"/>
                    </g>
                </svg>

                <div class="etapa__texto">
                    <h1 class="titulo" id="titulo-1" tabindex="-1">Como foi <span>sua rota?</span></h1>
                    <p class="lead">Sua avaliação nos ajuda a melhorar o OpenWay e tornar os caminhos mais acessíveis para todos.</p>

                    <a class="rota-card" href="<?= e(URL_MAPA) ?>"
                       aria-label="Rota selecionada: <?= e($rota['origem']) ?> até <?= e($rota['destino']) ?>. Alterar rota">
                        <?= icone('pin') ?>
                        <span>
                            <span class="rota-card__rotulo">Rota selecionada</span>
                            <span class="rota-card__trajeto"><?= $trajeto ?></span>
                        </span>
                        <?= icone('chevron', 'chevron') ?>
                    </a>

                    <fieldset class="avaliacao">
                        <legend>Sua experiência geral</legend>
                        <div class="estrelas">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <input class="sr-only" type="radio" name="nota" id="nota-<?= $i ?>" value="<?= $i ?>">
                                <label for="nota-<?= $i ?>">
                                    <span class="sr-only"><?= $i ?> <?= $i === 1 ? 'estrela' : 'estrelas' ?></span>
                                    <?= icone('estrela') ?>
                                </label>
                            <?php endfor; ?>
                        </div>
                    </fieldset>
                    <p class="erro" data-erro="nota" role="alert" hidden></p>
                </div>
            </div>

            <div class="etapa__acoes">
                <button type="button" class="btn" data-acao="proximo">Próximo <?= icone('seta') ?></button>
                <a class="btn-link" href="<?= e(URL_INICIO) ?>">Voltar</a>
            </div>
        </section>

        <!-- ETAPA 2: o que funcionou bem -->
        <section class="etapa" data-etapa="2" aria-labelledby="titulo-2" hidden>
            <div class="etapa__corpo">
                <h1 class="titulo" id="titulo-2" tabindex="-1">O que funcionou <span>bem na rota?</span></h1>
                <p class="lead" id="lead-2">Selecione os pontos positivos da sua experiência.</p>

                <fieldset class="chips-grupo" aria-describedby="lead-2">
                    <legend class="sr-only">Pontos positivos</legend>
                    <div class="chips">
                        <?php foreach ($pontos as $chave => $rotulo): ?>
                            <label class="chip">
                                <input class="sr-only" type="checkbox" name="positivos[]" value="<?= e($chave) ?>">
                                <span class="chip__corpo">
                                    <?= icone($chave, 'chip__icone') ?>
                                    <span><?= e($rotulo) ?></span>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </fieldset>
            </div>

            <div class="etapa__acoes">
                <button type="button" class="btn" data-acao="proximo">Próximo <?= icone('seta') ?></button>
                <button type="button" class="btn-link" data-acao="voltar">Voltar</button>
            </div>
        </section>

        <!-- ETAPA 3: o que poderia ser melhor -->
        <section class="etapa" data-etapa="3" aria-labelledby="titulo-3" hidden>
            <div class="etapa__corpo">
                <h1 class="titulo" id="titulo-3" tabindex="-1">O que poderia <span>ser melhor?</span></h1>
                <p class="lead" id="lead-3">Selecione os pontos que precisam de atenção.</p>

                <fieldset class="chips-grupo chips-grupo--atencao" aria-describedby="lead-3">
                    <legend class="sr-only">Pontos que precisam de atenção</legend>
                    <div class="chips">
                        <?php foreach ($pontos as $chave => $rotulo): ?>
                            <label class="chip">
                                <input class="sr-only" type="checkbox" name="melhorar[]" value="<?= e($chave) ?>">
                                <span class="chip__corpo">
                                    <?= icone($chave, 'chip__icone chip__icone--normal') ?>
                                    <?= icone('alerta', 'chip__icone chip__icone--alerta') ?>
                                    <span><?= e($rotulo) ?></span>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </fieldset>
            </div>

            <div class="etapa__acoes">
                <button type="button" class="btn" data-acao="proximo">Próximo <?= icone('seta') ?></button>
                <button type="button" class="btn-link" data-acao="voltar">Voltar</button>
            </div>
        </section>

        <!-- ETAPA 4: detalhes e foto -->
        <section class="etapa" data-etapa="4" aria-labelledby="titulo-4" hidden>
            <div class="etapa__corpo">
                <h1 class="titulo" id="titulo-4" tabindex="-1">Quer nos contar <span>mais detalhes?</span></h1>
                <p class="lead lead--larga" id="lead-4">Descreva o que aconteceu, quando foi e, se possível, adicione uma foto.</p>

                <div class="campo-texto">
                    <label class="sr-only" for="comentario">Detalhes da sua experiência</label>
                    <textarea id="comentario" name="comentario" maxlength="500" rows="4"
                              aria-describedby="lead-4 contador"
                              placeholder="Ex.: Notei que a rampa estava quebrada no dia 12/04…"></textarea>
                    <span class="campo-texto__contador" id="contador">0/500</span>
                </div>
                <p class="erro" data-erro="comentario" role="alert" hidden></p>

                <div class="foto">
                    <input class="sr-only" type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp">
                    <label class="foto__area" for="foto">
                        <img class="foto__miniatura" id="foto-miniatura" alt="Pré-visualização da foto escolhida" hidden>
                        <span class="foto__icone" id="foto-icone"><?= icone('camera') ?></span>
                        <strong id="foto-titulo">Adicionar foto</strong>
                        <small id="foto-dica">Tire ou escolha uma imagem da galeria (opcional).</small>
                    </label>
                    <button type="button" class="btn-link foto__remover" id="foto-remover" hidden>Remover foto</button>
                </div>
                <p class="erro" data-erro="foto" role="alert" hidden></p>
                <p class="erro" data-erro="geral" role="alert" hidden></p>
            </div>

            <div class="etapa__acoes">
                <button type="button" class="btn btn--largo" data-acao="enviar">Enviar <?= icone('seta') ?></button>
                <button type="button" class="btn-link" data-acao="voltar">Voltar</button>
            </div>
        </section>
    </form>

    <!-- ETAPA 5: confirmação -->
    <section class="etapa etapa--centro" data-etapa="5" aria-labelledby="titulo-5" hidden>
        <div class="etapa__corpo">
            <svg class="selo" viewBox="0 0 140 140" aria-hidden="true" focusable="false">
                <circle cx="70" cy="70" r="64" fill="#dcecfc"/>
                <circle cx="70" cy="70" r="46" fill="#bcdcfb" opacity=".7"/>
                <circle cx="70" cy="70" r="32" fill="#0b6bde"/>
                <path d="M55 71l11 11 20-22" fill="none" stroke="#fff" stroke-width="7"
                      stroke-linecap="round" stroke-linejoin="round"/>
            </svg>

            <h1 class="titulo" id="titulo-5" tabindex="-1">Feedback <span>registrado!</span></h1>
            <p class="lead">Obrigado por contribuir. Sua avaliação nos ajuda a melhorar a experiência de todos.</p>

            <div class="proxima">
                <?= icone('rota') ?>
                <div>
                    <p>Quer continuar explorando outra rota?</p>
                    <a href="<?= e(URL_MAPA) ?>">Voltar ao mapa <?= icone('seta') ?></a>
                </div>
            </div>
        </div>
    </section>

    <!-- ETAPA 6: encerramento -->
    <section class="etapa etapa--final" data-etapa="6" aria-labelledby="titulo-6" hidden>
        <div class="etapa__corpo">
            <svg class="ilustracao-final" viewBox="0 0 220 150" aria-hidden="true" focusable="false">
                <path d="M40 126C70 126 80 112 110 112C140 112 150 126 180 126" fill="none" stroke="#0b6bde"
                      stroke-width="5" stroke-linecap="round" stroke-opacity=".55"/>
                <g fill="#0b6bde" stroke="#fff" stroke-width="3">
                    <circle cx="40" cy="126" r="7"/>
                    <circle cx="180" cy="126" r="7"/>
                </g>
                <path fill="#0b6bde" d="M110 6C84 6 66 25 66 49c0 29 44 63 44 63s44-34 44-63C154 25 136 6 110 6z"/>
                <circle cx="110" cy="48" r="27" fill="#fff"/>
                <g stroke="#0b6bde" stroke-width="4" stroke-linecap="round" fill="none">
                    <circle cx="110" cy="37" r="3.5" fill="#0b6bde" stroke="none"/>
                    <path d="M95 47h30M110 47v12M110 59l-8 10M110 59l8 10"/>
                </g>
            </svg>

            <h1 class="titulo" id="titulo-6" tabindex="-1">Um caminho melhor é <span>construído juntos.</span></h1>
            <p class="lead">Seu feedback faz toda a diferença.</p>
        </div>

        <div class="etapa__acoes">
            <a class="btn btn--largo" href="<?= e(URL_INICIO) ?>"><?= icone('casa') ?> Voltar ao início</a>
        </div>
    </section>

</div>
