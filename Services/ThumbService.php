<?php

namespace Services;

use Intervention\Image\Image;

class ThumbService {

    private $ImageMimeType = array('image/jpg','image/jpeg','image/png','image/gif');

    /**
     * Get Thumbnails from Youtube
     *
     * @version  08-01-14
     * @param  String $youtubeUrl https://www.youtube.com/watch?v=9WoM2bHfr48
     * @param  Integer $clientId  Etablissement's id
     * @param  String $path       Path where to save image
     */
    public function getYoutubeThumb($youtubeUrl, $clientId, $path)
    {
        $youtubeId = $this->getYoutubeId($youtubeUrl);

        $url = 'http://img.youtube.com/vi/'.$youtubeId.'/maxresdefault.jpg';
        $img = $path.$clientId.'-big.jpg';
        file_put_contents($img, file_get_contents($url));

        $url = 'http://img.youtube.com/vi/'.$youtubeId.'/0.jpg';
        $img = $path.$clientId.'-medium.jpg';
        file_put_contents($img, file_get_contents($url));

        $url = 'http://img.youtube.com/vi/'.$youtubeId.'/2.jpg';
        $img = $path.$clientId.'-small.jpg';
        file_put_contents($img, file_get_contents($url));
    }

    /**
     * Extract Youtube ID from a youtube url
     *
     * @version  08-01-14
     * @param  string $url Youtube URL
     * @return string      YoutubeID
     */
    public function getYoutubeId($url){
        $debut_id = explode("v=",$url,2);
        $id_et_fin_url = explode("&",$debut_id[1],2);

        return $id_et_fin_url[0];
    }

    /**
     * Get A basic object that contain video infos, to be merged with slides
     *
     * @version  20-01-14
     * @param  String $url Only vital information here
     * @param  Integer $clientId  Etablissement's id
     * @return [type]      [description]
     */
    public function getThumbSlide($url, $clientId)
    {
        $videoSlide             = new \StdClass();
        $videoSlide->id         = $this->getYoutubeId($url);
        $videoSlide->smallThumb = $clientId.'-small.jpg';
        $videoSlide->bigThumb   = $clientId.'-big.jpg';
        $videoSlide->path       = $clientId.'-medium.jpg';
        $videoSlide->url        = $url;

        return $videoSlide;
    }

    /**
     * create Thumbnail with few elements
     *
     * @version  09-01-2014
     * @param  String  $dir         Path
     * @param  Integer $width       if null will ajust
     * @param  Integer $height      if null will ajust
     * @param  String  $destination Can be null
     */
    public function createThumbnail($dir, $width = null, $height = null, $destination = null)
    {
        if(!file_exists($dir)) throw new \Exception("That file doesnot exist!", 1);

        $image = Image::make($dir)->resize($width, $height, true);

        if ($destination===null) return $image;

        $image->save($destination);
    }

    /**
     * Special method for uploaded images
     *
     * @version  21-01-14
     * @param         $file     Request->files->get
     * @param  String $folder   Uploaded dir
     * @param  String $filename Nametype
     * @return String [description]
     */
    public function setUploadedImage($file, $folder, $filename)
    {
        if(!in_array($file->getClientMimeType(), $this->ImageMimeType)) throw new Exception("Wrong mime type (not allowed)", 1);

        $UseFilename = $filename.'.'.$file->guessClientExtension();
        $file->move($folder, $UseFilename);

        return (string)$UseFilename;
    }
}