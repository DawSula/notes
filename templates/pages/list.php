<div class="list">
    <section>
        <div class="message">
            <?php
            if (!empty($params['before'])) {
                switch ($params['before']) {
                    case 'created':
                        echo 'Notatka została utworzona !!!';
                        break;
                    case 'edited':
                        echo 'Notatka została zedytowana !!!';
                        break;
                    case 'deleted':
                        echo 'Notatka została usunięta !!!';
                        break;
                }
            }
            ?>
        </div>

        <div class="message">
            <?php
            if (!empty($params['error'])) {
                switch ($params['error']) {
                    case 'noteNotFound':
                        echo 'Nie znaleziono notatki !!!';
                        break;
                    case 'missingNoteId':
                        echo 'Niepoprawne ID notatki';
                        break;
                }
            }
            ?>
        </div>

        <?php
        $sort = $params['sort' ?? []];
        $by = $sort['by'] ?? 'title';
        $order = $sort['order'] ?? 'desc';

        $page = $params['page'] ?? [];
        $size = $page['size'] ?? 10;
        $currentPage = $page['number'] ?? 1;
        $pages = $page['pages'] ?? 1;

        $phrase = $params['phrase'] ?? null;


        ?>


        <div class="content">

            <form action="/" method="GET">
                <div class="mb-3">
                    <label>Wyszukaj:<input class="i_list" type="text" name="phrase"
                                           value="<?php echo $phrase ?>"></label>
                </div>
                <div class="mb-3">Sortuj po:
                    <label class="l_list">Tytule: <input name="sortby" type="radio"
                                                         value="title" <?php echo $by === 'title' ? 'checked' : '' ?>></label>
                    <label class="l_list">Dacie: <input name="sortby" type="radio"
                                                        value="created" <?php echo $by === 'created' ? 'checked' : '' ?>></label>
                </div>

                <div class="mb-3">Kierunek sortowania:

                    <label class="l_list">Rosnąco: <input name="sortorder" type="radio"
                                                          value="asc" <?php echo $order === 'asc' ? 'checked' : '' ?>></label>
                    <label class="l_list">Malejąco: <input name="sortorder" type="radio"
                                                           value="desc" <?php echo $order === 'desc' ? 'checked' : '' ?>></label>
                </div>

                <div class="mb-3">
                    <div>
                        <div>Rozmiar paczki:</div>
                        <label class="l_list">1 <input name="pagesize" type="radio"
                                                       value="1" <?php echo $size === 1 ? 'checked' : '' ?>>
                        </label>
                        <label class="l_list">5 <input name="pagesize" type="radio"
                                                       value="5" <?php echo $size === 5 ? 'checked' : '' ?>>
                        </label>
                        <label class="l_list">10 <input name="pagesize" type="radio"
                                                        value="10" <?php echo $size === 10 ? 'checked' : '' ?>>
                        </label>
                        <label class="l_list">25 <input name="pagesize" type="radio"
                                                        value="25" <?php echo $size === 25 ? 'checked' : '' ?>>
                        </label>

                    </div>
                </div>
                <button class="btn btn-secondary" type="submit">WYŚLIJ</button>
            </form>
        </div>

        <div class="content">

            <table class="table table-dark">
                <thead>
                <tr>
                    <th scope="col">Tytuł</th>
                    <th scope="col">Data</th>
                    <th scope="col">Opcje</th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($params['notes'] ?? [] as $note) : ?>
                    <tr class="table-light">

                        <td><?php echo $note['title'] ?></td>
                        <td><?php echo $note['created'] ?></td>
                        <td>
                            <a href="/?action=show&id=<?php echo (int)$note['id'] ?>">
                                <button type="button" class="btn btn-outline-dark">Pokaż</button>
                            </a>
                            <a href="/?action=delete&id=<?php echo (int)$note['id'] ?>">
                                <button type="button" class="btn btn-outline-dark">Usuń</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php $paginationUrl = "&phrase=$phrase&pagesize=$size?sortby=$by&sortorder=$order" ?>

        <ul class="pagination">
            <?php if ($currentPage !== 1) : ?>
                <li>
                    <a href="/?page=<?php echo $currentPage - 1 . $paginationUrl ?>">
                        <button class="btn btn-outline-dark">
                            <<
                        </button>
                    </a>
                </li>
            <?php endif ?>
            <?php for ($i = 1; $i <= $pages; $i++) : ?>
                <li>
                    <a href="/?page=<?php echo $i . $paginationUrl ?>">
                        <button class="btn btn-outline-dark"><?php echo $i ?></button>
                    </a>
                </li>
            <?php endfor; ?>
            <?php if ($currentPage < $pages) : ?>
                <li>
                    <a href="/?page=<?php echo $currentPage + 1 . $paginationUrl ?>">
                        <button class="btn btn-outline-dark">
                            >>
                        </button>
                    </a>
                </li>
            <?php endif ?>
        </ul>

    </section>
</div>