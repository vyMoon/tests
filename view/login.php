<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <nav class="navbar navbar-light bg-light">
        <a class="navbar-brand" href="./index.php">
            <img src="./img/t.jpg" width="30" height="30" alt="">
            TESTS
        </a>
        <a class="navbar-brand" href="./index.php?action=registration">
            Зарегистрироваться
        </a>
    </nav>
    <div class="card">
        <div class="card-body">
            <h1>Авторизация</h1>
            <p><?php echo $message; ?></p>
            <form action="./controller/controller.php" method="post">
                <div class="form-group">
                    <label for="exampleInputEmail1">Логин</label>
                    <input required type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Введите ваш логин" name="login">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Пароль</label>
                    <input required type="password" class="form-control" id="exampleInputPassword1" placeholder="Пароль" name="pass">
                </div>
                <button type="submit" class="btn btn-primary">Войти</button>
            </form>
        </div>
    </div>
    
</body>
</html>