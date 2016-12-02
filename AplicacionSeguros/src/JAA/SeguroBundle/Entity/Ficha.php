<?php

namespace JAA\SeguroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Ficha
 * @ORM\Entity(repositoryClass="JAA\SeguroBundle\Entity\FichaRepository"))
 * @ORM\Table(name="ficha", indexes={@ORM\Index(name="fk_tipoficha_ficha", columns={"tipoficha_id"}), @ORM\Index(name="fk_user_post", columns={"usuario_id"})})
 * @ORM\HasLifecycleCallbacks()
 */
class Ficha
{
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
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="valor", type="string", length=255, nullable=true)
     */
    private $valor;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=50, nullable=true)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="time", nullable=true)
     */
    private $time;

    /**
     * @var JAA\SeguroBundle\Entity\Tipoficha
     *
     * @ORM\ManyToOne(targetEntity="Tipoficha", inversedBy="fichas")
     * @ORM\JoinColumn(name="tipoficha_id", referencedColumnName="id")
     */
    private $tipoficha;

    /**
     * @var JAA\SeguroBundle\Entity\Usuario
	 *
	 * @ORM\ManyToOne(targetEntity="Usuario")
	 * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    public $usuario;
	///////////////////////////////////////////////////////////////////////////////////
	public $pubkey = '';
    public $privkey = '';

	/**
	 * encrypt data
	 * @param string $data
	 *
	 * @return string $data
	 */
    public function encrypt($data)
    {	
		$this->pubkey ='';
		$fp = fopen("../src/JAA/SeguroBundle/Entity/public.txt","r");
		for ($i =0; $i<=5;$i++){
			$this->pubkey .= fgets($fp);
		}
        fclose($fp);
		if (openssl_public_encrypt($data, $encrypted, $this->pubkey))
            $data = base64_encode($encrypted);
		$this->pubkey ='';
        return $data;
    }
	/**
	 * decrypt data
	 * @param string $data
	 *
	 * @return string $data
	 */
    public function decrypt($data)
    {	$this->privkey = '';
		$fp2 = fopen("../src/JAA/SeguroBundle/Entity/private.txt","r");
		for ($i =0; $i<=15; $i++){
			 $this->privkey .= fgets($fp2);
		}
		fclose($fp2);

        if (openssl_private_decrypt(base64_decode($data), $decrypted, $this->privkey))
            $data = $decrypted;
        else
            $data = '';
		$this->privkey = '';
        return $data;
    }
	///////////////////////////////////////////////////////////////////////////////////
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
     * Set title
     *
     * @param string $title
     *
     * @return Ficha
     */
    public function setTitle($title)
    {
		
		$title =$this->encrypt($title);
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
		
		$aux = $this->decrypt($this->title);
		
        return $aux;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Ficha
     */
    public function setDescription($description)
    {
		
		$description =$this->encrypt($description);
       
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
		
		$aux = $this->decrypt($this->description);
        return $aux;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Ficha
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set valor
     *
     * @param string $valor
     *
     * @return Ficha
     */
    public function setValor($valor)
    {
		
		$valor = $this->encrypt($valor);
       
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return string
     */
    public function getValor()
    {
		
		$aux = $this->decrypt($this->valor);
        return $aux;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Ficha
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Ficha
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     *
     * @return Ficha
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set tipoficha
     *
     * @param \JAA\SeguroBundle\Entity\Tipoficha $tipoficha
     *
     * @return Ficha
     */
    public function setTipoficha(\JAA\SeguroBundle\Entity\Tipoficha $tipoficha = null)
    {
        $this->tipoficha = $tipoficha;

        return $this;
    }

    /**
     * Get tipoficha
     *
     * @return \JAA\SeguroBundle\Entity\Tipoficha
     */
    public function getTipoficha()
    {
        return $this->tipoficha;
    }

    /**
     * Set usuario
     *
     * @param \JAA\SeguroBundle\Entity\Usuario $usuario
     *
     * @return Ficha
     */
    public function setUsuario(\JAA\SeguroBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \JAA\SeguroBundle\Entity\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
	
	
	/*public function getPaginateFicha($pageSize=3,$currentPage){
        $em=$this->getEntityManager();
        
        $posts=$this->findBy(array(), array('id' => 'DESC'));
        
        $dql = "SELECT p FROM JAA\SeguroBundle\Entity\Ficha p ORDER BY p.id DESC";
        $query = $em->createQuery($dql)
                               ->setFirstResult($pageSize * ($currentPage - 1))
                               ->setMaxResults($pageSize);

        $paginator = new Paginator($query, $fetchJoinCollection = true);

        return $paginator;
    }*/
	
	//SUBIDAS
    public function getAbsolutePath() {
        return null === $this->image ? null : $this->getUploadRootDir() . '/' . $this->image;
    }

    public function getWebPath() {
        return null === $this->image ? null : $this->getUploadDir() . '/' . $this->image;
    }

    public function getUploadRootDir() {
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        return 'uploads';
    }

    public function upload() {
        
        if (null === $this->getImage()) {
            return;
        }

       
        $this->file = null;
    }

    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }
}
