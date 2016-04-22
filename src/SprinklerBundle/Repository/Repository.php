<?php
namespace SprinklerBundle\Repository;

use Doctrine\ORM\EntityManager;

class Repository{
    public $em;

    public function __construct($em){
        $this->em = $em;
    }
}