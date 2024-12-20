<?php
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $login = htmlspecialchars($_POST['login']);
    $message = htmlspecialchars($_POST['message']);

    if(empty($login) || empty($message))
    {
        die("You must enter a login and message.");
    }

    $profanity = ["\bbadword1\b", "\bbadword2\b", "\bbadword3\b"];
    $discrimination = ["\bdiscrim1\b", "\bdiscrim2\b", "\bdiscrim3\b"];
    $violence = ["\bviolence1\b", "\bviolence2\b", "\bviolence3\b"];
    $spam = ["\bhttp(s)?:\/\/\S+\b", "\bBuy now\b", "\bClick here\b"];
    $punctuation = ["[!?.,;:']"];

    $filters = array_merge($profanity, $discrimination, $violence, $spam, $punctuation);

    foreach ($filters as $pattern)
    {
        $message = preg_replace("/" . $pattern . "/i", "", $message);
    }

    $file = $login . "_" . date("Y-m-d") . ".txt";
    file_put_contents($file, $message);

    $moderated_message = $message;
}
else
{
    $login = "";
    $moderated_message = "";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Moderation</title>
</head>
<body>
    <form method="POST">
        <label for="login">Login:</label><br>
        <input type="text" id="login" name="login"><br><br>

        <label for="message">Message:</label><br>
        <textarea id="message" name="message" rows="5" cols="30"></textarea><br><br>

        <button type="submit">Submit</button>
    </form>
    <?php if(!empty($moderated_message)): ?>
        <h3>Moderated message:</h3>
        <p><?php echo nl2br(htmlspecialchars($moderated_message)); ?></p>
    <?php endif; ?>
</body>
</html>