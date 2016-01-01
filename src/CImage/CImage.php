<?php
/**
 * Image manipulation class
 */
class CImage
{
    /**
    * Properties
    *
    */
    // Constants
    static private $maxWidth = 2000;
    static private $maxHeight = 2000;

    // Properties to hold query string parameters
    private $src        = null;
    private $verbose    = true;
    public $saveAs     = null;
    private $quality    = null;
    private $ignoreCache = null;
    private $newWidth   = null;
    private $newHeight  = null;
    private $cropToFit  = null;
    private $sharpen    = null;

    //Properties for temporary storage
    private $pathToImage    = null;
    public $fileExtension = null;
    private $imgInfo = null;
    public $width = null;
    public $height = null;
    public $cropWidth = null;
    public $cropHeight  = null;

    /**
     * Create image processing object
     * @param string $params from e.g. url with processing commands and options.
     * $params = array(
     *                  'src' -> $SourceImageFile,  // Relative path to file
     *                 ['verbose' -> $verbose,      // true | false
     *                  'save-as' -> $SaveAs,       // png | jpg | jpeg
     *                  'quality' -> $quality,      // 0..100
     *                  'no-cache' -> $NoCache,     // true | false, true forces cache file generation
     *                  'width' -> $width,          // width of output file
     *                  'height' -> $height,        // height of output file
     *                  'crop-to-fit' -> $CropToFit, // true | false
     *                  'sharpen' -> $sharpen,]     // true | false
     *                                                   );
     */
    function __construct($params)
    {
        $this->StoreParams($params);
        $this->ValidateParams();
    }

    /**
     * Store processing commands
     * @param string $params from e.g. url with processing commands and options.
     * @return resource $image as the processed image.
     */
    private function StoreParams($params)
    {
        $this->src        = isset($params['src'])     ? $params['src']      : null;
        $this->verbose    = isset($params['verbose']) ? true              : null;
        $this->saveAs     = isset($params['save-as']) ? $params['save-as']  : null;
        $this->quality    = isset($params['quality']) ? $params['quality']  : 60;
        $this->ignoreCache = isset($params['no-cache']) ? true           : null;
        $this->newWidth   = isset($params['width'])   ? $params['width']    : null;
        $this->newHeight  = isset($params['height'])  ? $params['height']   : null;
        $this->cropToFit  = isset($params['crop-to-fit']) ? true : null;
        $this->sharpen    = isset($params['sharpen']) ? true : null;

        $this->pathToImage = realpath(IMG_PATH . $this->src);
    }

    /**
     * Validate processing commands
     */
     private function ValidateParams()
    {
        is_dir(IMG_PATH) or errorMessage('The image dir is not a valid directory.');
        is_writable(CACHE_PATH) or errorMessage('The cache dir is not a writable directory.');
        isset($this->src) or errorMessage('Must set src-attribute.');
        preg_match('#^[a-z0-9A-Z-_\.\/]+$#', $this->src) or errorMessage('Filename contains invalid characters.');
        substr_compare(IMG_PATH, $this->pathToImage, 0, strlen(IMG_PATH)) == 0 or errorMessage('Security constraint: Source image is not directly below the directory IMG_PATH.');
        is_null($this->saveAs) or in_array($this->saveAs, array('png', 'jpg', 'jpeg')) or errorMessage('Not a valid extension to save image as');
        is_null($this->quality) or (is_numeric($this->quality) and $this->quality > 0 and $this->quality <= 100) or errorMessage('Quality out of range');
        is_null($this->newWidth) or (is_numeric($this->newWidth) and $this->newWidth > 0 and $this->newWidth <= self::$maxWidth) or errorMessage('Width out of range');
        is_null($this->newHeight) or (is_numeric($this->newHeight) and $this->newHeight > 0 and $this->newHeight <= self::$maxHeight) or errorMessage('Height out of range');
        is_null($this->cropToFit) or ($this->cropToFit and $this->newWidth and $this->newHeight) or errorMessage('Crop to fit needs both width and height to work');
        //
        // Start displaying log if verbose mode & create url to current image
        //
        if($this->verbose) {
          $tquery = array();
          parse_str($_SERVER['QUERY_STRING'], $tquery);
          unset($tquery['verbose']);
          $url = '?' . http_build_query($tquery);

          echo <<<EOD
<html lang='en'>
<meta charset='UTF-8'/>
<title>img.php verbose mode</title>
<h1>Verbose mode</h1>
<p><a href=$url><code>$url</code></a><br>
<img src='{$url}' /></p>
EOD;
        }
    }


    /**
     * Get information on the image
     *
     * @param string path to image
     */
    public function Information()
    {
        $this->imgInfo = list($width, $height, $type, $attr) = getimagesize($this->pathToImage);
        !empty($this->imgInfo) or errorMessage("The file doesn't seem to be an image.");
        $mime = $this->imgInfo['mime'];

        if($this->verbose) {
          $this->imgInfo['filesize'] = $filesize = filesize($this->pathToImage);
          verbose("Image file: {$this->pathToImage}");
          verbose("Image information: " . print_r($this->imgInfo, true));
          verbose("Image width x height (type): {$width} x {$height} ({$type}).");
          verbose("Image file size: {$filesize} bytes.");
          verbose("Image mime type: {$mime}.");
        }
    }

