<?php

use App\Helpers\ImageHelper;

if (!function_exists('responsive_product_image')) {
    /**
     * Generate responsive image HTML for product images
     */
    function responsive_product_image($imagePath, $alt, $classes = '', $lazy = false)
    {
        return ImageHelper::responsiveProductImage($imagePath, $alt, $classes, $lazy);
    }
}

if (!function_exists('picture_webp')) {
    /**
     * Generate picture element with WebP and fallback
     */
    function picture_webp($imagePath, $alt, $classes = '', $lazy = false, $width = null, $height = null)
    {
        return ImageHelper::pictureWithWebP($imagePath, $alt, $classes, $lazy, $width, $height);
    }
}
