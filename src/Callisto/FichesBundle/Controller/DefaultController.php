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
use Callisto\FichesBundle\Form\AdvancedSearchFiches;

use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/shib")
     */
    public function shibAction()
    {
        $this->get('session')->set('isUserShibAuthenticated', true);
        return $this->redirect($this->generateUrl('home'));
    }

    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {
        $nb_fiches = count($this->getDoctrine()
                               ->getManager()
                               ->getRepository( 'CallistoFichesBundle:Fiches' )->createQueryBuilder('f')
                               ->where('f.public = 1')
                               ->getQuery()->getResult());
        $page = $this->getDoctrine()
                        ->getManager()->getRepository( 'CallistoFichesBundle:Pages' )->find( '1' );

        return array('titre'     => 'Page d\'accueil',
                     'nb_fiches' => $nb_fiches,
                     'page'       => $page);
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

        $repository = $this->getDoctrine()
                           ->getManager()
                           ->getRepository( 'CallistoFichesBundle:Fiches' );

        if( $session->has('recherche') && 'POST' != $request->getMethod() ) {
            $form->bind($session->get('request'));
            $resultat = true;
            $query = $repository->createQueryBuilder( 'f' );

            if ( count($session->get('recherche')) > 0 ) {
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
        }


        if( 'POST' == $request->getMethod() ) {
            // On récupère le repository
            $repository = $this->getDoctrine()
                               ->getManager()
                               ->getRepository( 'CallistoFichesBundle:Fiches' );

            //le résultat posté doit être mappé sur l'entité sur lequel le formulaire est basé
            $form->bind($request);

            //de sorte que $data soit un Fiche
            $data = $form->getData();

            if ( $form->isValid() ) {
                $resultat = true;
                $query = $repository->createQueryBuilder( 'f' );

                $query
                    ->andWhere('f.datefrom >= :from')
                        ->setParameter( 'from',  $data->getDatefrom() )
                    ->andWhere( 'f.dateto <= :to' )
                        ->setParameter( 'to',  $data->getDateto() )
                    ->andWhere( 'f.public = true' );

                if ( $data->getTitre() != '' ) {
                    // plusieurs mots clés
                    $keywords = explode(' ', $data->getTitre());

                    $tmp = print_r(explode('AND', $data->getTitre()), true);

                    $i = 1;
                    foreach($keywords as $keyword) {
                        $query
                            ->andWhere( 'f.titre LIKE :titre_'.$i.' OR f.description LIKE :description_'.$i
                                        .' OR f.provenance LIKE :provenance_'.$i.' OR f.region LIKE :region_'.$i
                                        .' OR f.source LIKE :source_'.$i.' OR f.bibliographie LIKE :bibliographie_'.$i )
                                ->setParameter('titre_'.$i, '%'.$keyword.'%')
                                ->setParameter('description_'.$i, '%'.$keyword.'%')
                                ->setParameter('provenance_'.$i, '%'.$keyword.'%')
                                ->setParameter('region_'.$i, '%'.$keyword.'%')
                                ->setParameter('source_'.$i, '%'.$keyword.'%')
                                ->setParameter('bibliographie_'.$i, '%'.$keyword.'%');
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

                if ( $data->getRegion() != '' ) {
                    // plusieurs mots clés
                    $keywords_region = explode(' ', $data->getRegion());
                    $i = 1;
                    foreach($keywords_region as $keyword) {
                        $query
                            ->andWhere( 'f.region LIKE :region_'.$i )
                                ->setParameter('region_'.$i, '%'.$keyword.'%');
                        $i++;
                    }
                }

                if ( $data->getProvenance() != '' ) {
                    // plusieurs mots clés
                    $keywords_provenance = explode(' ', $data->getProvenance());
                    $i = 1;
                    foreach($keywords_provenance as $keyword) {
                        $query
                            ->andWhere( 'f.provenance LIKE :provenance_'.$i )
                                ->setParameter('provenance_'.$i, '%'.$keyword.'%');
                        $i++;
                    }
                }

                if ( $data->getId() != '' ) {
                    $query
                        ->andWhere( 'f.id = :id' )
                            ->setParameter('id', $data->getId());
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
                $session->set('derniere_recherche', 'normale');
#                $session->set('rechercheavancee_ids', null);
#                $session->set('rechercheavancee_data', null);
#                $session->set('request_avancee', null);
            }
        }

        // pour afficher le nombre total de fiches publiques
        $nb_fiches = count($repository->createQueryBuilder('f')
                               ->where('f.public = 1')
                               ->getQuery()->getResult());

        return $this->render( 'CallistoFichesBundle:Fiches:recherche_form.html.twig', array(
            'titre' => 'Effectuer une recherche',
            'form' => $form->createView(),
            'resultat' => $resultat,
            'fiches' => $fiches,
            'nb_fiches' => $nb_fiches,
            'nav_type' => 'n',
        ));
    }

    /**
     * @Route("/fiche/{id}")
     * @Template()
     */
    public function ficheAction( Fiches $fiche, Request $request )
    {
        $prev = false;
        $next = false;
        $recherche = false;

        if($fiche->getPublic() !== true) {
            throw $this->createNotFoundException('Cette fiche n\'est pas publique !');
        }

        $orm = $this->getDoctrine()
                        ->getManager();

        //On récupère les fiches similaires.
        $query = $orm->getRepository( 'CallistoFichesBundle:Fiches' )->createQueryBuilder( 'f' )
                        ->where('1 = 1');

        if ( $fiche->getCategorie() != null ) {
            $query
                ->andWhere( 'f.categorie = :cat' )
                    ->setParameter( 'cat',  $fiche->getCategorie()->getId() );
        }
        $query
            ->andWhere( 'f.public = true' )
            ->andWhere( 'f.id != :id' )
                ->setParameter( 'id',  $fiche->getId() )
            ->setMaxResults(25);

        $similaires = $query->getQuery()->getResult();

        // navigation dans la recherche
        $fiches_recherche = null;
        $session = $this->getRequest()->getSession();

        if ( $session->has('recherche') || $session->has('rechercheavancee_ids')) {
            if ( 'a' == $request->get('form') ) {
                $recherche = $session->get('rechercheavancee_ids');
            } else {
                $recherche = $session->get('recherche');
            }
            $index = array_search($fiche->getId(), $recherche);
            $prev = $index > 0 ? $recherche[$index - 1] : false;
            $next = $index < count($recherche)-1 ? $recherche[$index + 1] : false;

            // carousel recherche
            $repository = $this->getDoctrine()
                               ->getManager()
                               ->getRepository( 'CallistoFichesBundle:Fiches' );
            $query = $repository->createQueryBuilder( 'f' );

            $query
                ->where('f.id IN (:ids)')
                    ->setParameter( 'ids', $session->get('recherche') )
                ->orderBy( 'f.datefrom', 'ASC' )
                ->setMaxResults(25);

            $fiches_recherche = $query->getQuery()->getResult();
        }

        $emailform = $this->createFormBuilder()
            ->add('email', 'email')
            ->add('message2', 'textarea', array('attr' => array('style' => 'display: none;')))
            ->add('envoyer', 'submit')
            ->getForm();

        $emailform->handleRequest($request);
        $alert = false;
        if( 'POST' == $request->getMethod() ) {
            if ($emailform->isValid()) {
                $data = $emailform->getData();
                $titles = json_decode($data['message2'], true);
                unset($titles['__jstorage_meta']); // entrée de jStorage

                $content = $this->renderView('CallistoFichesBundle:Default:email.html.twig', array('titles' => $titles));

                $message = \Swift_Message::newInstance()
                    ->setSubject('Sélection de fiches Callisto')
                    ->setFrom($data['email'])
                    ->setTo($data['email']);

#                // on remplace les images hébergées par une version embarquée
#                foreach ($titles as $title) {
##                    $embed = $message->embed(\Swift_Image::fromPath($title['image']));
##                    $content = str_replace($title['image'], $message->embed(\Swift_Image::fromPath($title['image'])), $content);
#                    // retrouver le chemin local à partir de l'URL de l'image
#                    $image = explode('callisto/', $title['image']);
#                    $path = explode("src/Callisto/FichesBundle/Controller",__DIR__);
#                    $embed = $message->embed(\Swift_Image::fromPath($path[0].$image[1]));
#                    $content = str_replace($title['image'], $message->embed(\Swift_Image::fromPath($path[0].$image[1])), $content);
#                }

                $message
                    ->addPart($content, 'text/html')
                ;

                $this->get('mailer')->send($message);
                $alert = 'L\'e-mail a été envoyé à '. $data['email'].'.';
            }
        }

        return array(
            'titre' => $fiche->getTitre(),
            'fiche' => $fiche,
            'similaires' => $similaires,
            'c' => count($fiche->getfichesassociees()),
            'prev' => $prev,
            'next' => $next,
            'recherche' => $recherche,
            'recherche_from' => $request->query->get('from'),
            'fiches_recherche' => $fiches_recherche,
            'emailform'  => $emailform->createView(),
            'alert' => $alert,
            'derniere_recherche' => $session->get('derniere_recherche'),
            'nav_type' => $request->get('form'),
            'shib' => $session->get('isUserShibAuthenticated')
        );
    }

    /**
     * @Route("/print_panier", name="print_panier")
     * @Template()
     */
    public function printpanierAction( Request $request )
    {
        $titles = "";
        if( 'POST' == $request->getMethod() ) {
            $data = $request->request->all();
            $titles = json_decode($data['form']['message'], true);
            unset($titles['__jstorage_meta']); // entrée de jStorage
            return $this->render(
                'CallistoFichesBundle:Default:printpanier.html.twig',
                array('titre' => 'Imprimer le panier',
                      'titles' => $titles)
            );
        }
        return array(
            'titre' => 'Imprimer le panier',
            'titles' => $titles,
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
                        ->getManager();

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
        $page = $this->getDoctrine()
                        ->getManager()->getRepository( 'CallistoFichesBundle:Pages' )->find( '3' );
        return $this->render('CallistoFichesBundle:Default:page.html.twig',
            array(
                'titre' => 'FAQ',
                'page'  => $page,
            )
        );
    }

    /**
     * @Route("/faq")
     * @Template()
     */
    public function faqAction()
    {
        $page = $this->getDoctrine()
                        ->getManager()->getRepository( 'CallistoFichesBundle:Pages' )->find( '2' );
        return $this->render('CallistoFichesBundle:Default:page.html.twig',
            array(
                'titre' => 'FAQ',
                'page'  => $page,
            )
        );
    }



    /**
     * @Route("/email")
     * @Template()
     */
    public function emailAction()
    {
        $defaultData = array('email' => 'Entrez l\'adresse e-mail du destinataire.');
        $emailform = $this->createFormBuilder($defaultData)
            ->add('email', 'email')
            ->add('message', 'textarea')
            ->getForm();

        $emailform->handleRequest($request);

        if ($emailform->isValid()) {
            $data = $form->getData();
        }

        return array(
            'titre' => 'Formulaire d\'envoi',
            'form'  => $emailform->createView()
        );
    }


//*********************** Recherche avancée *********************************//
    /**
     * @Route("/rechercheavancee", name="rechercheavancee")
     */
    public function advancedSearchAction()
    {
        $session = $this->getRequest()->getSession();

        $fiche = new Fiches();
        $fiches = array();

        $fiche->setDatefrom( -2000 );
        $fiche->setDateto( 2000 );

        $form = $this->createForm( new AdvancedSearchFiches(), $fiche );
        $request = $this->get( 'request' );

        $categories = $this->getDoctrine()
            ->getManager()
            ->getRepository('CallistoFichesBundle:Categorie')
            ->findBy(array(), array('nom' => 'asc'));

        $materiaux = $this->getDoctrine()
            ->getManager()
            ->getRepository('CallistoFichesBundle:Materiau')
            ->findBy(array(), array('nom' => 'asc'));

        $periodes = $this->getDoctrine()
            ->getManager()
            ->getRepository('CallistoFichesBundle:Periode')
            ->findBy(array(), array('nom' => 'asc'));

        $session = $this->getRequest()->getSession();
        $request = $this->get( 'request' );

        // On récupère le repository
        $repository = $this->getDoctrine()->getManager()->getRepository( 'CallistoFichesBundle:Fiches' );

        if( $session->has('rechercheavancee_ids') && 'POST' != $request->getMethod() ) {
            $form->bind($session->get('request_avancee'));
            $resultat = true;
            $query = $repository->createQueryBuilder( 'f' );

            if ( count($session->get('rechercheavancee_ids')) > 0 ) {
                $query
                    ->where('f.id IN (:ids)')
                        ->setParameter( 'ids',$session->get('rechercheavancee_ids') )
                    ->andWhere( 'f.public = true' )
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
        }

        if( 'POST' == $request->getMethod() ) {

            //le résultat posté doit être mappé sur l'entité sur lequel le formulaire est basé
            $form->bind($request);

            $qb = $repository->createQueryBuilder('f');

            // $qb->setMaxResults(25);

            $data = array();

            /*
            à partir de _POST, on créé un tableau comme ceci :
            Array (
                [0] => Array
                        [field] => fiche
                        [value] => "AS329B2"

                [1] => Array
                        [field] => logic
                        [value] => "Ou"

                [2] => Array
                        [field] => categorie2
                        [value] => "lalala"

                [3] => Array
                        [field] => logic
                        [value] => "Et"

                [4] => Array
                        [field] => "monument"
                        [value] => "UNIVERSITÉ"
                ...
            */
                foreach($_POST as $field => $value) {
                    $field = substr($field, 0, strpos($field, '_'));
                    if(!empty($field) && !empty($value)) {
                        $data[] = array('field' => $field, 'value' => $value);
                    }
                }
#            print_r('<pre>');
#            print_r($data);
#            print_r('</pre>');

            $qb->andWhere('f.datefrom >= :from')
                    ->setParameter( 'from',  $form->getData()->getDatefrom() )
                ->andWhere( 'f.dateto <= :to' )
                    ->setParameter( 'to',  $form->getData()->getDateto() )
                ->andWhere( 'f.public = true' );

            /*
            1. Si c'est pas un champ de logique, on construit le SQL nécessaire
            2. On choisit la logique à appliquer
            */
            for($i = 0; $i < count($data); $i++) {
                $field = $data[$i]['field'];
                $value = $data[$i]['value'];

                if($i === 0) {
                    $logic_to_apply = "Et";
                } else {
                    $logic_to_apply = $data[$i-1]['value'];
                }

                switch($field) {
                    case 'logic':
                        continue;
                        break;
                    case 'categorie':
                    case 'periode':
                    case 'materiau':
                    case 'id':
                        if ( 'Sauf' === $logic_to_apply) {
                            $op = '!=';
                        } else {
                            $op = '=';
                        }
                        break;
                    default:
                        if ( 'Sauf' === $logic_to_apply) {
                            $op = 'NOT LIKE';
                        } else {
                            $op = 'LIKE';
                        }
                        $value = '%'.$value.'%';
                        break;
                }

                $field = 'f.'.$field;
                switch($logic_to_apply) {
                    case 'Ou':
                        $qb->orWhere($field.' '.$op.' :value_'.$i)
                            ->setParameter('value_'.$i, $value);
                        break;
                    case 'Et':
                        $qb->andWhere($field.' '.$op.' :value_'.$i)
                            ->setParameter('value_'.$i, $value);
                        break;
                    case 'Sauf':
                        $qb->andWhere($field.' '.$op.' :value_'.$i)
                            ->setParameter('value_'.$i, $value);
                        break;
                }

            $qb->orderBy( 'f.datefrom', 'ASC' );

#            $query = $qb->getDql();
#            print_r($query);

            $fiches = $qb->getQuery()->getResult();
            $ids = array();

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
            }            }

            // pour la mémoriation de la recherche avancée
#            $session->set('recherche', null);
#            $session->set('request', null);
            $session->set('request_avancee', $request);
            $session->set('rechercheavancee_ids', $ids);
            $session->set('rechercheavancee_data', $data);
            $session->set('derniere_recherche', 'avancee');
        }

        return $this->render( 'CallistoFichesBundle:Default:rechercheavancee.html.twig', array(
            'titre' => 'Effectuer une recherche',
            'form' => $form->createView(),
            'categories' => $categories,
            'periodes' => $periodes,
            'materiaux' => $materiaux,
            'rechercheavancee_data' => $session->get('rechercheavancee_data'),
            'fiches' => $fiches,
            'nav_type' => 'a',
            ));
    }
//*********************** Recherche avancée *********************************//





    /**
     * @Route("/effacer_requete", name="effacer_requete")
     * @Template()
     */
    public function effacerRequeteAction()
    {
        $session = $this->getRequest()->getSession();
            // pour la mémoriation de la recherche avancée
            $session->set('recherche', null);
            $session->set('request', null);
            return $this->rechercheAction();
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
//                         ->getManager();

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
//                         ->getManager();

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
//                         ->getManager();

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
