<?php

namespace Callisto\FichesBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

//In order to use service for prePersist and preUpdate
use Symfony\Component\DependencyInjection\ContainerInterface;

use Knp\Menu\ItemInterface as MenuItemInterface;

use Callisto\FichesBundle\Entity\Fiches;

class FichesAdmin extends Admin
{

    protected $securityContext;

    public function __construct($code, $class, $baseControllerName, ContainerInterface $container)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->container = $container;
    }

    /**
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     *
     * @return void
     */
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
                ->add('id')
            ->with('Contenu')
                ->add('titre')
                ->add('description')
                ->add('datefrom')
                ->add('dateto')
                ->add('provenance')
                ->add('region')
                ->add('bibliographie')
                ->add('image')
            ->end()
            ->with('Critères')
                ->add('materiau')
                ->add('categorie')
                ->add('periode')
            ->end()
            ->with('Autres')
                ->add('auteur')
                ->add('editeur')
                ->add('creation_date')
                ->add('modification_date')
            ->end()
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->add('titre');

if(
    $this->isGranted('ROLE_CALLISTO_FICHES_ADMIN_FICHES_ADMIN')
    ||
    $this->isGranted('ROLE_CALLISTO_FICHES_ADMIN_FICHES_EDITOR')
    ) {
        $formMapper->add('public', null, array('required' => false));
    }
    $formMapper
                ->add('description')
                ->add('datefrom')
                ->add('dateto')
                ->add('provenance')
                ->add('region')
                ->add('bibliographie')
            ->with('Medias')
                ->add('image', 'sonata_type_model_list', array('required' => false), array('link_parameters' => array('context' => 'default', 'provider'=>'sonata.media.provider.image')))
                ->add('fichiers', 'sonata_type_model_list', array('required' => false), array('link_parameters' => array('context' => 'default', 'provider'=>'sonata.media.provider.file')))
            ->end()
            ->with('Critères')
                ->add('materiau')
                ->add('categorie')
                ->add('periode')
            ->end()
            ->with('Fiches associées')
                ->add('fichesassociees', null, array('required' => false, 'label' => 'Fiches'))
                // Les helpers s'affichent dans le formulaire de création d'une nouvelle fiche
            ->setHelps(array(
                'titre' => 'Nom / Titre de la fiche',
                'description' => 'Description de l\'objet',
                'fichesassociees' => 'Sélection multiple avec shift+clic',
                'public' => 'Seules les fiches portant le symbole <i class="icon-ok-circle"></i> sont accessibles'
            ))
            ->end()
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     *
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('public', null, array(
                'editable' => $this->isGranted('ROLE_CALLISTO_FICHES_ADMIN_FICHES_ADMIN') || $this->isGranted('ROLE_CALLISTO_FICHES_ADMIN_FICHES_EDITOR'),
                'template' => 'CallistoFichesBundle:Fiches:crudpublic.html.twig'
            ))
            ->addIdentifier('titre')
            ->add('creation_date', null, array('label' => 'Création'))
            ->add('auteur', null, array('label' => 'Créateur'))
            ->add('modification_date', null, array('label' => 'Mise à jour'))
            ->add('editeur', null, array('label' => 'Éditeur'))
            ->add('image', 'sonata_type_model', array('template' => 'CallistoFichesBundle:Fiches:crudimage.html.twig'))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'view' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     *
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('id')
                ->add('public')
                ->add('titre')
                ->add('description')
                ->add('datefrom')
                ->add('dateto')
                ->add('provenance')
                ->add('region')
                ->add('bibliographie')
                ->add('image')
                ->add('materiau')
                ->add('categorie')
                ->add('periode')
                ->add('auteur')
                ->add('editeur')
                ->add('creation_date')
                ->add('modification_date')
        ;
    }


    public function prePersist($fiche)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $fiche->setAuteur($user);
    }

    public function preUpdate($fiche)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $fiche->setEditeur($user);
    }

}
