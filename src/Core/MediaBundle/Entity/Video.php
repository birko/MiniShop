<?php

namespace Core\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Sluggable\Util\Urlizer as GedmoUrlizer;
/**
 * Nws\MediaBundle\Entity\Video
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Core\MediaBundle\Entity\VideoRepository")
 */
class Video extends Media
{
    /**
     * @var integer $videoType
     *
     * @ORM\Column(name="video_type", type="integer",  nullable = true)
     */
    private $videoType;
    
    public function __construct()
    {
        parent::__construct();
    } 
    
    
    /**
     * Set videoType
     *
     * @param integer $videoType
     */
    public function setVideoType($videoType)
    {
        $this->videoType = $videoType;
    }

    /**
     * Get videoType
     *
     * @return integer 
     */
    public function getVideoType()
    {
        return $this->videoType;
    }
    
    
    public function getType() 
    {
        return "video";
    }
    
    protected function getUploadDir($dir = null)
    {
        return  'uploads/video/';
    }
    
    public function preUpload()
    {
        $type = $this->getVideoType();
        $file = $this->getFile();
        if($type == VideoType::YOUTUBE)
        {
            $this->setFileName($this->getSource());
            $this->setHash($this->getSource());
            return true;
        }
        elseif(isset($file))
        {
            
            $status  = parent::preUpload();
            if ($status) //if is new video
            {
                return true;
            }
        }
        return false;
    }
    
    public function upload()
    {
        parent::upload(); 
    }
}
