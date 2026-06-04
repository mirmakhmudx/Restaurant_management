<?php
$file = __DIR__ . '/resources/views/layouts/app.blade.php';
$c = file_get_contents($file);

// 1. Dark CSS blokini o'chirish
$c = preg_replace('/<style>\s*html\.dark.*?<\/style>/s', '', $c);

// 2. Dark mode init script ni o'chirish
$c = preg_replace('/<script>\s*\/\/ Dark mode init.*?<\/script>/s', '', $c);

// 3. Dark toggle button ni o'chirish
$c = preg_replace('/\{\{-- Dark Mode --\}\}.*?<\/button>/s', '', $c);

// 4. Body tagini tozalash
$c = str_replace(
    '<body class="bg-gray-50 antialiased dark:bg-gray-950 transition-colors">',
    '<body class="bg-gray-50 antialiased">',
    $c
);

file_put_contents($file, $c);
echo "Dark mode tozalandi!\n";
