<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Test</title>
</head>
<body>
    <div class="card">
        <div class="card-body">
            <h1><?php echo $currentTest ?></h1>

            <form action="./index.php?result=<?php echo $currentTest ?>" method="post">
            <?php
                $this->questionsShower($questions);
            ?>
            <div>
                <input type="submit" name="" value="Отправить" class="btn btn-primary btn-lg btn-block">
            </div>
            </form>

            <div><a class="btn btn-secondary btn-lg btn-block" role="button" aria-pressed="true" href="./index.php">все тесты</a></div>
        </div>
    </div>
</body>
<p></p>
</html>