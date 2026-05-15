{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
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
    <url>
        <loc>{{ url('/notes') }}</loc>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ url('/resumes') }}</loc>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ url('/blog') }}</loc>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ url('/jobs') }}</loc>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ url('/academic-hub') }}</loc>
        <priority>0.9</priority>
    </url>

    <!-- Faculty Nodes -->
    @foreach ($professors as $professor)
    <url>
        <loc>{{ route('professors.show', $professor->slug) }}</loc>
        <lastmod>{{ $professor->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach

    <!-- Academic Resources (Notes) -->
    @foreach ($notes as $note)
    <url>
        <loc>{{ route('notes.show', $note->slug ?? $note->id) }}</loc>
        <lastmod>{{ $note->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

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

    <!-- Academic Hub Manifests -->
    @foreach ($guides as $guide)
    <url>
        <loc>{{ route('guides.show', $guide->slug) }}</loc>
        <lastmod>{{ $guide->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

    <!-- Career Nodes (Jobs) -->
    @foreach ($jobs as $job)
    <url>
        <loc>{{ route('jobs.show', $job->id) }}</loc>
        <lastmod>{{ $job->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

    <!-- Static Identity Nodes (Pages) -->
    @foreach ($pages as $page)
    <url>
        <loc>{{ route('pages.show', $page->slug) }}</loc>
        <lastmod>{{ $page->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    @endforeach
</urlset>
