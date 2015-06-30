<?php

namespace Callisto\FichesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
//use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Sonata\AdminBundle\Security\Acl\Permission\MaskBuilder;

use Callisto\FichesBundle\Entity\Fiches;
use Callisto\FichesBundle\Form\SearchFiches;

use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        return array('titre' => 'Page d\'accueil');
    }

    /**
     * @Route("/recherche")
     */
    public function rechercheAction()
    {
        $fiche = new Fiches();
        $fiches = array();

        $fiche->setDatefrom( -2000 );
        $fiche->setDateto( 2000 );

        $form = $this->createForm( new SearchFiches(), $fiche );
        $request = $this->get( 'request' );

        $resultat = false;

        $session = $this->getRequest()->getSession();

        if( $session->has('recherche') && 'POST' != $request->getMethod() ) {
            $form->bind($session->get('request'));
            $resultat = true;
            
            $repository = $this->getDoctrine()
                               ->getEntityManager()
                               ->getRepository( 'CallistoFichesBundle:Fiches' );
            $query = $repository->createQueryBuilder( 'f' );

            $query
                ->where('f.id IN (:ids)')
                    ->setParameter( 'ids',$session->get('recherche') )
                ->orderBy( 'f.datefrom', 'ASC' );

            $fiches = $query->getQuery()->getResult();

            foreach($fiches as $fiche) {

                $string = strip_tags($fiche->getDescription());

                $retval = $string;

                $array = explode(" ", $string);

                if (count($array) <= 20) {
                    $retval = $string;
                } else {
                    array_splice($array, 20);
                    $retval = implode(" ", $array)." ...";
                }
                $fiche->setDescription($retval);
            }
        }


        if( 'POST' == $request->getMethod() ) {
            // On récupère le repository
            $repository = $this->getDoctrine()
                               ->getEntityManager()
                               ->getRepository( 'CallistoFichesBundle:Fiches' );

            //le résultat posté doit être mappé sur l'entité sur lequel le formulaire est basé
            $form->bind($request);

            //de sorte que $data soit un Fiche
            $data = $form->getData();

            if ( $form->isValid() ) {
                $resultat = true;
                $query = $repository->createQueryBuilder( 'f' );

                $query
                    ->where('f.datefrom >= :from')
                        ->setParameter( 'from',  $data->getDatefrom() )
                    ->andWhere( 'f.dateto <= :to' )
                        ->setParameter( 'to',  $data->getDateto() )
                    ->andWhere( 'f.public = true' );

                if ( $data->getTitre() != '' ) {
                    // plusieurs mots clés
                    $keywords = explode(' ', $data->getTitre());
                    $i = 1;
                    foreach($keywords as $keyword) {
                        $query
                            ->andWhere( 'f.titre LIKE :titre_'.$i.' OR f.description LIKE :description_'.$i )
                                ->setParameter('titre_'.$i, '%'.$keyword.'%')
                                ->setParameter('description_'.$i, '%'.$keyword.'%');
                        $i++;
                    }
                    
                }

                if ( $data->getMateriau() != '' ) {
                    $query
                        ->andWhere( 'f.materiau = :mat' )
                            ->setParameter( 'mat', $data->getMateriau() );
                }

                if ( $data->getCategorie() != '' ) {
                    $query
                        ->andWhere( 'f.categorie = :cat' )
                            ->setParameter( 'cat', $data->getCategorie() );
                }

                if ( $data->getPeriode() != '' ) {
                    $query
                        ->andWhere( 'f.periode = :per' )
                            ->setParameter( 'per', $data->getPeriode() );
                }

                $query
                    ->orderBy( 'f.datefrom', 'ASC' );

                $fiches = $query->getQuery()->getResult();
                $ids = array();
#                $ids = "";

                foreach($fiches as $fiche) {

                    $string = strip_tags($fiche->getDescription());

                    $retval = $string;

                    $array = explode(" ", $string);

                    if (count($array) <= 20) {
                        $retval = $string;
                    } else {
                        array_splice($array, 20);
                        $retval = implode(" ", $array)." ...";
                    }
                    $fiche->setDescription($retval);
                    
                    array_push($ids, $fiche->getId());
#                    $ids .= $fiche->getId().", ";
                }
                
                // pour la navigation dans les recherches
                $session->set('recherche', $ids);
                $session->set('request', $request);
            }
        }

        return $this->render( 'CallistoFichesBundle:Fiches:recherche_form.html.twig', array(
            'titre' => 'Effectuer une recherche',
            'form' => $form->createView(),
            'resultat' => $resultat,
            'fiches' => $fiches,
        ));
    }

    /**
     * @Route("/fiche/{id}")
     * @Template()
     */
    public function ficheAction( Fiches $fiche )
    {
        $prev = false;
        $next = false;
        $recherche = false;
        
        
        if($fiche->getPublic() !== true) {
            throw $this->createNotFoundException('Cette fiche n\'est pas publique !');
        }

        $orm = $this->getDoctrine()
                        ->getEntityManager();

        //On récupère les fiches similaires.
        $query = $orm->getRepository( 'CallistoFichesBundle:Fiches' )->createQueryBuilder( 'f' );

        $query
            ->where( 'f.categorie = :cat' )
                ->setParameter( 'cat',  $fiche->getCategorie()->getId() )
            ->andWhere( 'f.public = true' )
            ->andWhere( 'f.id != :id' )
                ->setParameter( 'id',  $fiche->getId() )
            ->setMaxResults(25);

        $similaires = $query->getQuery()->getResult();

        // navigation dans la recherche
        $fiches_recherche = null;
        $session = $this->getRequest()->getSession();
        if ($session->has('recherche')) {
            $recherche = $session->get('recherche');
            $index = array_search($fiche->getId(), $recherche);
            $prev = $index > 0 ? $recherche[$index - 1] : false;
            $next = $index < count($recherche)-1 ? $recherche[$index + 1] : false;
            
            // carousel recherche
            $repository = $this->getDoctrine()
                               ->getEntityManager()
                               ->getRepository( 'CallistoFichesBundle:Fiches' );
            $query = $repository->createQueryBuilder( 'f' );

            $query
                ->where('f.id IN (:ids)')
                    ->setParameter( 'ids', $session->get('recherche') )
                ->orderBy( 'f.datefrom', 'ASC' )
                ->setMaxResults(25);

            $fiches_recherche = $query->getQuery()->getResult();
        }

        return array(
            'titre' => $fiche->getTitre(),
            'fiche' => $fiche,
            'similaires' => $similaires,
            'c' => count($fiche->getfichesassociees()),
            'prev' => $prev,
            'next' => $next,
            'recherche' => $recherche,
            'fiches_recherche' => $fiches_recherche,
        );
    }

    /**
     * @Route("/print/{id}")
     * @Template()
     */
    public function printAction( Fiches $fiche )
    {
        
        
        if($fiche->getPublic() !== true) {
            throw $this->createNotFoundException('Cette fiche n\'est pas publique !');
        }

        $orm = $this->getDoctrine()
                        ->getEntityManager();

        //On récupère les fiches similaires.
        $query = $orm->getRepository( 'CallistoFichesBundle:Fiches' )->createQueryBuilder( 'f' );

        $query
            ->where( 'f.categorie = :cat' )
                ->setParameter( 'cat',  $fiche->getCategorie()->getId() )
            ->andWhere( 'f.public = true' )
            ->andWhere( 'f.id != :id' )
                ->setParameter( 'id',  $fiche->getId() );

        $similaires = $query->getQuery()->getResult();

        return array(
            'titre' => $fiche->getTitre(),
            'fiche' => $fiche,
            'similaires' => $similaires,
            'c' => count($fiche->getfichesassociees()),
        );
    }

    /**
     * @Route("/contact")
     * @Template()
     */
    public function contactAction()
    {
        return array(
            'titre' => 'Contact',
        );
    }

    /**
     * @Route("/faq")
     * @Template()
     */
    public function faqAction()
    {
        return array(
            'titre' => 'FAQ',
        );
    }


