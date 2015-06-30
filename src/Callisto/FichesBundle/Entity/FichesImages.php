<?php

namespace Callisto\FichesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FichesImages
 *
 * @ORM\Table(name="fiches_images")
 * @ORM\Entity
 */
class FichesImages
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Fiches", cascade={"detach"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="taxon_id", referencedColumnName="id")
     * })
     */
    private $fiche;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"detach"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="media_id", referencedColumnName="id")
     * })
     */
    private $media;


    public function __toString()
	{
	    return $this->id."";
	}

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set media
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $media
     * @return BotaniqueFichierTaxon
     */
    public function setMedia(\Application\Sonata\MediaBundle\Entity\Media $media = null)
    {
        $this->media = $media;
    
        return $this;
    }

    /**
     * Get media
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media 
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set fiche
     *
     * @param \Callisto\FichesBundle\Entity\Fiches $fiche
     * @return FichesImages
     */
    public function setFiche(\Callisto\FichesBundle\Entity\Fiches $fiche = null)
    {
        $this->fiche = $fiche;
    
        return $this;
    }

    /**
     * Get fiche
     *
     * @return \Callisto\FichesBundle\Entity\Fiches 
     */
    public function getFiche()
    {
        return $this->fiche;
    }
}
