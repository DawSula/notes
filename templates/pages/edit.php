<div class="list">

    <h3> Edycja notatki </h3>
    <div class="edit">
    <?php $note = $params['note']; ?>

    <?php if (!empty($params['note'])) : ?>
      <div>
        <form class="note-form" action="/?action=edit" method="post">
          <input type="hidden" name="id" value="<?php echo $note['id'] ?>" />


          <div class="mb-3">
            <label class="form-label">Tytuł</label>
            <input type="text" class="form-control" value=<?php echo $note['title']; ?> name="title" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Treść</label>
            <textarea class="form-control" name="description" rows="3"><?php echo $note['description'] ?></textarea>

          </div>
          <div class="mb-3">
            <button class="btn btn-secondary" type="submit">WYŚLIJ</button>
          </div>
        </form>
      <?php else : ?>
        <div>
          <p>Brak danych do wyświeltenia</p>
          <a href="/"><button class="btn btn-secondary">Powrót do listy notatek</button></a>
        </div>
      <?php endif; ?>
      </div>
  </div>
</div>