<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class OptimizeImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:optimize {--path= : Specific path to optimize (relative to public)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert images to WebP format for better performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting image optimization...');

        // Determine paths to scan
        $paths = $this->option('path')
            ? [public_path($this->option('path'))]
            : [
                public_path('images'),
                storage_path('app/public/produtos'),
            ];

        $totalConverted = 0;
        $totalSaved = 0;

        foreach ($paths as $path) {
            if (!File::exists($path)) {
                $this->warn("Path does not exist: {$path}");
                continue;
            }

            $this->info("Scanning: {$path}");
            [$converted, $saved] = $this->optimizeDirectory($path);
            $totalConverted += $converted;
            $totalSaved += $saved;
        }

        $this->info("✓ Optimization complete!");
        $this->info("Converted {$totalConverted} images");
        $this->info("Saved approximately " . number_format($totalSaved / 1024, 2) . " KB");
    }

    /**
     * Optimize images in a directory
     */
    private function optimizeDirectory($directory)
    {
        $converted = 0;
        $totalSaved = 0;

        $files = File::allFiles($directory);

        foreach ($files as $file) {
            $extension = strtolower($file->getExtension());

            // Only process PNG and JPG files
            if (!in_array($extension, ['png', 'jpg', 'jpeg'])) {
                continue;
            }

            // Skip if WebP already exists
            $webpPath = $file->getPath() . '/' . $file->getFilenameWithoutExtension() . '.webp';
            if (File::exists($webpPath)) {
                continue;
            }

            try {
                $originalSize = $file->getSize();

                // Use GD library to convert to WebP
                $this->convertToWebP($file->getRealPath(), $webpPath);

                if (File::exists($webpPath)) {
                    $newSize = filesize($webpPath);
                    $saved = $originalSize - $newSize;
                    $totalSaved += $saved;
                    $converted++;

                    $this->line("✓ {$file->getFilename()} -> " . basename($webpPath) .
                               " (saved " . number_format($saved / 1024, 2) . " KB)");
                }
            } catch (\Exception $e) {
                $this->error("✗ Failed to convert {$file->getFilename()}: {$e->getMessage()}");
            }
        }

        return [$converted, $totalSaved];
    }

    /**
     * Convert image to WebP format
     */
    private function convertToWebP($sourcePath, $destPath)
    {
        $imageInfo = getimagesize($sourcePath);
        if (!$imageInfo) {
            throw new \Exception('Invalid image file');
        }

        $mimeType = $imageInfo['mime'];

        // Create image resource based on type
        switch ($mimeType) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($sourcePath);
                // Preserve transparency
                imagealphablending($image, false);
                imagesavealpha($image, true);
                break;
            default:
                throw new \Exception("Unsupported image type: {$mimeType}");
        }

        if (!$image) {
            throw new \Exception('Failed to create image resource');
        }

        // Convert to WebP with quality 85 (good balance between size and quality)
        $result = imagewebp($image, $destPath, 85);
        imagedestroy($image);

        if (!$result) {
            throw new \Exception('Failed to save WebP image');
        }
    }
}
