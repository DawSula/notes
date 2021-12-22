<div class="login">

    <form action="/" method="post">
        <div class="mb-3">
            <label class="form-label">Nazwa</label>
            <input type="text" class="form-control" name="name">
        </div>

        <div class="mb-5">
            <label class="form-label">Hasło</label>
            <input class="form-control" type="password" name="password">
        </div>

        <div class="mb-3">
            <button class="btn btn-secondary" type="submit">ZALOGUJ</button>
        </div>
    </form>

    <div class="mb-3">
        <a href="/?action=register">
            <button class="btn btn-secondary">REJESTRACJA</button>
        </a>
    </div>



    <div class="mb-3 text-center text-primary">
        <?php if (!empty($params['registered'])):
            switch ($params['registered']) {
                case 'register':
                    echo "Zarejestrowano";
                    break;
            }
        endif;
        ?>
    </div>

    <div class="mb-3 text-center text-danger">
        <?php if (!empty($params['error'])):
            switch ($params['error']) {
                case 'dataRequire':
                    echo "Nie wypełniono wszystkich pól";
                    break;
                case 'IncorrectData':
                    echo "Hasło lub login niepoprawne";
                    break;
            }
        endif;
        ?>
    </div>



</div>