<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Watermarkable
{
    /**
     * Generate a Cloudinary URL with dynamic watermarks.
     * Center: Logo
     * Bottom Left: Downloaded from mycollegeverse.in
     * Bottom Right: Author name
     */
    public function getWatermarkedPdfUrl($asDownload = false, $authorName = null)
    {
        $url = $this->file_path;

        // Only works for Cloudinary URLs
        if (!Str::contains($url, 'cloudinary.com')) {
            return $url;
        }

        $name = $authorName ?? ($this->user->name ?? 'MCV Archivist');
        
        // Sanitize name for Cloudinary text (escape special characters)
        $safeName = str_replace(['/', ',', '.'], ' ', $name);
        $authorText = urlencode("Verified Author: " . $safeName);
        $siteText = urlencode("Downloaded from mycollegeverse.in");

        $transformations = "l_mcv_watermark_logo,o_15,w_500,g_center/" .
                          "l_text:Arial_16_bold:{$siteText},g_south_west,x_30,y_30,co_rgb:94a3b8/" .
                          "l_text:Arial_16_bold:{$authorText},g_south_east,x_30,y_30,co_rgb:94a3b8/";

        if ($asDownload) {
            $transformations .= "fl_attachment/";
        }

        // Inject into the URL
        if (Str::contains($url, '/upload/')) {
            return Str::replace('/upload/', "/upload/{$transformations}", $url);
        }

        return $url;
    }
}
