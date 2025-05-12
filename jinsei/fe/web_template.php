<?php
use Framework\App;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="/jinsei/fe/css/reset.css">
    <link rel="stylesheet" type="text/css" href="/jinsei/fe/css/app.css">
    <title><?= App::$name ?></title>
</head>

<body class="bg1" style="max-height: 100vh">

<!-- Navigation -->
<?php if (isset($_COOKIE['_a'])): ?>
    
    <div class="flex border_b1 align_center" style="border-bottom: 2px solid var(--color10);">
        <a href="/documents" class="block p1 pl2 hover_fc1 fs4 yuji"><?= App::$name ?></a>
        <div class="flex justify_end w100 px1">
            <a href="/logout" class="block btn1 p1">Logout</a>
        </div>
    </div>

<?php endif ?>

@section('body')

<!-- Notification -->
<?php if (isset($notification) && strlen($notification) > 0): ?>

    <div id="notification" class="pos_absolute border1 bg2 p4"
        style="top: 0; left: 0; right: 0; margin-inline: auto; width: fit-content;
        opacity: 0.0; animation-name: notification; animation-duration: 8s">
        <?= $notification ?>
    </div>

<?php endif ?>

</body>
</html>