<?php

namespace Callisto\FichesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Callisto\FichesBundle\Entity\Fiches
 *
 * @ORM\Table(name="fiches")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Fiches
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
     * @var string $titre
     *
     * @ORM\Column(name="titre", type="string", length=100, nullable=false)
     */
    private $titre;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var integer $datefrom
     *
     * @ORM\Column(name="dateFrom", type="integer", nullable=false)
     */
    private $datefrom;

    /**
     * @var integer $dateto
     *
     * @ORM\Column(name="dateTo", type="integer", nullable=false)
     */
    private $dateto;

    /**
     * @var string $provenance
     *
     * @ORM\Column(name="provenance", type="string", length=250, nullable=true)
     */
    private $provenance;

    /**
     * @var string $region
     *
     * @ORM\Column(name="region", type="string", length=250, nullable=true)
     */
    private $region;

    /**
     * @var string $source
     *
     * @ORM\Column(name="source", type="text", nullable=true)
     */
    private $source;

    /**
     * @var string $bibliographie
     *
     * @ORM\Column(name="bibliographie", type="text", nullable=true)
     */
    private $bibliographie;

    /**
     * @var string $dimensions
     *
     * @ORM\Column(name="dimensions", type="string", length=50, nullable=true)
     */
    private $dimensions;

    /**
     * @var boolean $public
     *
     * @ORM\Column(name="public", type="boolean")
     */
    private $public;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $creation_date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modification_date;

    /**
     * @var Categorie
     *
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorie_id", referencedColumnName="id")
     * })
     */
    private $categorie;

    /**
     * @var Periode
     *
     * @ORM\ManyToOne(targetEntity="Periode")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="periode_id", referencedColumnName="id")
     * })
     */
    private $periode;

    /**
     * @var Materiau
     *
     * @ORM\ManyToOne(targetEntity="Materiau")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="materiau_id", referencedColumnName="id")
     * })
     */
    private $materiau;

    /**
     * @var Image
     *
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"all"})
     */
    private $image;

    /**
     * @var Fichiers
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"all"})
     */
    private $fichiers;

    /**
     * @var Fichesassociees
     *
     * @ORM\ManyToMany(targetEntity="Fiches")
     * @ORM\JoinTable(name="fichesAssociees",
     *      joinColumns={@ORM\JoinColumn(name="id_fiche", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_fiche_associee", referencedColumnName="id")}
     * )
     */
    private $fichesassociees;

    /**
     * @var Auteur
     *
     * @ORM\ManyToOne(targetEntity="Callisto\FichesBundle\Entity\User")
     */
    private $auteur;

    /**
     * @var Editeur
     *
     * @ORM\ManyToOne(targetEntity="Callisto\FichesBundle\Entity\User")
     */
    private $editeur;

    /**
     * @ORM\OneToMany(targetEntity="FichesImages", mappedBy="fiche", cascade={"persist"}, orphanRemoval=true)
     */
    private $images;

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
     * Set titre
     *
     * @param string $titre
     * @return Fiches
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Fiches
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set datefrom
     *
     * @param integer $datefrom
     * @return Fiches
     */
    public function setDatefrom($datefrom)
    {
        $this->datefrom = $datefrom;

        return $this;
    }

    /**
     * Get datefrom
     *
     * @return integer
     */
    public function getDatefrom()
    {
        return $this->datefrom;
    }

    /**
     * Set dateto
     *
     * @param integer $dateto
     * @return Fiches
     */
    public function setDateto($dateto)
    {
        $this->dateto = $dateto;

        return $this;
    }

    /**
     * Get dateto
     *
     * @return integer
     */
    public function getDateto()
    {
        return $this->dateto;
    }

    /**
     * Set provenance
     *
     * @param string $provenance
     * @return Fiches
     */
    public function setProvenance($provenance)
    {
        $this->provenance = $provenance;

        return $this;
    }

    /**
     * Get provenance
     *
     * @return string
     */
    public function getProvenance()
    {
        return $this->provenance;
    }

    /**
     * Set region
     *
     * @param string $region
     * @return Fiches
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set source
     *
     * @param string $source
     * @return Fiches
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set bibliographie
     *
     * @param string $bibliographie
     * @return Fiches
     */
    public function setBibliographie($bibliographie)
    {
        $this->bibliographie = $bibliographie;

        return $this;
    }

    /**
     * Get bibliographie
     *
     * @return string
     */
    public function getBibliographie()
    {
        return $this->bibliographie;
    }

    /**
     * Set dimensions
     *
     * @param string $dimensions
     * @return Fiches
     */
    public function setDimensions($dimensions)
    {
        $this->dimensions = $dimensions;

        return $this;
    }

    /**
     * Get dimensions
     *
     * @return string
     */
    public function getDimensions()
    {
        return $this->dimensions;
    }

    /**
     * Set categorie
     *
     * @param Callisto\FichesBundle\Entity\Categorie $categorie
     * @return Fiches
     */
    public function setCategorie(\Callisto\FichesBundle\Entity\Categorie $categorie = null)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return Callisto\FichesBundle\Entity\Categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set periode
     *
     * @param Callisto\FichesBundle\Entity\Periode $periode
     * @return Fiches
     */
    public function setPeriode(\Callisto\FichesBundle\Entity\Periode $periode = null)
    {
        $this->periode = $periode;

        return $this;
    }

    /**
     * Get periode
     *
     * @return Callisto\FichesBundle\Entity\Periode
     */
    public function getPeriode()
    {
        return $this->periode;
    }

    /**
     * Set materiau
     *
     * @param Callisto\FichesBundle\Entity\Materiau $materiau
     * @return Fiches
     */
    public function setMateriau(\Callisto\FichesBundle\Entity\Materiau $materiau = null)
    {
        $this->materiau = $materiau;

        return $this;
    }

    /**
     * Get materiau
     *
     * @return Callisto\FichesBundle\Entity\Materiau
     */
    public function getMateriau()
    {
        return $this->materiau;
    }

    /**
     * Set image
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $image
     * @return Fiches
     */
    public function setImage(\Application\Sonata\MediaBundle\Entity\Media $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getImage()
    {
        return $this->image;
    }

    public function __toString()
    {
        return (string)($this->getId().' '.$this->getTitre());
    }

    /**
     * Set fichiers
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $fichiers
     * @return Fiches
     */
    public function setFichiers(\Application\Sonata\MediaBundle\Entity\Media $fichiers = null)
    {
        $this->fichiers = $fichiers;

        return $this;
    }

    /**
     * Get fichiers
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getFichiers()
    {
        return $this->fichiers;
    }

    /**
     * Set fichesassociees
     *
     * @param \Callisto\FichesBundle\Entity\Fiches $fichesassociees
     * @return Fiches
     */
    public function setFichesassociees(\Callisto\FichesBundle\Entity\Fiches $fichesassociees = null)
    {
        $this->fichesassociees = $fichesassociees;

        return $this;
    }

    /**
     * Get fichesassociees
     *
     * @return \Callisto\FichesBundle\Entity\Fiches
     */
    public function getFichesassociees()
    {
        return $this->fichesassociees;
    }

    /**
     * Add fichesassociees
     *
     * @param \Callisto\FichesBundle\Entity\Fiches $fichesassociees
     * @return Fiches
     */
    public function addFichesassociee(\Callisto\FichesBundle\Entity\Fiches $fichesassociees)
    {
        $this->fichesassociees[] = $fichesassociees;

        return $this;
    }

    /**
     * Remove fichesassociees
     *
     * @param \Callisto\FichesBundle\Entity\Fiches $fichesassociees
     */
    public function removeFichesassociee(\Callisto\FichesBundle\Entity\Fiches $fichesassociees)
    {
        $this->fichesassociees->removeElement($fichesassociees);
    }

    /**
     * Set public
     *
     * @param boolean $public
     * @return Fiches
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->creation_date = $this->modification_date = new \DateTime('now');
        //See also FichesListener.php
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->modification_date = new \DateTime('now');
        //See also FichesListener.php
    }

    /**
     * Set creation_date
     *
     * @param \DateTime $creationDate
     * @return Fiches
     */
    public function setCreationDate($creationDate)
    {
        $this->creation_date = $creationDate;

        return $this;
    }

    /**
     * Get creation_date
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creation_date;
    }

    /**
     * Set modification_date
     *
     * @param \DateTime $modificationDate
     * @return Fiches
     */
    public function setModificationDate($modificationDate)
    {
        $this->modification_date = $modificationDate;

        return $this;
    }

    /**
     * Get modification_date
     *
     * @return \DateTime
     */
    public function getModificationDate()
    {
        return $this->modification_date;
    }

    /**
     * Set auteur
     *
     * @param \Callisto\FichesBundle\Entity\User $auteur
     * @return Fiches
     */
    public function setAuteur(\Callisto\FichesBundle\Entity\User $auteur = null)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return \Callisto\FichesBundle\Entity\User
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set editeur
     *
     * @param \Callisto\FichesBundle\Entity\User $editeur
     * @return Fiches
     */
    public function setEditeur(\Callisto\FichesBundle\Entity\User $editeur = null)
    {
        $this->editeur = $editeur;

        return $this;
    }

    /**
     * Get editeur
     *
     * @return \Callisto\FichesBundle\Entity\User
     */
    public function getEditeur()
    {
        return $this->editeur;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fichesassociees = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add images
     *
     * @param \Callisto\FichesBundle\Entity\FichesImages $images
     * @return Fiches
     */
    public function addImage(\Callisto\FichesBundle\Entity\FichesImages $images)
    {
        $images->setFiche($this); # pour la collection dans le formulaire
        $this->images[] = $images;
    
        return $this;
    }

    /**
     * Remove images
     *
     * @param \Callisto\FichesBundle\Entity\FichesImages $images
     */
    public function removeImage(\Callisto\FichesBundle\Entity\FichesImages $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImages()
    {
        return $this->images;
    }
}
