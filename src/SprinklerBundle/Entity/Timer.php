<?php

namespace SprinklerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Timer
 *
 * @ORM\Table(name="timer")
 * @ORM\Entity
 */
class Timer
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="zone_id", type="integer", nullable=false)
     */
    private $zoneId;

    /**
     * @var integer
     *
     * @ORM\Column(name="day", type="integer", nullable=false)
     */
    private $day;

    /**
     * @var string
     *
     * @ORM\Column(name="start", type="string", length=5, nullable=false)
     */
    private $start;

    /**
     * @var string
     *
     * @ORM\Column(name="end", type="string", length=5, nullable=false)
     */
    private $end;

    /**
     * @var \SprinklerBundle\Entity\Zone
     *
     * @ORM\ManyToOne(targetEntity="SprinklerBundle\Entity\Zone")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="zone_id", referencedColumnName="id")
     * })
     */
    private $zone;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set zoneId
     *
     * @param integer $zoneId
     *
     * @return Timer
     */
    public function setZoneId($zoneId)
    {
        $this->zoneId = $zoneId;

        return $this;
    }

    /**
     * Get zoneId
     *
     * @return integer
     */
    public function getZoneId()
    {
        return $this->zoneId;
    }

    /**
     * Set day
     *
     * @param integer $day
     *
     * @return Timer
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Get day
     *
     * @return integer
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set start
     *
     * @param string $start
     *
     * @return Timer
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return string
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param string $end
     *
     * @return Timer
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return string
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set zone
     *
     * @param \SprinklerBundle\Entity\Zone $zone
     *
     * @return Timer
     */
    public function setZone(\SprinklerBundle\Entity\Zone $zone = null)
    {
        $this->zone = $zone;

        return $this;
    }

    /**
     * Get zone
     *
     * @return \SprinklerBundle\Entity\Zone
     */
    public function getZone()
    {
        return $this->zone;
    }
}
