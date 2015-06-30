<?php

namespace Callisto\FichesBundle\Admin;
 
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
 
use Knp\Menu\ItemInterface as MenuItemInterface;
 
use Callisto\FichesBundle\Entity\Pages;
 
class PagesAdmin extends Admin
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
        ));
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid)
    {
        $datagrid
            ->add('title')
            ->add('introduction')
            ->add('text')
        ;
    }
    
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('title')
            ->add('introduction', 'html')
            ->add('text', 'html')
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
     * Create and Edit
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title')
            ->add('introduction', null, array('attr' => array('class' => 'ckeditor')))
            ->add('text', null, array('attr' => array('class' => 'ckeditor')))
            ->end()
        ;
    }
     
    /**
     
    * {@inheritdoc}
     
    */
     
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('title')
            ->add('introduction', 'html')
            ->add('text', 'html')
            ->end()
        ;
    }
}
