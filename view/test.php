<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test</title>
</head>
<body>
    <h1><?php echo $currentTest ?></h1>

    <form action="./index.php?result= <?php echo $currentTest ?> " method="post">
    <?php
        $this->questionsShower($questions);
        // foreach($questions as $question) {

        //     echo '<h2>' . $question['question_id'] . ' ' . $question['question_text'] . '</h2>';

        //     // echo $question['question_type'];
        //     if($question['question_type'] == 'single' || $question['question_type'] == 'multi') {
        //         $answers = explode($this->tests->separator, $question['answers']);
        //         // print_r($answers);
        //         if($question['question_type'] == 'multi') {
        //             foreach($answers as $answer) {
        //                 // echo '<p><input required type="checkbox" name="' . $question['question_id'] . '" value="' . $answer . '">' . $answer . '</p>';
        //                 echo '<input type="checkbox" name="' . $question['question_id'] . '" value="' . $answer . '">' . $answer;
        //             }
        //         }
        //         if ($question['question_type'] == 'single') {
        //             foreach($answers as $answer) {
        //                 // echo '<p><input required name="' . $question['question_id'] . '" type="radio" value="' . $answer . '">' . $answer . '</p>';
        //                 echo '<input required name="' . $question['question_id'] . '" type="radio" value="' . $answer . '">' . $answer;
        //             }
        //         }

        //     }
        //     if($question['question_type'] == 'text') {
        //         echo '<textarea required placeholder="Введите свой ответ здесь" rows="3" cols="45" name="' . $question['question_id'] . '"></textarea>';
        //     }
        // }

    ?>
    <div>
        <input type="submit" name="publishing" value="Отправить">
    </div>
    </form>

    <div><a href="./index.php">все тесты</a></div>
</body>
<p></p>
</html>