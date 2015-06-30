<?php

namespace Callisto\FichesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Callisto\FichesBundle\Entity\Materiau
 *
 * @ORM\Table(name="materiau")
 * @ORM\Entity
 */
class Materiau
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
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="Fiches", mappedBy="materiau")
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
     * @return Materiau
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
     * Set prix
     *
     * @param integer $prix
     * @return Materiau
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return integer
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
    * Override toString() method to return the name of the materiau
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
     * @param \Callisto\FichesBundle\Entity\Fiches $fiches
     * @return Materiau
     */
    public function addFiche(\Callisto\FichesBundle\Entity\Fiches $fiches)
    {
        $this->fiches[] = $fiches;
    
        return $this;
    }

    /**
     * Remove fiches
     *
     * @param \Callisto\FichesBundle\Entity\Fiches $fiches
     */
    public function removeFiche(\Callisto\FichesBundle\Entity\Fiches $fiches)
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
