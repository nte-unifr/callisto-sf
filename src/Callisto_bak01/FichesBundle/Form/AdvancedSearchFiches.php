<?php
 
namespace Callisto\FichesBundle\Form;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
 
use Doctrine\ORM\EntityRepository;
 
 
class AdvancedSearchFiches extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Callisto\FichesBundle\Entity\Fiches',
            'cascade_validation' => true,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'intention'       => 'advancedsearch_fiche_unique_secret_hahaha',
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

#        $builder->add('fiche',       'text', array('required' => false, 'label' => 'Fiche n°'));

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

        $builder->add('categorie', 'entity', array(
            'label' => 'Catégorie',
            'class' => 'CallistoFichesBundle:Categorie',
            'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('c')
                    ->orderBy('c.nom', 'ASC');
            },
            'required' => true
        ));

        $builder->add('periode', 'entity', array(
            'label' => 'Période',
            'class' => 'CallistoFichesBundle:Periode',
            'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('p')
                    ->orderBy('p.nom', 'ASC');
            },
            'required' => true
        ));

        $builder->add('materiau', 'entity', array(
            'label' => 'Matériau',
            'class' => 'CallistoFichesBundle:Materiau',
            'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('m')
                    ->orderBy('m.nom', 'ASC');
            },
            'required' => true
        ));
    }

    public function getName()
    {
        return 'rechercheavancee';
    }
}