//     /**
//      * Migration des ACLs !
//      * @Route("/test")
//      * @Template()
//      */
//     public function testAction()
//     {
//         // php app/console init:acl
//         // php app/console sonata:admin:setup-acl

//         $aclProvider = $this->container->get('security.acl.provider');
//         $securityContext = $this->container->get('security.context');


//         //We allow members of ROLE_ADMIN to be OWNERS of any object of the following classes :
//         $admin_permissions_on = array('\Fiches', '\Materiau', '\Periode', '\Categorie', '\User');

//         foreach($admin_permissions_on as $class) {
//             $sid = new RoleSecurityIdentity('ROLE_ADMIN');

//             $objectIdentity = new ObjectIdentity('class', 'Callisto\FichesBundle\Entity'.$class);
//             $acl = $aclProvider->createAcl($objectIdentity); //used for creation

//             $acl->insertClassAce($sid, MaskBuilder::MASK_OWNER);

//             $aclProvider->updateAcl($acl);
//         }
//         //End allow admins


//         // And then we set every Fiche to its respective OWNER user
//         $orm = $this->getDoctrine()
//                         ->getEntityManager();

//         //on récupère tous les Users
//         $users = $orm->getRepository( 'CallistoFichesBundle:User' )->findAll();

//         $i = 0;
//         $outa = array();

//         foreach($users as $user) {
//             if(!is_object($user)) continue;
//             $out = array();
//             //On récupère les fiches de cet auteur
//             $query = $orm->getRepository( 'CallistoFichesBundle:Fiches' )->createQueryBuilder( 'f' );

//             $query
//                 ->where( 'f.auteur = :auteurid' )
//                     ->setParameter(':auteurid', $user->getId())
//                     ;

//             $fiches = $query->getQuery()->getResult();


//             $aclProvider = $this->container->get('security.acl.provider');

//             $securityContext = $this->container->get('security.context');
//             //$thisuser = $securityContext->getToken()->getUser();
//             $securityIdentity = UserSecurityIdentity::fromAccount($user);
//             $out['txt'] = $securityIdentity." has now : \n";
//             foreach($fiches as $fiche) {
//                 $objectIdentity = ObjectIdentity::fromDomainObject($fiche);
//                 $acl = $aclProvider->createAcl($objectIdentity); //used for creation

