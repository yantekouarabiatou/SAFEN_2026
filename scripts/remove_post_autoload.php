<?php
$path = '/var/www/html/composer.json';
$json = json_decode(file_get_contents($path), true);
if (isset($json['scripts']['post-autoload-dump'])) {
    unset($json['scripts']['post-autoload-dump']);
    file_put_contents($path, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    echo "post-autoload-dump supprime\n";
} else {
    echo "Rien a supprimer\n";
}