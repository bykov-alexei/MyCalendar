<html>
<head>
    <meta charset='utf-8'>
    <link rel='stylesheet' href='views/style.css'>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.3.3/vue.min.js"></script>
    <script type='text/javascript' src='views/cookies.js'></script>
    <title><?= $title ?></title>
</head>
<body>
    <div id="header">
        <div class='content'>
          <div class='links'>
                <a href='/tasks.php?edit'>Создать задачу</a>
                <a href='/tasks.php?list'>Просмотреть задачи</a>
                <a href='/index.php' onclick="setCookie('token', '');">Выйти</a>
            </div>
            <div>
                <h1>Мой календарь</h1>
            </div>  
        </div>
    </div>
    <div id="wrapper">
