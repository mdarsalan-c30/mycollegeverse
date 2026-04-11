<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ImageKitService
{
    protected $client;
    protected $publicKey;
    protected $privateKey;
    protected $endpoint;

    public function __construct()
    {
        $this->publicKey = config('services.imagekit.public_key');
        $this->privateKey = config('services.imagekit.private_key');
        $this->endpoint = config('services.imagekit.endpoint');
        $this->client = new Client();
    }

    /**
     * Upload a file to ImageKit
     *
     * @param mixed $file File object or base64 string
     * @param string $fileName
     * @param string $folder
     * @return object|null
     */
    public function upload($file, $fileName, $folder = '/')
    {
        try {
            $response = $this->client->request('POST', 'https://upload.imagekit.io/api/v1/files/upload', [
                'auth' => [$this->privateKey, ''],
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => is_string($file) ? $file : fopen($file->getRealPath(), 'r'),
                    ],
                    [
                        'name' => 'fileName',
                        'contents' => $fileName,
                    ],
                    [
                        'name' => 'folder',
                        'contents' => $folder,
                    ],
                    [
                        'name' => 'useUniqueFileName',
                        'contents' => 'true',
                    ],
                ],
            ]);

            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            Log::error('ImageKit Upload Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get optimized URL for an image
     *
     * @param string $path
     * @param array $options
     * @return string
     */
    public function getUrl($path, $options = [])
    {
        if (empty($path)) return '';
        
        // If it's already a full URL, return it
        if (filter_var($path, FILTER_VALIDATE_URL)) return $path;

        $transformations = [];
        foreach ($options as $key => $value) {
            $transformations[] = "{$key}-{$value}";
        }

        $tr = !empty($transformations) ? '?tr=' . implode(',', $transformations) : '';
        
        return rtrim($this->endpoint, '/') . '/' . ltrim($path, '/') . $tr;
    }

    /**
     * Delete a file from ImageKit by its filePath
     *
     * @param string $filePath
     * @return bool
     */
    public function deleteFile($filePath)
    {
        try {
            // Search for file by path to get its fileId
            $response = $this->client->request('GET', 'https://api.imagekit.io/v1/files', [
                'auth' => [$this->privateKey, ''],
                'query' => ['searchQuery' => "filePath=\"{$filePath}\""],
            ]);

            $files = json_decode($response->getBody()->getContents());

            if (!empty($files) && isset($files[0]->fileId)) {
                $this->client->request('DELETE', "https://api.imagekit.io/v1/files/{$files[0]->fileId}", [
                    'auth' => [$this->privateKey, ''],
                ]);
                return true;
            }
        } catch (\Exception $e) {
            Log::error('ImageKit Delete Error: ' . $e->getMessage());
        }
        return false;
    }
}
