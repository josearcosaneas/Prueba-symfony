<?php

namespace JAA\SeguroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Tipoficha
 *
 * @ORM\Table(name="tipoficha")
 * @ORM\Entity
 */
class Tipoficha
{
	
	
	/**
	 * @var \JAA\SeguroBundle\Entity\Ficha
	 *
     * @ORM\OneToMany(targetEntity="Ficha", mappedBy="tipoficha")
     */
    protected $fichas;
 
    public function __construct()
    {
        $this->fichas = new ArrayCollection();
    }
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    public $name;



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
     * Set name
     *
     * @param string $name
     *
     * @return Tipoficha
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
     * Add Fichas
     *
     * @param  Ficha $ficha
     * @return Course
     */
    public function addFichas($ficha)
    {
        $this->fichas[] = $ficha;

        return $this;
    }

    /**
     * Get Fichas
     *
     * @return ArrayCollection
     */
    public function getFichas()
    {
        return $this->fichas;
    }
	
}
