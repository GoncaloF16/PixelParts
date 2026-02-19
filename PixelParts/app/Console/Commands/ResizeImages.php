<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ResizeImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:resize
                            {--path= : Specific path to resize images (relative to storage/app/public)}
                            {--max-width=1200 : Maximum width in pixels}
                            {--max-height=1200 : Maximum height in pixels}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resize large images to optimal dimensions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting image resizing...');

        $maxWidth = (int) $this->option('max-width');
        $maxHeight = (int) $this->option('max-height');

        // Determine path to scan
        $relativePath = $this->option('path') ?? 'produtos';
        $path = storage_path('app/public/' . $relativePath);

        if (!File::exists($path)) {
            $this->error("Path does not exist: {$path}");
            return 1;
        }

        $this->info("Scanning: {$path}");
        $this->info("Max dimensions: {$maxWidth}x{$maxHeight}");

        $resized = 0;
        $totalSaved = 0;

        $files = File::allFiles($path);

        foreach ($files as $file) {
            $extension = strtolower($file->getExtension());

            // Only process image files
            if (!in_array($extension, ['png', 'jpg', 'jpeg', 'webp'])) {
                continue;
            }

            try {
                $originalSize = $file->getSize();
                $imageInfo = getimagesize($file->getRealPath());

                if (!$imageInfo) {
                    continue;
                }

                [$width, $height] = $imageInfo;

                // Skip if image is already within limits
                if ($width <= $maxWidth && $height <= $maxHeight) {
                    continue;
                }

                // Calculate new dimensions maintaining aspect ratio
                $ratio = min($maxWidth / $width, $maxHeight / $height);
                $newWidth = (int) ($width * $ratio);
                $newHeight = (int) ($height * $ratio);

                // Resize the image
                if ($this->resizeImage($file->getRealPath(), $newWidth, $newHeight, $extension)) {
                    $newSize = filesize($file->getRealPath());
                    $saved = $originalSize - $newSize;
                    $totalSaved += $saved;
                    $resized++;

                    $this->line("✓ {$file->getFilename()} ({$width}x{$height} -> {$newWidth}x{$newHeight}, saved " .
                               number_format($saved / 1024, 2) . " KB)");
                }
            } catch (\Exception $e) {
                $this->error("✗ Failed to resize {$file->getFilename()}: {$e->getMessage()}");
            }
        }

        $this->info("✓ Resizing complete!");
        $this->info("Resized {$resized} images");
        $this->info("Saved approximately " . number_format($totalSaved / 1024, 2) . " KB");

        return 0;
    }

    /**
     * Resize an image
     */
    private function resizeImage($path, $newWidth, $newHeight, $extension)
    {
        $imageInfo = getimagesize($path);
        if (!$imageInfo) {
            return false;
        }

        $mimeType = $imageInfo['mime'];

        // Create image resource based on type
        switch ($mimeType) {
            case 'image/jpeg':
                $source = imagecreatefromjpeg($path);
                break;
            case 'image/png':
                $source = imagecreatefrompng($path);
                break;
            case 'image/webp':
                $source = imagecreatefromwebp($path);
                break;
            default:
                return false;
        }

        if (!$source) {
            return false;
        }

        // Create new image
        $destination = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency for PNG and WebP
        if (in_array($extension, ['png', 'webp'])) {
            imagealphablending($destination, false);
            imagesavealpha($destination, true);
            $transparent = imagecolorallocatealpha($destination, 0, 0, 0, 127);
            imagefilledrectangle($destination, 0, 0, $newWidth, $newHeight, $transparent);
        }

        // Resize
        imagecopyresampled(
            $destination, $source,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            imagesx($source), imagesy($source)
        );

        // Save based on type
        $result = false;
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $result = imagejpeg($destination, $path, 85);
                break;
            case 'png':
                $result = imagepng($destination, $path, 6);
                break;
            case 'webp':
                $result = imagewebp($destination, $path, 85);
                break;
        }

        imagedestroy($source);
        imagedestroy($destination);

        return $result;
    }
}
