<?php

namespace Callisto\FichesBundle\Security\Authorization\Voter;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Acl\Voter\AclVoter;

use Callisto\FichesBundle\Entity\Fiches as Fiches;
use Callisto\FichesBundle\Entity\Periode as Periode;
use Callisto\FichesBundle\Entity\Materiau as Materiau;
use Callisto\FichesBundle\Entity\Categorie as Categorie;
use Callisto\FichesBundle\Entity\Pages as Pages;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Authorization\Voter\RoleVoter;

class FicheAclVoter extends AclVoter
{
    /**
    * {@InheritDoc}
    */
    public function supportsClass($class)
    {
        // support the Class-Scope ACL for votes with the custom permission map
        // return $class === 'Sonata\UserBundle\Admin\Entity\UserAdmin' || $is_subclass_of($class, 'FOS\UserBundle\Model\UserInterface');
        // if you use php >=5.3.7 you can check the inheritance with is_a($class, 'Sonata\UserBundle\Admin\Entity\UserAdmin');
        // support the Object-Scope ACL
#        return is_subclass_of($class, 'Callisto\FichesBundle\Entity\Fiches');
        return $class === 'Callisto\FichesBundle\Entity\Fiches' || $class === 'Callisto\FichesBundle\Entity\Periode'
                 || $class === 'Callisto\FichesBundle\Entity\Materiau' || $class === 'Callisto\FichesBundle\Entity\Categorie'
                 || $class === 'Callisto\FichesBundle\Entity\Pages';
    }

    public function supportsAttribute($attribute)
    {
        return $attribute === 'EDIT' || $attribute === 'DELETE';
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        if (!$this->supportsClass(get_class($object))) {
            return self::ACCESS_ABSTAIN;
        }

        if ('Callisto\FichesBundle\Entity\Fiches' === get_class($object)) {
            $roleVoter = new RoleVoter();
            $not_admin = $roleVoter->vote($token, $object, array('ROLE_CALLISTO_FICHES_ADMIN_FICHES_ADMIN')) === -1;

            $user = $token->getUser();

            foreach ($attributes as $attribute) {
                if ($this->supportsAttribute($attribute) && $object instanceof Fiches) {
                    if ($not_admin && ( ($attribute == 'DELETE') || ($object->getAuteur() != $user) ) ) {
                        return self::ACCESS_DENIED;
                    }
                }
            }
        }

        // ci dessous Voter pour d'autres objets mais pb de temps et petit bug impliquent ceci... TODO: les choses correctement.
        if ('Callisto\FichesBundle\Entity\Periode' === get_class($object)) {
            foreach ($attributes as $attribute) {
                if ($this->supportsAttribute($attribute) && $object instanceof Periode) {
                    if ( ($attribute == 'DELETE') && (count($object->getFiches()) > 0) ) {
                        return self::ACCESS_DENIED;
                    }
                }
            }
        }

        if ('Callisto\FichesBundle\Entity\Materiau' === get_class($object)) {
            foreach ($attributes as $attribute) {
                if ($this->supportsAttribute($attribute) && $object instanceof Materiau) {
                    if ( ($attribute == 'DELETE') && (count($object->getFiches()) > 0) ) {
                        return self::ACCESS_DENIED;
                    }
                }
            }
        }

        if ('Callisto\FichesBundle\Entity\Categorie' === get_class($object)) {
            foreach ($attributes as $attribute) {
                if ($this->supportsAttribute($attribute) && $object instanceof Categorie) {
                    if ( ($attribute == 'DELETE') && (count($object->getFiches()) > 0) ) {
                        return self::ACCESS_DENIED;
                    }
                }
            }
        }

        if ('Callisto\FichesBundle\Entity\Pages' === get_class($object)) {
            foreach ($attributes as $attribute) {
                if ($this->supportsAttribute($attribute) && $object instanceof Pages) {
                    if ( ($attribute == 'DELETE') && ( in_array($object->getId(), array(1, 2, 3, 4))) ) {
                        return self::ACCESS_DENIED;
                    }
                }
            }
        }

        // use the parent vote with the custom permission map:
        // return parent::vote($token, $object, $attributes);
        // otherwise leave the permission voting to the AclVoter that is using the default permission map
        return self::ACCESS_ABSTAIN;
    }
}
