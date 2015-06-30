<?php

namespace Callisto\FichesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

//use Doctrine\ORM;
//use Callisto\FichesBundle\Entity;
//use Doctrine\ORM\EntityRepository;

class SearchFiches extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Callisto\FichesBundle\Entity\Fiches',
            'cascade_validation' => true,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'intention'       => 'search_fiches_unique_secret_hahaha',
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('titre',      'text', array(
            'required' => false,
            'label' => 'Titre'
        ));

        $choices = array_combine(range(-2000, 2000, 50), range(-2000, 2000, 50));

        $builder->add('datefrom',   'choice', array(
            'choices' => $choices,
            'empty_data' => -2000,
            'label' => 'De'
        ));

        $builder->add('dateto',     'choice', array(
            'choices' => $choices,
            'empty_data' => 2000,
            'label' => 'à'
        ));

        $dropdown_opts = array(
            'empty_value' => '----------- Choisissez une option -----------',
            'empty_data'  => null,
            'required' => false
        );

        $builder->add('periode',    null, array_merge(array('label' => 'Période'), $dropdown_opts));
        $builder->add('categorie',  null, array_merge(array('label' => 'Catégorie'), $dropdown_opts));
        $builder->add('materiau',   null, array_merge(array('label' => 'Matériau'), $dropdown_opts));

        $builder->add('region',      'text', array(
            'required' => false,
            'label' => 'Région'
        ));

        $builder->add('provenance',      'text', array(
            'required' => false,
            'label' => 'Provenance'
        ));

        $builder->add('id',      'integer', array(
            'required' => false,
            'label' => 'Num&eacute;ro de la fiche'
        ));
    }

    public function getName()
    {
        return 'recherche_fiches';
    }
}
