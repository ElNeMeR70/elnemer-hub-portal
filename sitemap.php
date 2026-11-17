<?php
/**
 * Dynamic XML Sitemap Generator for all 7,201+ Sovereign Tools
 * Architected by Eng. Mahmoud Bek ElNemer (@ElNeMeR70)
 */

header('Content-Type: application/xml; charset=utf-8');

$jsonFile = __DIR__ . '/tools.json';
$tools = [];
if (file_exists($jsonFile)) {
    $raw = file_get_contents($jsonFile);
    $data = json_decode($raw, true);
    if (isset($data['tools'])) {
        $tools = $data['tools'];
    }
}

$now = date('Y-m-d');

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <!-- Homepage Mega Hub -->
  <url>
    <loc>https://elnemer-ai.com/megahub/</loc>
    <lastmod><?= $now ?></lastmod>
    <changefreq>daily</changefreq>
    <priority>1.0</priority>
  </url>
  <url>
    <loc>https://hub.elnemer-ai.com/</loc>
    <lastmod><?= $now ?></lastmod>
    <changefreq>daily</changefreq>
    <priority>1.0</priority>
  </url>

  <!-- Individual Sovereign Tools SEO Pages -->
  <?php foreach ($tools as $t): ?>
  <url>
    <loc>https://elnemer-ai.com/megahub/tool.php?name=<?= htmlspecialchars(urlencode($t['n'])) ?></loc>
    <lastmod><?= $now ?></lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
  <?php endforeach; ?>
</urlset>
