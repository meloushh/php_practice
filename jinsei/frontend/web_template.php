<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" type="text/css" href="/jinsei/frontend/reset.css">
<link rel="stylesheet" type="text/css" href="/jinsei/frontend/app.css">
<title><?= App::$si->name ?></title>
</head>
<body class="bg1">
@section('body')

<?php if (isset($message) && strlen($message) > 0): ?>

    <div id="notification" class="pos_absolute border1 bg2 p4"
        style="top: 0; left: 0; right: 0; margin-inline: auto; width: fit-content;
        opacity: 0.0; animation-name: notification; animation-duration: 8s">
        <?= $message ?>
    </div>

<?php endif ?>

</body>
</html>