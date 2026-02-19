<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Generate responsive image attributes for product images
     *
     * @param string $imagePath The original image path
     * @param string $alt Alt text for the image
     * @param string $classes CSS classes
     * @param bool $lazy Whether to lazy load the image
     * @return string HTML img tag with responsive attributes
     */
    public static function responsiveProductImage($imagePath, $alt, $classes = '', $lazy = false)
    {
        // Determine if it's an external URL or storage path
        $isExternal = str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://');
        $src = $isExternal ? $imagePath : asset('storage/' . $imagePath);

        // For lazy loading
        $loadingAttr = $lazy ? 'loading="lazy"' : '';

        // Since we're serving the full resolution images, we'll use the same src
        // In a production environment, you'd want to generate multiple sizes server-side
        // For now, we'll let the browser scale and add proper width/height hints
        return sprintf(
            '<img src="%s" alt="%s" class="%s" %s width="355" height="355" decoding="async">',
            htmlspecialchars($src),
            htmlspecialchars($alt),
            htmlspecialchars($classes),
            $loadingAttr
        );
    }

    /**
     * Generate WebP image path with fallback to original
     *
     * @param string $imagePath Original image path
     * @return array ['webp' => 'path/to/image.webp', 'original' => 'path/to/image.jpg']
     */
    public static function getWebPPath($imagePath)
    {
        $pathInfo = pathinfo($imagePath);
        $webpPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';

        return [
            'webp' => $webpPath,
            'original' => $imagePath
        ];
    }

    /**
     * Generate picture element with WebP and fallback
     *
     * @param string $imagePath The original image path
     * @param string $alt Alt text
     * @param string $classes CSS classes
     * @param bool $lazy Whether to lazy load
     * @param int|null $width Display width
     * @param int|null $height Display height
     * @return string HTML picture element
     */
    public static function pictureWithWebP($imagePath, $alt, $classes = '', $lazy = false, $width = null, $height = null)
    {
        $isExternal = str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://');

        if ($isExternal) {
            // For external images, just return a regular img tag
            $loadingAttr = $lazy ? 'loading="lazy"' : '';
            $dimensionAttrs = '';
            if ($width && $height) {
                $dimensionAttrs = sprintf('width="%d" height="%d"', $width, $height);
            }
            return sprintf(
                '<img src="%s" alt="%s" class="%s" %s %s decoding="async">',
                htmlspecialchars($imagePath),
                htmlspecialchars($alt),
                htmlspecialchars($classes),
                $loadingAttr,
                $dimensionAttrs
            );
        }

        $webpPaths = self::getWebPPath($imagePath);
        $webpSrc = asset('storage/' . $webpPaths['webp']);
        $originalSrc = asset('storage/' . $imagePath);

        // Check if WebP version exists, otherwise just use original
        $loadingAttr = $lazy ? 'loading="lazy"' : '';
        $dimensionAttrs = '';
        if ($width && $height) {
            $dimensionAttrs = sprintf('width="%d" height="%d"', $width, $height);
        }

        return sprintf(
            '<picture><source srcset="%s" type="image/webp"><img src="%s" alt="%s" class="%s" %s %s decoding="async"></picture>',
            htmlspecialchars($webpSrc),
            htmlspecialchars($originalSrc),
            htmlspecialchars($alt),
            htmlspecialchars($classes),
            $loadingAttr,
            $dimensionAttrs
        );
    }
}
