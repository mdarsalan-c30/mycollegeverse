<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\Note;
use App\Models\JobPosting;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $colleges = College::all();
        $notes = Note::all();
        $jobs = JobPosting::where('is_approved', true)->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Static Pages
        $urls = [
            ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => route('notes.index'), 'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => route('colleges.index'), 'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => route('jobs.index'), 'priority' => '0.9', 'changefreq' => 'daily'],
        ];

        foreach ($urls as $url) {
            $xml .= "<url><loc>{$url['loc']}</loc><priority>{$url['priority']}</priority><changefreq>{$url['changefreq']}</changefreq></url>";
        }

        // Dynamic Colleges
        foreach ($colleges as $college) {
            $loc = route('colleges.show', $college->slug ?? $college->id);
            $lastmod = $college->updated_at->toAtomString();
            $xml .= "<url><loc>{$loc}</loc><lastmod>{$lastmod}</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>";
        }

        // Dynamic Notes
        foreach ($notes as $note) {
            $loc = route('notes.show', $note->slug ?? $note->id);
            $lastmod = $note->updated_at->toAtomString();
            $xml .= "<url><loc>{$loc}</loc><lastmod>{$lastmod}</lastmod><changefreq>weekly</changefreq><priority>0.7</priority></url>";
        }

        // Dynamic Jobs
        foreach ($jobs as $job) {
            $loc = route('jobs.show', $job->slug ?? $job->id);
            $lastmod = $job->updated_at->toAtomString();
            $xml .= "<url><loc>{$loc}</loc><lastmod>{$lastmod}</lastmod><changefreq>weekly</changefreq><priority>0.7</priority></url>";
        }

        $xml .= '</urlset>';

        return response($xml)->header('Content-Type', 'text/xml');
    }
}
