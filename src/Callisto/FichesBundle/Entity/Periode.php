<?php

namespace Callisto\FichesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Callisto\FichesBundle\Entity\Periode
 *
 * @ORM\Table(name="periode")
 * @ORM\Entity
 */
class Periode
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $nom
     *
     * @ORM\Column(name="nom", type="string", length=100, nullable=false)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="Fiches", mappedBy="periode")
     */
    protected $fiches;



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
     * Set nom
     *
     * @param string $nom
     * @return Periode
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
    * Override toString() method to return the name of the periode
    * @return string name
    */
    public function __toString()
    {
        return (string)$this->nom;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fiches = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add fiches
     *
     * @param \Callisto\FichesBundle\Entity\fiches $fiches
     * @return Periode
     */
    public function addFiche(\Callisto\FichesBundle\Entity\fiches $fiches)
    {
        $this->fiches[] = $fiches;
    
        return $this;
    }

    /**
     * Remove fiches
     *
     * @param \Callisto\FichesBundle\Entity\fiches $fiches
     */
    public function removeFiche(\Callisto\FichesBundle\Entity\fiches $fiches)
    {
        $this->fiches->removeElement($fiches);
    }

    /**
     * Get fiches
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFiches()
    {
        return $this->fiches;
    }
}
