<?php
/*
|--------------------------------------------------------------------------
| Remove Laravel Comments
|--------------------------------------------------------------------------
|
| Just made a new Laravel project, but don't want all those big
| comment blocks? Put this in the root of your project and run
| "php remove_laravel_comments.php"
|
*/

$directories = [
    'app',
    'bootstrap',
    'config',
    'database',
    'public',
    'resources',
    'routes',
];

$base = './';

foreach ($directories as $dir) {
    $it = new RecursiveDirectoryIterator($base . $dir);
    foreach (new RecursiveIteratorIterator($it) as $file) {
        if ($file->getExtension() == 'php') {
            echo "Removing comments from: " . $file->getRealPath() . "\n";
            $contents = file_get_contents($file->getRealPath());
            $new = preg_replace('/^(\{?)\s*?\/\*(.|[\r\n])*?\*\/([\r\n]+$|$)/im', '$1', $contents);
            file_put_contents($file->getRealPath(), $new);
        }
    }
}
