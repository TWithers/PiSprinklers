<?php

namespace SprinklerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zone
 *
 * @ORM\Table(name="zone")
 * @ORM\Entity
 */
class Zone
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
     * @var string
     *
     * @ORM\Column(name="relay", type="string", length=60, nullable=false)
     */
    private $relay;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=60, nullable=false)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="image", type="boolean", nullable=false)
     */
    private $image;

    /**
     * @var boolean
     *
     * @ORM\Column(name="override", type="boolean", nullable=false)
     */
    private $override = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="overrideEndTime", type="string", length=5, nullable=true)
     */
    private $overrideendtime;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="SprinklerBundle\Entity\Timer", mappedBy="zone")
     */
    private $timers;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->timers = new \Doctrine\Common\Collections\ArrayCollection();
    }


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
     * Set relay
     *
     * @param string $relay
     *
     * @return Zone
     */
    public function setRelay($relay)
    {
        $this->relay = $relay;

        return $this;
    }

    /**
     * Get relay
     *
     * @return string
     */
    public function getRelay()
    {
        return $this->relay;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Zone
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set image
     *
     * @param boolean $image
     *
     * @return Zone
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return boolean
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set override
     *
     * @param boolean $override
     *
     * @return Zone
     */
    public function setOverride($override)
    {
        $this->override = $override;

        return $this;
    }

    /**
     * Get override
     *
     * @return boolean
     */
    public function getOverride()
    {
        return $this->override;
    }

    /**
     * Set overrideendtime
     *
     * @param string $overrideendtime
     *
     * @return Zone
     */
    public function setOverrideendtime($overrideendtime)
    {
        $this->overrideendtime = $overrideendtime;

        return $this;
    }

    /**
     * Get overrideendtime
     *
     * @return string
     */
    public function getOverrideendtime()
    {
        return $this->overrideendtime;
    }

    /**
     * Add timer
     *
     * @param \SprinklerBundle\Entity\Timer $timer
     *
     * @return Zone
     */
    public function addTimer(\SprinklerBundle\Entity\Timer $timer)
    {
        $this->timers[] = $timer;

        return $this;
    }

    /**
     * Remove timer
     *
     * @param \SprinklerBundle\Entity\Timer $timer
     */
    public function removeTimer(\SprinklerBundle\Entity\Timer $timer)
    {
        $this->timers->removeElement($timer);
    }

    /**
     * Get timers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTimers()
    {
        return $this->timers;
    }
}
