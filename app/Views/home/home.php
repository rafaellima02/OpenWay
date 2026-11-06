   <?php
   $textoDetalhe = static function (array $rota): string {
       $ocorrencias = (int) $rota['ocorrencia'];

       $situacao = match (true) {
           $ocorrencias === 0 => 'Acessível',
           $ocorrencias === 1 => '1 ocorrência',
           default            => $ocorrencias . ' ocorrências',
       };

       return $situacao . ' · ' . (int) $rota['tempo'] . ' min';
   };
   ?>
   <main>
       <h1>Olá! Para onde vamos hoje?</h1>

       <form action="index.php" method="get">
           <input type="hidden" name="pagina" value="home">
           <label for="destino">Buscar destino</label>
           <input id="destino" name="destino" type="text" placeholder="Endereço, local ou ponto de referência">
           <button type="submit">Buscar</button>
       </form>

       <h2>Rotas recentes</h2>
       <?php if ($rotasRecentes === []): ?>
           <p>Nenhuma rota ainda.</p>
       <?php else: ?>
           <ul>
               <?php foreach ($rotasRecentes as $rota): ?>
                   <li>
                        <a href="#mapa-rota" data-rota-id="<?= (int) $rota['id'] ?>">
                        <?= e($rota['origem']) ?> → <?= e($rota['destino']) ?>
                        (<?= e($textoDetalhe($rota)) ?>)
                        </a>
                    </li>
               <?php endforeach; ?>
           </ul>
       <?php endif; ?>

       <section>
                <h2>Mapa</h2>
                <div id="mapa-rota" aria-live="polite">Escolha uma rota para visualizar no mapa.</div>
         </section>

       <?php if ($ultimaRotaId !== null): ?>
           <section>
               <h2>Como foi sua última rota?</h2>
               <a href="index.php?pagina=feedback/cadastro&amp;rota_id=<?= (int) $ultimaRotaId ?>">Avaliar agora</a>
           </section>
       <?php endif; ?>
   </main>