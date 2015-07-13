<?php
namespace Callisto\FichesBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class DocumentType extends AbstractEnumType
{
    const STANDARD              = 'ST';
    const THEMATIC_BIBLIOGRAPHY = 'TB';

    protected static $choices = [
        self::STANDARD              => 'Standard',
        self::THEMATIC_BIBLIOGRAPHY => 'Bibliographie Thématique'
    ];
}
