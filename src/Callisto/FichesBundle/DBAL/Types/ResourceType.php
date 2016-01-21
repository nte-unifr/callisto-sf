<?php
namespace Callisto\FichesBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class ResourceType extends AbstractEnumType
{
    const OT = 'OT';
    const TB = 'TB';
    const GBA = 'GBA';
    const GBP = 'GBP';

    protected static $choices = [
        self::OT => 'Autre ressource',
        self::TB => 'Bibliographie thématique',
        self::GBA => 'Bibliographie générale toutes périodes',
        self::GBP => 'Bibliographie générale par période'
    ];
}
