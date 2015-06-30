<?php

namespace Callisto\FichesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Verrou
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Verrou
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
     * @var string
     *
     * @ORM\Column(name="objet", type="string", length=255)
     */
    private $objet;

    /**
     * @var integer
     *
     * @ORM\Column(name="objet_id", type="integer")
     */
    private $objet_id;

    /**
     * @var string
     *
     * @ORM\Column(name="utilisateur", type="string", length=255)
     */
    private $utilisateur;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="echeance", type="datetime")
     */
    private $echeance;


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
     * Set objet
     *
     * @param string $objet
     * @return Verrou
     */
    public function setObjet($objet)
    {
        $this->objet = $objet;
    
        return $this;
    }

    /**
     * Get objet
     *
     * @return string 
     */
    public function getObjet()
    {
        return $this->objet;
    }

    /**
     * Set objet_id
     *
     * @param integer $objetId
     * @return Verrou
     */
    public function setObjetId($objetId)
    {
        $this->objet_id = $objetId;
    
        return $this;
    }

    /**
     * Get objet_id
     *
     * @return integer 
     */
    public function getObjetId()
    {
        return $this->objet_id;
    }

    /**
     * Set utilisateur
     *
     * @param string $utilisateur
     * @return Verrou
     */
    public function setUtilisateur($utilisateur)
    {
        $this->utilisateur = $utilisateur;
    
        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return string 
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * Set echeance
     *
     * @param \DateTime $echeance
     * @return Verrou
     */
    public function setEcheance($echeance)
    {
        $this->echeance = $echeance;
    
        return $this;
    }

    /**
     * Get echeance
     *
     * @return \DateTime 
     */
    public function getEcheance()
    {
        return $this->echeance;
    }
}
