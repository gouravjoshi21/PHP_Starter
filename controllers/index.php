<?php
view("index.view.php", [
    'head' => [
        'tags' => ['<script>console.log("Script tag")</script>'],
        'strict' => false
    ],
    'page' => [
        'title' => "Welcome!",
        'description' => "Write description here..."
    ]
]);