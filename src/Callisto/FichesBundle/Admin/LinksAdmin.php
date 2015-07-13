<?php

namespace Callisto\FichesBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use Knp\Menu\ItemInterface as MenuItemInterface;

use Callisto\FichesBundle\Entity\Link;

class LinksAdmin extends Admin
{
    // LIST FIELDS
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('title', null, array('label' => 'Titre'))
            ->add('value', null, array('label' => 'Url'))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    // LIST FILTERS
    // no filters

    // FORM FIELDS
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', null, array('required' => true, 'label' => 'Titre'))
            ->add('value', null, array('required' => true, 'label' => 'Url'))
        ;
    }

    // DEFAULT DATA ORGANISATION
    /**
     * Default Datagrid values
     *
     * @var array
     */
    protected $datagridValues = array(
        '_page' => 1,               // display the first page (default = 1)
        '_sort_order' => 'ASC',     // reverse order (default = 'ASC')
        '_sort_by' => 'title' // name of the ordered field
        // the '_sort_by' key can be of the form 'mySubModel.mySubSubModel.myField'.
    );
}
