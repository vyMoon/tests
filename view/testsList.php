<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tests List</title>
</head>
<body>
    <h1>Список доступных тестов</h1>
    <?php
        $this->testsListShower($activeTests);
        // if ($activeTests != 0) {
        //     foreach ($activeTests as $testName) {
        //         echo '<p><a href="./index.php?test='.$testName['test_name'].'">' . $testName['test_name'] . '</a></p>';
                
        //     }
        // }

        // for ($i = 1; $i <= $pages; $i += 1) {
        //     echo $i > $pages;
        //     if ($i == $_GET['page']) {
        //         echo '<span>' . $i . '</span>';
        //     } else {
        //         echo '<a href="./index.php?page=' . $i . '">' . $i . '</a>';
        //     }
        // }
    ?>

</body>
</html>