//                 $out['txt'] = $objectIdentity."\tMask:\t".(MaskBuilder::MASK_OWNER)."\t\n";
//                 //$out['o'][] = $acl->getClassAces();
//                 //add ACL owner to owner to fiche
//                 $acl->insertObjectAce($securityIdentity, (MaskBuilder::MASK_OWNER));
//                 $aclProvider->updateAcl($acl);
//             }
//             $outa[$i++] = $out;
//         }

//         return array(
//             'titre' => 'Test',
//             'out' => $out
//             );
//     }
// }

//     public function testAction()
//     {
//         // creating the ACL
//         $orm = $this->getDoctrine()
//                         ->getEntityManager();

//         //on récupère tous les Users
//         $users = $orm->getRepository( 'CallistoFichesBundle:User' )->findAll();

//         $i = 0;
//         $outa = array();

//         foreach($users as $user) {
//             if(!is_object($user)) continue;
//             $out = array();
//             //On récupère les fiches de cet auteur
//             $query = $orm->getRepository( 'CallistoFichesBundle:Fiches' )->createQueryBuilder( 'f' );

//             $query
//                 ->where( 'f.auteur = :auteurid' )
//                     ->setParameter(':auteurid', $user->getId())
//                     ;

//             $fiches = $query->getQuery()->getResult();


//             $aclProvider = $this->container->get('security.acl.provider');

//             $securityContext = $this->container->get('security.context');
//             //$thisuser = $securityContext->getToken()->getUser();
//             $securityIdentity = UserSecurityIdentity::fromAccount($user);
//             $out['txt'] = $securityIdentity." has now : \n";
//             foreach($fiches as $fiche) {
//                 $objectIdentity = ObjectIdentity::fromDomainObject($fiche);
//                 $acl = $aclProvider->createAcl($objectIdentity); //used for creation

//                 $out['txt'] = $objectIdentity."\tMask:\t".(MaskBuilder::MASK_OWNER)."\t\n";
//                 //$out['o'][] = $acl->getClassAces();
//                 //add ACL owner to owner to fiche
//                 $acl->insertObjectAce($securityIdentity, (MaskBuilder::MASK_OWNER));
//                 $aclProvider->updateAcl($acl);
//             }
//             $outa[$i++] = $out;
//         }

//         return array(
//             'titre' => 'Test',
//             'out' => $outa
//             );
//     }
//
//     public function testAction()
//     {
//         // creating the ACL
//         $orm = $this->getDoctrine()
//                         ->getEntityManager();

//         //on récupère tous les Users
//         $users = $orm->getRepository( 'CallistoFichesBundle:User' )->findAll();

//         $i = 0;
//         $outa = array();

//         foreach($users as $user) {
//             if(!is_object($user)) continue;
//             $out = array();
//             //On récupère les fiches de cet auteur
//             $query = $orm->getRepository( 'CallistoFichesBundle:Fiches' )->createQueryBuilder( 'f' );

//             $query
//                 ->where( 'f.auteur = :auteurid' )
//                     ->setParameter(':auteurid', $user->getId())
//                     ;

//             $fiches = $query->getQuery()->getResult();


//             $aclProvider = $this->container->get('security.acl.provider');

//             $securityContext = $this->container->get('security.context');
//             //$thisuser = $securityContext->getToken()->getUser();
//             $securityIdentity = UserSecurityIdentity::fromAccount($user);
//             $out['txt'] = $securityIdentity." has now : \n";
//             foreach($fiches as $fiche) {
//                 $objectIdentity = ObjectIdentity::fromDomainObject($fiche);
//                 $acl = $aclProvider->createAcl($objectIdentity); //used for ObjectField

//                 //ACL should deny to owner fiche.public modification, so only give VIEW, LIST
//                 $builder = new MaskBuilder();
//                 $builder
//                     ->add('EDIT')
//                 ;

//                 $mask = $builder->get(); // int(29)
//                 $out['txt'] = $objectIdentity."\tMask:\t".($mask)."\t\n";
//                 $acl->insertObjectFieldAce('public', $securityIdentity, MaskBuilder::MASK_OWNER);
//                 //$acl->updateObjectFieldAce(0, 'public', $mask);
//                 $aclProvider->updateAcl($acl);
//             }
//             $outa[$i++] = $out;
//         }

//         return array(
//             'titre' => 'Test',
//             'out' => $outa
//             );
//     }
// }
/*
SELECT *, ace.mask as acemask
FROM            `acl_entries` as ace
    INNER JOIN      `acl_object_identities` as oi
    ON              ace.object_identity_id = oi.id
    INNER JOIN      `acl_classes` as c
    ON              c.id = oi.class_id
    INNER JOIN      `acl_security_identities` as si
    ON              si.id = ace.security_identity_id
WHERE           oi.`object_identifier` = 293 OR oi.`object_identifier` = 205
*/
}
