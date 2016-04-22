<?php
namespace SprinklerBundle\Repository;


use SprinklerBundle\Entity\Zone;

class ZoneRepository extends Repository
{
    public $lastZone = null;
    public function getAllZones()
    {
        $zones = $this->em->getRepository('SprinklerBundle:Zone')->findAll();
        return $zones;
    }
    public function isValidRelay($relay){
        $this->lastZone = $this->em->getRepository('SprinklerBundle:Zone')->findOneByRelay($relay);
        return $this->lastZone!==null;
    }
    public function addZone(Zone $zone){
        $this->em->persist($zone);
        $this->em->flush();
        return true;
    }
    public function deleteZone($relay){
        if($this->lastZone===null || $this->lastZone->getRelay()!=$relay){
            $zone = $this->loadZoneByRelay($relay);
            if($zone===null){
                return false;
            }
        }else{
            $zone = $this->lastZone;
        }
        $this->em->remove($zone);
        $this->em->flush();
        return true;
    }
    public function loadZoneByRelay($relay){
        $this->lastZone = $this->em->getRepository('SprinklerBundle:Zone')->findOneByRelay($relay);
        return $this->lastZone;
    }
    public function updateZone(Zone $zone){
        $this->em->flush();
        return true;
    }
}