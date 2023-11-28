<?php
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Define a list of file extensions that should not be processed by the router
$skipExtensions = array('jpg', 'jpeg', 'png', 'gif', 'css', 'js', 'ttf');

$extension = pathinfo($requestPath, PATHINFO_EXTENSION);

// If the requested path has an extension in the skip list, serve the file directly
if (!empty($extension) && $extension !== 'php') {
    return false;
}

// if (!empty($extension) && in_array(strtolower($extension), $skipExtensions)) {
//     return false;
// }

// If the requested file doesn't exist, append ".php" and check again
if (file_exists(__DIR__ . $requestPath . '.php')) {
    include(__DIR__ . $requestPath . '.php');
} else {
    include(__DIR__ . '/index.php');
}
?>
