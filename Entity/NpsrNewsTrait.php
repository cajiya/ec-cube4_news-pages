<?php
namespace Plugin\NewsPageSelfReliance42\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation as Eccube;

/**
 * @Eccube\EntityExtension("Eccube\Entity\News")
 */
trait NpsrNewsTrait
{

    /**
     * @var string
     *
     * @ORM\Column(name="np_thumbnail_url", type="string", nullable=true)
     */
    private $np_thumbnail_url;

    /**
     * @var string
     *
     * @ORM\Column(name="npseo_title", type="string", nullable=true)
     */
    private $npseo_title;

    /**
     * @var string
     *
     * @ORM\Column(name="npseo_description", type="string", nullable=true)
     */
    private $npseo_description;

    /**
     * @var string
     *
     * @ORM\Column(name="npseo_robots", type="string", nullable=true , options={"default" : "index,follow"})
     */
    private $npseo_robots;


    /**
     * @return string
     */
    public function getNpThumbnailUrl()
    {
        return $this->np_thumbnail_url;
    }

    /**
     * @param string $np_thumbnail_url
     */
    public function setNpThumbnailUrl($np_thumbnail_url)
    {
        $this->np_thumbnail_url = $np_thumbnail_url;
    }

    /**
     * @return string
     */
    public function getNpseoTitle()
    {
        return $this->npseo_title;
    }

    /**
     * @param string $npseo_title
     */
    public function setNpseoTitle($npseo_title)
    {
        $this->npseo_title = $npseo_title;
    }

    /**
     * @return string
     */
    public function getNpseoDescription()
    {
        return $this->npseo_description;
    }

    /**
     * @param string $npseo_description
     */
    public function setNpseoDescription($npseo_description)
    {
        $this->npseo_description = $npseo_description;
    }

    /**
     * @return boolean
     */
    public function getNpseoRobots()
    {
        return $this->npseo_robots;
    }

    /**
     * @param boolean $npseo_robots
     */
    public function setNpseoRobots($npseo_robots)
    {
        $this->npseo_robots = $npseo_robots;
    }
}
