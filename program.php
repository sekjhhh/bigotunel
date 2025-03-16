<?php
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$fullUrl = $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

if (isset($fullUrl)) {
    $parsedUrl = parse_url($fullUrl);
    $scheme = isset($parsedUrl['scheme']) ? $parsedUrl['scheme'] : '';
    $host = isset($parsedUrl['host']) ? $parsedUrl['host'] : '';
    $path = isset($parsedUrl['path']) ? $parsedUrl['path'] : '';
    $baseUrl = $scheme . "://" . $host . $path;
    $urlAsli = str_replace("program.php", "", $baseUrl);

    $judulFile = "cola.txt";

    if (!file_exists($judulFile)) {
        echo "File cola.txt tidak ditemukan.";
        exit();
    }

    $sitemapFile = fopen("halaman.xml", "w");
    fwrite($sitemapFile, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL);
    fwrite($sitemapFile, '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL);

    $fileLines = file($judulFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $lastmod = date('Y-m-d');

    foreach ($fileLines as $judul) {
        $sitemapLink = $urlAsli . '?ninja=' . urlencode($judul);
        fwrite($sitemapFile, '  <url>' . PHP_EOL);
        fwrite($sitemapFile, '    <loc>' . $sitemapLink . '</loc>' . PHP_EOL);
        fwrite($sitemapFile, '    <lastmod>' . $lastmod . '</lastmod>' . PHP_EOL);
        fwrite($sitemapFile, '    <changefreq>daily</changefreq>' . PHP_EOL);
        fwrite($sitemapFile, '    <priority>0.8</priority>' . PHP_EOL);
        fwrite($sitemapFile, '  </url>' . PHP_EOL);
    }

    fwrite($sitemapFile, '</urlset>' . PHP_EOL);
    fclose($sitemapFile);

    echo "Sitemap telah dibuat.";
} else {
    echo "URL saat ini tidak didefinisikan.";
}
?>
