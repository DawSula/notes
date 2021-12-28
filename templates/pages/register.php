

<div class="register">
    <form action="?action=register" method="post">
        <div class="mb-3">
            <label class="form-label">Nazwa</label>
            <input type="text" class="form-control" name="name">
        </div>

        <div class="mb-3">
            <label class="form-label">Hasło</label>
            <input class="form-control" type="password" name="password">
        </div>

        <div class="mb-5">
            <label class="form-label">Powtórz</label>
            <input class="form-control" type="password" name="repeatedPassword">
        </div>
        <div class="mb-3">
            <button class="btn btn-secondary" type="submit">WYŚLIJ</button>
        </div>
    </form>

    <div class="mb-3">
        <a href="/"><button class="btn btn-secondary">LOGOWANIE</button></a>
    </div>

    <div class="mb-3 text-center text-danger">
        <?php if (!empty($params['error'])):
        switch ($params['error']){
            case 'passwordError':
                echo "Podane hasła są różne";
                break;
            case 'someEmpty':
                echo "Wszystkie pola muszą być wypełnione";
                break;
            case 'userExist':
                echo "Użytkownik o podanej nazwie istnieje";
                break;
            case 'passwordNotEnoughStrong':
                echo "Hasło powinno mieć przynajmniej 8 znaków zawierające 1 dużą literę, 1 numer, 1 znak specjalny ";
                break;
        };
        endif ?>
    </div>


</div>