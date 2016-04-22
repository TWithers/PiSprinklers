<?php

namespace SprinklerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Zone
 *
 * @ORM\Table(name="zone")
 * @ORM\Entity
 * @UniqueEntity("relay")
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
     * @ORM\Column(name="relay", type="smallint", nullable=false, unique=true)
     * @Assert\NotBlank
     * @Assert\Type(type="numeric",message="This value should be a number.")
     */
    private $relay;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=60, nullable=false)
     * @Assert\NotBlank
     * @Assert\Length(max=60)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="image", type="smallint", nullable=false)
     * @Assert\NotBlank
     * @Assert\Choice(choices={1,2})
     */
    private $image;


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
     * @param smallint $relay
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
     * @return smallint
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
