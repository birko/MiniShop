<?php

namespace Core\MediaBundle\Entity;

class ImageManipulation
{
    public static function createResource($file = null)
    {
        $im = new \Imagick($file);
        return $im;
    }
    
    public static function saveResource($imageResource, $path, $format="jpeg", $quality = 80)
    {
        $imageResource->unsharpMaskImage(2, 1.414, 0.5, 0.05/*, \Imagick::CHANNEL_ALL */);
        $imageResource->setImageCompressionQuality($quality);
        $imageResource->setImageFormat($format);
        $imageResource->writeImage($path);
        return $imageResource;
    }
    
    public static function checkResource($imageResource)
    {
        $save = false;
        switch ($imageResource->getImageType()) 
        {
            case IMAGETYPE_GIF: // gif
            case IMAGETYPE_JPEG: // jpeg
            case IMAGETYPE_JPEG2000: //jpeg2000
            case IMAGETYPE_SWF: // swf nejake tif bralo ako flash
            case IMAGETYPE_PSD: // gifka vracia ako PSD
            case IMAGETYPE_BMP: //bmp
            case IMAGETYPE_WBMP: //bmp
            case IMAGETYPE_PNG: // png
            case IMAGETYPE_TIFF_II: //tiff
            case IMAGETYPE_TIFF_MM: //tiff
                $save = true;
                break;
        }
        return $save;
    }
    
    public static function cutResource($imageResource, $width, $height)
    {
        $w = $imageResource->getImageWidth();
        $h = $imageResource->getImageHeight();
        if (($w / $h) > ($width / $height)) 
        {
            $y = 0;
            $h = $imageResource->getImageHeight();
            $w = $imageResource->getImageHeight() * $width / $height;
            $x = $imageResource->getImageWidth() / 2 - $w / 2;
        } 
        else 
        {
            $x = 0;
            $w = $imageResource->getImageWidth();
            $h = $imageResource->getImageWidth() * $height / $width;
            $y = $imageResource->getImageHeight() / 2 - $h / 2;
        }   
        $imageResource->cropImage($w, $h, $x, $y);
        $imageResource->scaleImage($width, $height);
        $imageResource->stripImage();
        return $imageResource;
    }
    
    public static function resizeResource($imageResource, $width, $height, $enlarge = false)
    {
        $w = $imageResource->getImageWidth();
        $h = $imageResource->getImageHeight();
        if ((($imageResource->getImageWidth() >= $width) || ($imageResource->getImageHeight() >= $height)) || $enlarge)
        {
            if (($w / $h) > ($width / $height)) 
            {
                $tempHeight = $h * $width / $w;
                $imageResource->scaleImage($width, $tempHeight);
            } 
            else 
            {
                $tempWidth = $w * $height / $h;
                $imageResource->scaleImage($tempWidth, $height);
            }
        }
        return $imageResource;
    }
    
    public static function cropResource($imageResource, $width, $height)
    {
        if (($imageResource->getImageWidth() / $imageResource->getImageHeight()) > ($width / $height)) 
        {
            $y = 0;
            $crop_height = $imageResource->getImageHeight();
            $crop_width = $imageResource->getImageHeight() * $width / $height;
            $x = $im->getImageWidth() / 2 - $crop_width / 2;
        } 
        else 
        {
            $x = 0;
            $crop_width = $imageResource->getImageWidth();
            $crop_height = $imageResource->getImageWidth() * $height / $width;
            $y = $im->getImageHeight() / 2 - $crop_height / 2;
        }  
        $imageResource->cropImage($crop_width, $crop_height, $x, $y);
        $imageResource->scaleImage($width, $height);
        return  $imageResource;
    }
    
    public static function fillResource($imageResource, $width, $height, $color = array(0, 0, 0))
    {
        $im = self::createResource();
        $color_string = "rgb (" . $color[0] . "," . $color[1] . "," . $color[2] . ")";
        $im->newImage($width, $height, $color_string);
        $new_w = $imageResource->getImageWidth();
        $new_h = $imageResource->getImageHeight();
        $target_x = ($width - $new_w) / 2;
        $target_y = ($height - $new_h) / 2;
        $im->compositeImage($imageResource, \Imagick::COMPOSITE_DEFAULT, $target_x, $target_y);
        return $im;
    }
    
    public static function watermarkResource($imageResource, $watermarkFile) 
    {
        if (file_exists($watermarkFile)) 
        {
            $dest_width = $imageResource->getImageWidth();
            $dest_height = $imageResource->getImageHeight();
            $watermark = self::resizeResource(self::createResource($watermarkFile), $dest_width, $dest_height);
            $watermark_width = $watermark->getImageWidth();
            $watermark_height = $watermark->getImageHeight();
            $target_x = $dest_width / 2 - $watermark_width / 2;
            $target_y = $dest_height / 2 - $watermark_height / 2;
            $imageResource->compositeImage($watermark, \Imagick::COMPOSITE_DEFAULT, $target_x, $target_y);
        }
        return $imageResource;
    }
    
    public static function unsharpMaskResource($imageResource, $amount, $radius, $threshold) 
    {
        $imageResource->unsharpMaskImage($radius, 0.5, $amount, $threshold);
        return $imageResource;
    }
    
    public static function greyscaleResource($imageResource)
    {
        $imageResource->setImageColorspace(2);
        return $imageResource;
    }
    
    public static function sepiaResource($imageResource)
    {
        $imageResource->sepiaToneImage(80);
        return $imageResource;
    }
}
?>