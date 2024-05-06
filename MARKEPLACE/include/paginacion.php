<nav>
    <ul class="pagination justify-content-center">
        <li class="page-item <?php if ($pag == 1) echo 'disabled'; ?>">
            <a class="page-link" href="<?=$url?><?= $pag - 1 ?>">&lt;&lt;</a>
        </li>
        <?php for ($i = 1; $i <= $numPaginas; $i++) : ?>

            <li class="page-item <?php if ($pag == $i) echo "active"; ?>">
                <a class="page-link" href="<?=$url?><?= $i ?>"><?= $i ?></a>
            </li>

        <?php endfor; ?>

        <li class="page-item <?php if ($pag >= $numPaginas) echo 'disabled'; ?>">
            <a class="page-link" href="<?=$url?><?= $pag + 1 ?>">&gt;&gt;</a>
        </li>
    </ul>
</nav>