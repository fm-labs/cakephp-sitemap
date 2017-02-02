<?= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php foreach((array) $this->get('locations') as $location):?>
        <url>
            <loc><?= $this->Url->build($location['loc'],true); ?></loc>
            <?php if ($location['lastmod']): ?>
            <lastmod><?= $location['lastmod']; ?></lastmod>
            <?php endif; ?>
            <?php if ($location['changefreq']): ?>
            <changefreq><?= $location['changefreq']; ?></changefreq>
            <?php endif; ?>
            <?php if ($location['priority']): ?>
            <priority><?= $location['priority']; ?></priority>
            <?php endif; ?>
        </url>
    <?php endforeach; ?>
</urlset>