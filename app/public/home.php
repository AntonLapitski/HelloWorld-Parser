<!DOCTYPE html>
<html>
<head>
    <title>Page Title</title>
</head>
<body>
<h1>Hello World</h1>

<form action="parser.php" method="post">

    <?php

    for ($i = 0; $i < $quantity; $i++) {
        echo "Please input " . $inputs[$i] . ":" . "<input type=\"text\" name=\"$userVariables[$i]\">" . "</br>";
    }

    ?>

    <input type="submit" value="Submit">
</form>
</body>