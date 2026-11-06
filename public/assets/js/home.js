document.addEventListener('DOMContentLoaded', () =>  {
    const dados = document.getElementById('rotas-dados');
    const painel = document.getElementById('mapa-rota');

    if (!dados || !painel) return;

    let rotas;
    try{
        rotas = JSON.parse(dados.textContent);
    } catch {
        return;
    }

    const porId = new Map(rotas.map((rota) => [String(rota.id), rota]));
    const botoes = document.querySelectorAll('[data-rota-id]');


    function mostrarRotaMapa(rota) {
        painel.textContent = 'Rota no mapa: ' + rota.origem + ' → ' + rota.destino;
    }
    
    botoes.forEach((botao) => {
        botao.addEventListener('click', (evento) => {
            evento.preventDefault();
            const rota = porId.get(botao.dataset.rotaId);
            if (!rota) return;

            botoes.forEach((outro) => outro.removeAttribute('aria-current'));
            botao.setAttribute('aria-current', 'true');

            mostrarRotaMapa(rota);
        });
    });
});