<?php echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php foreach((array) $this->get('locations') as $location):?>
        <url>
            <loc><?php echo $location['loc']; ?></loc>
            <?php if ($location['lastmod']): ?>
            <lastmod><?php echo $location['lastmod']; ?></lastmod>
            <?php endif; ?>
            <?php if ($location['changefreq']): ?>
            <changefreq><?php echo $location['changefreq']; ?></changefreq>
            <?php endif; ?>
            <?php if ($location['priority']): ?>
            <priority><?php echo $location['priority']; ?></priority>
            <?php endif; ?>
        </url>
    <?php endforeach; ?>
</urlset>