<?php

declare(strict_types=1);

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();
$app->instance('env', 'preview');
config(['app.env' => 'preview', 'app.debug' => false, 'indexing.enabled' => false]);

$origin = 'http://hope-preview-export.test';
$destination = 'https://ikbar1.github.io/hope_charity';
$output = base_path('.local/pages-preview/'.gmdate('Ymd-His'));
if (file_exists($output)) {
    throw new RuntimeException('Output already exists; retry with a new timestamp.');
}
mkdir($output, 0755, true);
$routes = [];
foreach (['', '/en', '/ar'] as $prefix) {
    foreach (['', '/profil', '/program', '/kontak'] as $page) {
        $routes[] = $prefix.$page ?: '/';
    }
}

foreach ($routes as $route) {
    $request = Request::create($origin.$route);
    $response = $kernel->handle($request);
    if ($response->getStatusCode() !== 200) {
        throw new RuntimeException('Export request failed: '.$route);
    }
    $html = $response->getContent();
    $html = preg_replace_callback('/(href|src)="'.preg_quote($origin, '/').'([^"<>]*)"/', function (array $match) use ($destination, $routes): string {
        $path = $match[2];
        if ($path === '' || in_array($path, $routes, true)) {
            $path = rtrim($path, '/').'/';
        }

        return $match[1].'="'.$destination.$path.'"';
    }, $html);
    $html = str_replace('<head>', '<head><meta name="robots" content="noindex, nofollow">', $html);
    if (str_contains($html, $origin) || str_contains($html, '127.0.0.1') || ! str_contains($html, 'demo-ribbon')) {
        throw new RuntimeException('Invalid preview export: '.$route);
    }
    $folder = $output.($route === '/' ? '' : $route);
    if (! is_dir($folder)) {
        mkdir($folder, 0755, true);
    }
    file_put_contents($folder.'/index.html', $html);
    $kernel->terminate($request, $response);
}

$copyTree = function (string $from, string $to) use (&$copyTree): void {
    if (! is_dir($to)) {
        mkdir($to, 0755, true);
    }
    foreach (new DirectoryIterator($from) as $file) {
        if ($file->isDot()) {
            continue;
        }
        if ($file->isDir()) {
            $copyTree($file->getPathname(), $to.'/'.$file->getFilename());
        } else {
            copy($file->getPathname(), $to.'/'.$file->getFilename());
        }
    }
};
foreach (['build', 'images', 'fonts'] as $assets) {
    $copyTree(public_path($assets), $output.'/'.$assets);
}
file_put_contents($output.'/.nojekyll', '');
file_put_contents($output.'/robots.txt', "User-agent: *\nDisallow: /\n");
file_put_contents($output.'/404.html', '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="robots" content="noindex,nofollow"><title>Page not found</title></head><body><h1>404</h1><a href="'.$destination.'/">Hope Charity</a></body></html>');
echo json_encode(['output' => $output, 'pages' => count($routes), 'url' => $destination.'/'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL;
