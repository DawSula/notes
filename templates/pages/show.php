<div class="list">
    <?php $note = $params['note'] ?? null; ?>
    <div class="show">
        <?php if ($note) : ?>
            <div class="mb-3">Tytuł: <?php echo $note['title'] ?></div>
            <div class="mb-3"><?php echo $note['description'] ?></div>
            <div class="mb-3">Utworzono: <?php echo $note['created'] ?></div>

            </ul>
            <a href="/?action=edit&id=<?php echo $note['id'] ?>">
                <button class="btn btn-secondary">Edytuj</button></a>
            <a href="/">
                <button class="btn btn-secondary">Powrót do listy notatek</button>
            </a>
        <?php endif ?>
    </div>
</div>