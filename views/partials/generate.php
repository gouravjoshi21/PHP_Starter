<?php
function generateMeta ($page) {
    // Default Values
    $page['img'] = isset($page['img']) ? $page['img'] : '/src/img/favicon.png';
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="Description" content="<?= $page['description'] ?>">
    <meta property="og:title" content="<?= $page['title'] ?>">
    <meta property="og:site_name" content="The Typing World">
    <meta property="og:url" content="<?= 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">
    <meta property="og:description" content="<?= $page['description'] ?>">
    <meta property="og:type" content="game">
    <meta property="og:image" content="<?= 'https://' . $_SERVER['HTTP_HOST'] .$page['img'] ?>">
    <meta name="author" content="Gourav Joshi">

    <title><?= $page['title'] ?></title>

    <link rel="icon" type="image/x-icon" href="/src/img/favicon.png">
    <?php
}