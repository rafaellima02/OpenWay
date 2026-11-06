<?php

class HomeController{
    /**
     * Tela inicial: busca de rotas, rotas recentes, mapa e convite para avaliação.

    */

    public function index(): void{   // TODO: trocar por consulta ao banco quando existir a tabela de rotas
        // (origem, destino, tempo e ocorrências). Por enquanto são dados de exemplo.




        $rotasRecentes = [
            ['id' => 1, 'origem' => 'Terminal de Igarassu', 'destino' => 'IFPE(Campus Igarassu)', 'tempo' => 45, 'ocorrencia' => 0],
            ['id' => 2, 'origem' => 'Casa', 'destino' => 'Trabalho', 'tempo' => 70, 'ocorrencia' => 1],
            ['id' => 3, 'origem' => 'Shopping', 'destino' => 'Casa', 'tempo' => 20, 'ocorrencia' => 4],
        ];


        $ultimaRotaId = $rotasRecentes[0]['id'] ?? null;
        $paginaAtual = 'home';

        
        $tituloPagina = 'Início';
        $css = [];
        $js = ['home.js'];


        require BASE_PATH . '/app/Views/layouts/header.php';
        require BASE_PATH . '/app/Views/home/home.php';
        require BASE_PATH . '/app/Views/layouts/footer.php';

    }
}