    /**
     * Calculate new width and height for the image
     */
    public function CalcWidthHeight()
    {
        list($this->width, $this->height) = $this->imgInfo;

        $aspectRatio = $this->width / $this->height;

        if($this->cropToFit && $this->newWidth && $this->newHeight) {
          $targetRatio = $this->newWidth / $this->newHeight;
          $this->cropWidth   = $targetRatio > $aspectRatio ? $this->width : round($this->height * $targetRatio);
          $this->cropHeight  = $targetRatio > $aspectRatio ? round($this->width  / $targetRatio) : $this->height;
          if($this->verbose) { verbose("Crop to fit into box of {$this->newWidth}x{$this->newHeight}. Cropping dimensions: {$this->cropWidth}x{$this->cropHeight}."); }
        }
        else if($this->newWidth && !$this->newHeight) {
          $this->newHeight = round($this->newWidth / $aspectRatio);
          if($this->verbose) { verbose("New width is known {$this->newWidth}, height is calculated to {$this->newHeight}."); }
        }
        else if(!$this->newWidth && $this->newHeight) {
          $this->newWidth = round($this->newHeight * $aspectRatio);
          if($this->verbose) { verbose("New height is known {$this->newHeight}, width is calculated to {$this->newWidth}."); }
        }
        else if($this->newWidth && $this->newHeight) {
          $ratioWidth  = $this->width  / $this->newWidth;
          $ratioHeight = $this->height / $this->newHeight;
          $ratio = ($ratioWidth > $ratioHeight) ? $ratioWidth : $ratioHeight;
          $this->newWidth  = round($this->width  / $ratio);
          $this->newHeight = round($this->height / $ratio);
          if($this->verbose) { verbose("New width & height is requested, keeping aspect ratio results in {$this->newWidth}x{$this->newHeight}."); }
        }
        else {
          $this->newWidth = $this->width;
          $this->newHeight = $this->height;
          if($this->verbose) { verbose("Keeping original width & heigth."); }
        }
    }

    /**
     * Creating filename for the cache
     *
     * @return string filename for image cache file.
     */
    public function CacheFileName()
    {
        $parts          = pathinfo($this->pathToImage);
        $this->fileExtension  = $parts['extension'];
        $this->saveAs  = is_null($this->saveAs) ? $this->fileExtension : $this->saveAs;
        $quality_       = is_null($this->quality) ? null : "_q{$this->quality}";
        $cropToFit_     = is_null($this->cropToFit) ? null : "_cf";
        $sharpen_       = is_null($this->sharpen) ? null : "_s";
        $dirName        = preg_replace('/\//', '-', dirname($this->src));
        $cacheFileName = CACHE_PATH . "-{$dirName}-{$parts['filename']}_{$this->newWidth}_{$this->newHeight}{$quality_}{$cropToFit_}{$sharpen_}.{$this->saveAs}";
        $cacheFileName = preg_replace('/^a-zA-Z0-9\.-_/', '', $cacheFileName);

        if($this->verbose) { verbose("Cache file is: {$cacheFileName}"); }
        return $cacheFileName;
    }

    /**
     * Open up the original image from file
     *
     * @return resource $image from the source file.
     */
     private function PrepareImageForCache()
     {
        if($this->verbose) { verbose("File extension is: {$this->fileExtension}"); }

        switch($this->fileExtension) {
          case 'jpg':
          case 'jpeg':
            $image = imagecreatefromjpeg($this->pathToImage);
            if($this->verbose) { verbose("Opened the image as a JPEG image."); }
            break;

          case 'png':
            $image = imagecreatefrompng($this->pathToImage);
            if($this->verbose) { verbose("Opened the image as a PNG image."); }
            break;

          default: errorPage('No support for this file extension.');
        }
        return $image;
    }

    /**
     * Create new image and keep transparency
     *
     * @param resource $image the image to apply this filter on.
     * @return resource $image as the processed image.
     */
    private function createImageKeepTransparency($width, $height) {
        $img = imagecreatetruecolor($width, $height);
        imagealphablending($img, false);
        imagesavealpha($img, true);
        return $img;
    }

    /**
     * Sharpen image as http://php.net/manual/en/ref.image.php#56144
     * http://loriweb.pair.com/8udf-sharpen.html
     *
     * @param resource $image the image to apply this filter on.
     * @return resource $image as the processed image.
     */
    public function sharpen($image) {
        $matrix = array(
            array(-1,-1,-1,),
            array(-1,16,-1,),
            array(-1,-1,-1,)
        );
        $divisor = 8;
        $offset = 0;
        imageconvolution($image, $matrix, $divisor, $offset);
        return $image;
    }

