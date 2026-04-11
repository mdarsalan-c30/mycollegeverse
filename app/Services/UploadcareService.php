<?php

namespace App\Services;

class UploadcareService
{
    protected $publicKey;
    protected $secretKey;

    public function __construct()
    {
        $this->publicKey = env('UPLOADCARE_PUBLIC_KEY');
        $this->secretKey = env('UPLOADCARE_SECRET_KEY');
    }

    /**
     * Get the public key for frontend widget.
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * Convert a UUID or Uploadcare URL to a clean CDN URL.
     * Hardened to handle protocols but avoids transformations that might fail on free/restricted tiers.
     */
    public function getCdnUrl($fileId)
    {
        if (!$fileId) return null;

        // 1. Ensure Protocol (Fixes // and missing https)
        if (strpos($fileId, '//') === 0) {
            $fileId = 'https:' . $fileId;
        } elseif (!preg_match('/^https?:\/\//', $fileId)) {
            // Assume it's a UUID
            $fileId = "https://ucarecdn.com/{$fileId}/";
        }

        // 2. Clear any existing transformations to ensure we serve the original raw file
        // which is the most compatible for all Uploadcare tiers.
        $cleanUrl = explode('/-/', $fileId)[0];
        
        // 3. Ensure trailing slash for consistent format
        return rtrim($cleanUrl, '/') . '/';
    }
}
