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
}
