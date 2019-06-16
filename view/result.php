<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Result</title>
</head>
<body>
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">Ваш результат</h1>
            <?php $this->reustShower($report) ?>
            <p>
                <a class="btn btn-primary" href="./index.php?page=1">Выполнить еще</a>
            </p>
        </div>
    </div>
    
</body>
</html>