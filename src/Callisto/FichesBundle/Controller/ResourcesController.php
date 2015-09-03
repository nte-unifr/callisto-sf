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
        $links      = $repository->findByType("Autre");
        $extTBs     = $repository->findByType("Bibliographie Thématique");

        $repository = $this->getDoctrine()->getRepository('CallistoFichesBundle:Document');
        $others     = $repository->findByType("Autre");
        $intTBs     = $repository->findByType("Bibliographie Thématique");

        return $this->render('CallistoFichesBundle:Resources:index.html.twig', array(
            'links'     => $links,
            'extTBs'    => $extTBs,
            'others'    => $others,
            'intTBs'    => $intTBs,
            'titre'     => "Ressources"
        ));
    }
}
