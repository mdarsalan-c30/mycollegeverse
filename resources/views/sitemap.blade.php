<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Main Nodes -->
    <url>
        <loc>{{ url('/') }}</loc>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ url('/colleges') }}</loc>
        <priority>0.9</priority>
    </url>

    <!-- Institutional Nodes (Colleges) -->
    @foreach ($colleges as $college)
        <url>
            <loc>{{ route('colleges.show', $college->slug) }}</loc>
            <lastmod>{{ $college->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach

    <!-- Knowledge Artifacts (Blogs) -->
    @foreach ($blogs as $blog)
        <url>
            <loc>{{ route('blogs.show', $blog->slug) }}</loc>
            <lastmod>{{ $blog->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach
</urlset>
