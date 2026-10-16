</main>

<?php foreach (($js ?? []) as $arquivoJs): ?>
<script src="<?= BASE_URL ?>/assets/js/<?= e($arquivoJs) ?>" defer></script>
<?php endforeach; ?>
</body>
</html>
