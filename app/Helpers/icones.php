<?php

/**
 * Devolve um ícone SVG pronto para usar na tela: <?= icone('pin', 'minha-classe') ?>
 * A cor segue o "color" do CSS (currentColor). Os ícones são decorativos (aria-hidden).
 */
function icone(string $nome, string $classe = ''): string
{
    static $icones = [
        // Pontos de acessibilidade
        'rampa'       => '<circle cx="11" cy="4.5" r="1.7"/><path d="M11 8.5v5.5h5l2.5 5"/><path d="M11 11.5h4.5"/><path d="M8 11.5a5.5 5.5 0 1 0 7.5 7.2"/>',
        'calcada'     => '<circle cx="13.5" cy="4.5" r="1.7"/><path d="M13 8.5l-2 5.5"/><path d="M11 14l-3 6"/><path d="M11 14l4.5 2.5.5 4.5"/><path d="M13 8.5l-3.5 2.5"/><path d="M13 8.5l3.5 3 2.5.5"/>',
        'sinalizacao' => '<path d="M12 3v18"/><path d="M5 5.5h11l2.5 2.5-2.5 2.5H5z"/><path d="M19 13.5H8L5.5 16 8 18.5h11z"/>',
        'transporte'  => '<rect x="4" y="3.5" width="16" height="14" rx="3"/><path d="M4 11h16"/><path d="M7 20v-2.5M17 20v-2.5"/><circle cx="8" cy="14.2" r=".6" fill="currentColor"/><circle cx="16" cy="14.2" r=".6" fill="currentColor"/>',
        'banheiro'    => '<rect x="7" y="3" width="10" height="5.5" rx="1.5"/><path d="M5 11.5h14c0 4-2.2 6.5-7 6.5s-7-2.5-7-6.5z"/><path d="M9.5 18l-1 3h7l-1-3"/>',
        'elevador'    => '<rect x="4" y="3" width="16" height="18" rx="2.5"/><path d="M12 3v18"/><path d="M8 7.5 6 10.5h4z" fill="currentColor"/><path d="M16 16.5l-2-3h4z" fill="currentColor"/>',
        'piso_tatil'  => '<g fill="currentColor" stroke="none"><circle cx="6" cy="6" r="1.4"/><circle cx="12" cy="6" r="1.4"/><circle cx="18" cy="6" r="1.4"/><circle cx="6" cy="12" r="1.4"/><circle cx="12" cy="12" r="1.4"/><circle cx="18" cy="12" r="1.4"/><circle cx="6" cy="18" r="1.4"/><circle cx="12" cy="18" r="1.4"/><circle cx="18" cy="18" r="1.4"/></g>',
        'outro'       => '<path d="M12 5v14M5 12h14"/>',

        // Interface
        'alerta'      => '<path d="M12 3.5 2.5 20h19z"/><path d="M12 10v4.5"/><path d="M12 17.2v.1"/>',
        'check'       => '<path d="M5 12.5l4.5 4.5L19 7.5"/>',
        'pin'         => '<path fill="currentColor" stroke="none" d="M12 2a7.5 7.5 0 0 0-7.5 7.5c0 5.4 7.5 12.5 7.5 12.5s7.5-7.1 7.5-12.5A7.5 7.5 0 0 0 12 2z"/><circle cx="12" cy="9.5" r="2.7" fill="#fff" stroke="none"/>',
        'rota'        => '<path d="M12 2.5a4.8 4.8 0 0 0-4.8 4.8c0 3.4 4.8 7.7 4.8 7.7s4.8-4.3 4.8-7.7A4.8 4.8 0 0 0 12 2.5z"/><circle cx="12" cy="7.3" r="1.6"/><path d="M5.5 20c3-1.8 5 1.2 9-.2"/>',
        'estrela'     => '<polygon points="12 2.5 14.95 8.5 21.5 9.45 16.75 14.05 17.87 20.55 12 17.5 6.13 20.55 7.25 14.05 2.5 9.45 9.05 8.5"/>',
        'seta'        => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'chevron'     => '<path d="M9 5.5l6.5 6.5L9 18.5"/>',
        'fechar'      => '<path d="M6 6l12 12M18 6L6 18"/>',
        'camera'      => '<path d="M4 8.5h3.2L9 6h6l1.8 2.5H20V19H4z"/><circle cx="12" cy="13.5" r="3.4"/>',
        'casa'        => '<path d="M3.5 11.5 12 4l8.5 7.5"/><path d="M5.5 10v10h13V10"/><path d="M10 20v-6h4v6"/>',
    ];

    if (!isset($icones[$nome])) {
        return '';
    }

    $atributoClasse = $classe !== '' ? ' class="' . e($classe) . '"' : '';

    return '<svg' . $atributoClasse . ' viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"'
        . ' stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">'
        . $icones[$nome]
        . '</svg>';
}
