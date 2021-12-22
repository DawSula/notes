<html lang="pl">

<head>
    <title>Notatnik</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="/public/style.css" rel="stylesheet">
</head>

<body>


    <div class="container">
        <div class="header bg-secondary">
            <h1><i class="far fa-clipboard"></i>   Moje notatki</h1>
        </div>

        <?php
//        dump($_SESSION['id']);
        if (isset($_SESSION['start'])): ?>
            <div class="wrapper">

                <div class="menu bg-secondary">
                    <div>
                        <a href="/">Strona główna</a>
                    </div>
                    <div>
                        <a href="/?action=create">Nowa notatka</a>
                    </div>
                    <div>
                        <a href="/?action=logout">Wyloguj</a>
                    </div>


                </div>

            <?php endif; ?>

            <div class="page">
                <?php require_once("templates/pages/$page.php"); ?>
            </div>

            </div>


            <div class="footer bg-secondary">
                <p>Notatki - projekt w kursie PHP</p>
            </div>
    </div>

</body>

</html>