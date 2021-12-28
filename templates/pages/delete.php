<div class="list">
    <div class="delete">
        <?php $note = $params['note'] ?? null; ?>
        <?php if ($note) : ?>
            <div class="mb-3">Tytuł: <?php echo $note['title'] ?></div>
            <div class="mb-3"><?php echo $note['description'] ?></div>
            <div class="mb-3">Utworzono: <?php echo $note['created'] ?></div>

            <div class="mb-3">
                <a href="/">
                    <button class="btn btn-secondary">Powrót do listy notatek</button>
                </a>
            </div>



            <form action="/?action=delete" method="POST">
                <div class="mb-3">
                    <input type='hidden' name="id" value="<?php echo $note['id'] ?>">
                    <button class="btn btn-secondary" type="submit">USUŃ</button>
                </div>
            </form>
        <?php endif ?>
    </div>
</div>