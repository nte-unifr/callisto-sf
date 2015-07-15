<?php

namespace Callisto\FichesBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\Common\Collections\ArrayCollection;

class ResourcesController extends Controller
{
    /**
     * @Route("/ressources", name="resources")
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository('CallistoFichesBundle:Link');
        $links = $repository->findAll();

        $repository = $this->getDoctrine()->getRepository('CallistoFichesBundle:Document');
        $sts = $repository->findByType("ST");
        $tbs = $repository->findByType("TB");

        return $this->render('CallistoFichesBundle:Resources:index.html.twig', array(
            'links' => $links,
            'sts' => $sts,
            'tbs' => $tbs,
            'titre' => "Ressources"
        ));
    }
}
