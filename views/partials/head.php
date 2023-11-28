<?php require 'generate.php' ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?= generateMeta ($page) ?>

    <?php 
        if (($head['tags'] ?? false) && ($head['strict'] ?? false))
            foreach ($head['tags'] as $tag) 
                echo $tag;

        else {
            require_once base_Path('views/partials/commonHead.php');

            if ($head['tags'] ?? false)
                foreach ($head['tags'] as $tag) 
                    echo $tag;
        }
    ?>
</head>
<body>