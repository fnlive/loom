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
    static private $maxWidth = 2000;
    static private $maxHeight = 2000;

    private $image; //?? = imagecreatefromjpeg($pathToImage);
    //private $params = array();
    private $src        = null;
    private $verbose    = true;
    public $saveAs     = null;
    private $quality    = null;
    private $ignoreCache = null;
    private $newWidth   = null;
    private $newHeight  = null;
    private $cropToFit  = null;
    private $sharpen    = null;
    private $pathToImage    = null;
    public $fileExtension = null;

    private $imgInfo = null;

    public $cropWidth = null;
    public $cropHeight  = null;

    function __construct()
    {
        // ParseQuery could go here instead.
    }

    /**
     * Parse query string and store processing commands
     * @param string $query from url with processing commands and options.
     * @return resource $image as the processed image.
     */
    public function ParseQuery($query)
    {
        //
        // Get the incoming arguments
        //
        // var_dump($query);
        $this->src        = isset($query['src'])     ? $query['src']      : null;
        $this->verbose    = isset($query['verbose']) ? true              : null;
        $this->saveAs     = isset($query['save-as']) ? $query['save-as']  : null;
        $this->quality    = isset($query['quality']) ? $query['quality']  : 60;
        $this->ignoreCache = isset($query['no-cache']) ? true           : null;
        $this->newWidth   = isset($query['width'])   ? $query['width']    : null;
        $this->newHeight  = isset($query['height'])  ? $query['height']   : null;
        $this->cropToFit  = isset($query['crop-to-fit']) ? true : null;
        $this->sharpen    = isset($query['sharpen']) ? true : null;

        $this->pathToImage = realpath(IMG_PATH . $this->src);
        //
        // Validate incoming arguments
        //
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
     * @param string path to image
     * @return
     */
     // TODO: Chang $pathToImage to property this->
    public function Information($pathToImage)
    {
        // TODO: make private
        $this->imgInfo = list($width, $height, $type, $attr) = getimagesize($pathToImage);
        // echo "<br>" . __FILE__ . " : " . __LINE__ . "<br>";var_dump($this->imgInfo);
        !empty($this->imgInfo) or errorMessage("The file doesn't seem to be an image.");
        $mime = $this->imgInfo['mime'];

        if($this->verbose) {
          $this->imgInfo['filesize'] = $filesize = filesize($pathToImage);
        //   echo "<br>" . __FILE__ . " : " . __LINE__ . "<br>";var_dump($this->imgInfo);
          verbose("Image file: {$pathToImage}");
          verbose("Image information: " . print_r($this->imgInfo, true));
          verbose("Image width x height (type): {$width} x {$height} ({$type}).");
          verbose("Image file size: {$filesize} bytes.");
          verbose("Image mime type: {$mime}.");
        }
        return $this->imgInfo;
    }

    /**
     * Blabla
     * @param
     * @return
     */
    public function CalcWidthHeight()
    {
        //
        // Calculate new width and height for the image
        //
        list($width, $height) = $this->imgInfo;
        // echo "<br>" . __FILE__ . " : " . __LINE__ . "<br>";var_dump($width);
        // echo "<br>" . __FILE__ . " : " . __LINE__ . "<br>";var_dump($height);
        $aspectRatio = $width / $height;

        if($this->cropToFit && $this->newWidth && $this->newHeight) {
          $targetRatio = $this->newWidth / $this->newHeight;
          $this->cropWidth   = $targetRatio > $aspectRatio ? $width : round($height * $targetRatio);
          $this->cropHeight  = $targetRatio > $aspectRatio ? round($width  / $targetRatio) : $height;
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
          $ratioWidth  = $width  / $this->newWidth;
          $ratioHeight = $height / $this->newHeight;
          $ratio = ($ratioWidth > $ratioHeight) ? $ratioWidth : $ratioHeight;
          $this->newWidth  = round($width  / $ratio);
          $this->newHeight = round($height / $ratio);
          if($this->verbose) { verbose("New width & height is requested, keeping aspect ratio results in {$this->newWidth}x{$this->newHeight}."); }
        }
        else {
          $this->newWidth = $width;
          $this->newHeight = $height;
          if($this->verbose) { verbose("Keeping original width & heigth."); }
        }
        return array($this->newWidth, $this->newHeight);
    }

    //
    // Creating a filename for the cache
    //
    /**
     * Blabla
     * @param
     * @return
     */
    public function CacheFileName()
    {
        # code...
        $parts          = pathinfo($this->pathToImage);
        $this->fileExtension  = $parts['extension'];
        // echo "<br>" . __FILE__ . " : " . __LINE__ . "<br>";var_dump($this->fileExtension);
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
      * Blabla
      * @param
      * @return
      */

      /**
       * Output an image together with last modified header.
       *
       * @param string $file as path to the image.
       * @param boolean $verbose if verbose mode is on or off.
       */
       // TODO: change $verbose to use member property
      public function outputFile($file, $verbose) {
          $info = getimagesize($file);
          !empty($info) or errorMessage("The file doesn't seem to be an image.");
          $mime   = $info['mime'];

          $lastModified = filemtime($file);
          $gmdate = gmdate("D, d M Y H:i:s", $lastModified);

          if($verbose) {
              verbose("Memory peak: " . round(memory_get_peak_usage() /1024/1024) . "M");
              verbose("Memory limit: " . ini_get('memory_limit'));
              verbose("Time is {$gmdate} GMT.");
          }

          if(!$verbose) header('Last-Modified: ' . $gmdate . ' GMT');
          if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $lastModified){
              if($verbose) { verbose("Would send header 304 Not Modified, but its verbose mode."); exit; }
              header('HTTP/1.0 304 Not Modified');
          } else {
              if($verbose) { verbose("Would send header to deliver image with modified time: {$gmdate} GMT, but its verbose mode."); exit; }
              header('Content-type: ' . $mime);
              readfile($file);
          }
          exit;
      }

      public function output($file, $verbose) {
          //
          // Is there already a valid image in the cache directory, then use it and exit
          //
          $imageModifiedTime = filemtime($this->pathToImage);
          // TODO: $cacheFileName change to property this-> but first to $file
          $cacheModifiedTime = is_file($file) ? filemtime($file) : null;

          // If cached image is valid, output it.
          if(!$this->ignoreCache && is_file($file) && $imageModifiedTime < $cacheModifiedTime) {
            if($this->verbose) { verbose("Cache file is valid, output it."); }
            $this->outputFile($file, $this->verbose);
          }

          if($this->verbose) { verbose("Cache is not valid, process image and create a cached version of it."); }

          // If there is no valid cached file, create one, store in cache, and output this.
          //
          // Open up the original image from file
          //
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
          //
          // Resize the image if needed
          //
          // TODO: temp ass to witdth below
          list($width, $height) = $this->imgInfo;
          if($this->cropToFit) {
            if($this->verbose) { verbose("Resizing, crop to fit."); }
            $cropX = round(($width - $this->cropWidth) / 2);
            $cropY = round(($height - $this->cropHeight) / 2);
            $imageResized = imagecreatetruecolor($this->newWidth, $this->newHeight);
            imagecopyresampled($imageResized, $image, 0, 0, $cropX, $cropY, $this->newWidth, $this->newHeight, $this->cropWidth, $this->cropHeight);
            $image = $imageResized;
            $width = $this->newWidth;
            $height = $this->newHeight;
          }
          else if(!($this->newWidth == $width && $this->newHeight == $height)) {
            if($this->verbose) { verbose("Resizing, new height and/or width."); }
            $imageResized = imagecreatetruecolor($this->newWidth, $this->newHeight);
            imagecopyresampled($imageResized, $image, 0, 0, 0, 0, $this->newWidth, $this->newHeight, $width, $height);
            $image  = $imageResized;
            $width  = $this->newWidth;
            $height = $this->newHeight;
          }

          //
          // Apply filters and postprocessing of image
          //
          if($this->sharpen) {
            $image = $this->sharpen($image);
          }
          //
          // Save the image
          //
          switch($this->saveAs) {
            case 'jpeg':
            case 'jpg':
              if($this->verbose) { verbose("Saving image as JPEG to cache using quality = {$this->quality}."); }
              imagejpeg($image, $file, $this->quality);
            break;

            case 'png':
              if($this->verbose) { verbose("Saving image as PNG to cache."); }
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

          //
          // Output the resulting image
          //
          $this->outputFile($file, $this->verbose);


      }

}
