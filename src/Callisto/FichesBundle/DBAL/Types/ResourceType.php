<?php
namespace Callisto\FichesBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class ResourceType extends AbstractEnumType
{
    const OT = 'Autre';
    const TB = 'Bibliographie Thématique';

    protected static $choices = [
        self::OT => 'Autre',
        self::TB => 'Bibliographie Thématique'
    ];
}
