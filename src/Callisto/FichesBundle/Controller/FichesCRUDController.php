<?php

namespace Callisto\FichesBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class FichesCRUDController extends CRUDController
{
#    public function publicationAction($id)
#    {
#        
#        return new Response('<html><body><h1>ICI</h1></body></html>');
#    }

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException|\Symfony\Component\Security\Core\Exception\AccessDeniedException
     *
     * @param mixed $id
     *
     * @return Response|RedirectResponse
     */
    public function publicationAction($id)
    {
        $id     = $this->get('request')->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        $url = $this->get('router')->generate('admin_callisto_fiches_fiches_edit', array('id' => $object->getId()), true);

        // retouver les adminsitrateurs du système (CallistoAdmin)
        $em = $this->container->get('doctrine.orm.entity_manager');
        $qb = $em->getRepository('CallistoFichesBundle:User')
                            ->createQueryBuilder('u')
                            ->leftJoin('u.groups', 'g')
                            ->where('g.name = \'CallistoPublication\'');

        $users = $qb->getQuery()->getResult();
        $dest = array();
        foreach ($users as $user) {
            $dest[] = $user->getEmail();
        }
#print_r($dest);exit();

        $message = \Swift_Message::newInstance()
            ->setSubject('Demande du publication d\'une fiche Callisto')
            ->setFrom(array('nte@unifr.ch' => 'Callisto / NTE'))
            ->setTo($dest)
            ->addPart("<html><body><h3>Callisto</h3>Une demande de publication a été faite pour la fiche suivante :<ul><li><a href=\"$url\">" . $object->getTitre() . " (#". $object->getId() .")</a></li></ul></body></html>", 'text/html')
        ;

        $object->setPublication(true);
        $em->flush();

        $this->get('mailer')->send($message);

        $this->addFlash('sonata_flash_success', 'Demande de publication envoyée.');

        return new RedirectResponse($this->admin->generateUrl('list'));
    }
}