     /**
     * Rezize image
     * @param resource $image the image to resize.
     * @return resource $image as the resized image.
     */
     public function Resize($image)
     {
         if($this->cropToFit) {
           if($this->verbose) {
               verbose("Resizing, crop to fit.");
               verbose("Create {$this->newWidth}x{$this->newHeight}");
               verbose("Crop {$this->cropWidth}x{$this->cropHeight}");
           }
           $cropX = round(($this->width - $this->cropWidth) / 2);
           $cropY = round(($this->height - $this->cropHeight) / 2);
           //    $imageResized = imagecreatetruecolor($this->newWidth, $this->newHeight);
              $imageResized = $this->createImageKeepTransparency($this->newWidth, $this->newHeight);
           imagecopyresampled($imageResized, $image, 0, 0, $cropX, $cropY, $this->newWidth, $this->newHeight, $this->cropWidth, $this->cropHeight);
           $image = $imageResized;
           $this->width = $this->newWidth;
           $this->height = $this->newHeight;
         }
         else if(!($this->newWidth == $this->width && $this->newHeight == $this->height)) {
           if($this->verbose) { verbose("Resizing, new height and/or width."); }
        //    $imageResized = imagecreatetruecolor($this->newWidth, $this->newHeight);
           $imageResized = $this->createImageKeepTransparency($this->newWidth, $this->newHeight);
           imagecopyresampled($imageResized, $image, 0, 0, 0, 0, $this->newWidth, $this->newHeight, $this->width, $this->height);
           $image  = $imageResized;
           $this->width  = $this->newWidth;
           $this->height = $this->newHeight;
         }
         return $image;
     }

     /**
     * Save image to cache
     * @param resource $image of image to save.
     * @param string $file filename of image to save in cache
     */
      private function CacheSave($image, $file)
      {
          switch($this->saveAs) {
            case 'jpeg':
            case 'jpg':
              if($this->verbose) { verbose("Saving image as JPEG to cache using quality = {$this->quality}."); }
              imagejpeg($image, $file, $this->quality);
            break;

            case 'png':
              if($this->verbose) { verbose("Saving image as PNG to cache."); }
              // Turn off alpha blending and set alpha flag
              imagealphablending($image, false);
              imagesavealpha($image, true);
              imagepng($image, $file);
            break;

            default:
              errorMessage('No support to save as this file extension.');
            break;
          }
          if($this->verbose) {
              clearstatcache();
              $cacheFilesize = filesize($file);
              verbose("File size of cached file: {$cacheFilesize} bytes.");
              $filesize = $this->imgInfo['filesize'];
              verbose("Cache file has a file size of " . round($cacheFilesize/$filesize*100) . "% of the original size.");
          }
      }


      /**
       * Output an image together with last modified header.
       *
       * @param string $file as path to the image.
       */
      public function outputFile($file) {
          $info = getimagesize($file);
          !empty($info) or errorMessage("The file doesn't seem to be an image.");
          $mime   = $info['mime'];

          $lastModified = filemtime($file);
          $gmdate = gmdate("D, d M Y H:i:s", $lastModified);

          if($this->verbose) {
              verbose("Memory peak: " . round(memory_get_peak_usage() /1024/1024) . "M");
              verbose("Memory limit: " . ini_get('memory_limit'));
              verbose("Time is {$gmdate} GMT.");
          }

          if(!$this->verbose) header('Last-Modified: ' . $gmdate . ' GMT');
          if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $lastModified){
              if($this->verbose) { verbose("Would send header 304 Not Modified, but its verbose mode."); exit; }
              header('HTTP/1.0 304 Not Modified');
          } else {
              if($this->verbose) { verbose("Would send header to deliver image with modified time: {$gmdate} GMT, but its verbose mode."); exit; }
              header('Content-type: ' . $mime);
              readfile($file);
          }
          exit;
      }

      /**
       * Output an image either by fetching from cache or
       * creating a new image which is stored to cache.
       *
       */
      public function output() {
          //
          // Get information on the image
          //
          $this->Information();

          //
          // Calculate new width and height for the image
          //
          $this->CalcWidthHeight();

          //
          // Creating a filename for the cache
          //
          $cacheFileName = $this->CacheFileName();

          //
          // Is there already a valid image in the cache directory, then use it and exit
          //
          $imageModifiedTime = filemtime($this->pathToImage);
          $cacheModifiedTime = is_file($cacheFileName) ? filemtime($cacheFileName) : null;

          // If cached image is valid, output it.
          if(!$this->ignoreCache && is_file($cacheFileName) && $imageModifiedTime < $cacheModifiedTime) {
            if($this->verbose) { verbose("Cache file is valid, output it."); }
            $this->outputFile($cacheFileName);
          }

          if($this->verbose) { verbose("Cache is not valid, process image and create a cached version of it."); }

          // If there is no valid cached file, create one, store in cache, and output this.
          //
          // Open up the original image from file
          //
          $image = $this->PrepareImageForCache();

          //
          // Resize the image if needed
          //
          $image = $this->Resize($image);
          //
          // Apply filters and postprocessing of image
          //
          if($this->sharpen) {
            $image = $this->sharpen($image);
          }
          //
          // Save the image
          //
          $this->CacheSave($image, $cacheFileName);
          //
          // Output the resulting image
          //
          $this->outputFile($cacheFileName);
      }

}
