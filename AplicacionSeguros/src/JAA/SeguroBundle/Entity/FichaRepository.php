<?php
namespace JAA\SeguroBundle\Entity;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\Mapping as ORM;
/**
 * JAA\SeguroBundle\Entity\FichaRepository
 * 
 */

class FichaRepository extends EntityRepository{

    /**
	 * getPaginateFicha
	 *
	 * @param Integer $pageSize
	 * @param Integer $currentPage
	 * @param JAA\SeguroBundle\Entity\Usuario $user
	 * @return Doctrine\ORM\Tools\Pagination\Paginator
	 */
    public function getPaginateFicha($pageSize=3,$currentPage){
        $em=$this->getEntityManager();
        

        
        $dql = "SELECT p FROM JAA\SeguroBundle\Entity\Ficha p  ORDER BY p.id DESC";

        $query = $em->createQuery($dql)
                               ->setFirstResult($pageSize * ($currentPage - 1))
                               ->setMaxResults($pageSize);

        $paginator = new Paginator($query, $fetchJoinCollection = true);

        return $paginator;
    }

    /**
	 * getPaginateFicha
	 *
	 * @param Integer $pageSize
	 * @param Integer $currentPage
	 * @param JAA\SeguroBundle\Entity\Usuario $user
	 * @return Doctrine\ORM\Tools\Pagination\Paginator
	 */
    public function getPaginate1Ficha($pageSize=3,$currentPage, $user){
        $em=$this->getEntityManager();
        

        
        $dql = "SELECT p FROM JAA\SeguroBundle\Entity\Ficha p WHERE p.usuario = :user ORDER BY p.id DESC";

        $query = $em->createQuery($dql);
		$query->setParameter('user',$user);
		$query->setFirstResult($pageSize * ($currentPage - 1))
                               ->setMaxResults($pageSize);
		
        $paginator = new Paginator($query, $fetchJoinCollection = true);

        return $paginator;
    }

	
    /**
	 * getPaginate2Ficha
	 *
	 * @param Integer $pageSize
	 * @param Integer $currentPage
	 * @param JAA\SeguroBundle\Entity\Usuario $user
	 * @param JAA\SeguroBundle\Entity\Tipoficha $tipoficha
	 * @return Doctrine\ORM\Tools\Pagination\Paginator
	 */
	public function getPaginate2Ficha($pageSize=3,$currentPage, $user, $tipoficha){
        $em=$this->getEntityManager();
        
        
        $dql = "SELECT p FROM JAA\SeguroBundle\Entity\Ficha p WHERE
p.usuario = :user AND p.tipoficha = :tipoficha ORDER BY p.id DESC";
		$query = $em->createQuery($dql);
		$query->setParameters(array(
   			 'user' => $user,
  			 'tipoficha' => $tipoficha,
    		 ));

		$query->setFirstResult($pageSize * ($currentPage - 1))
                               ->setMaxResults($pageSize);

        $paginator = new Paginator($query, $fetchJoinCollection = true);

        return $paginator;
    }

	/**
	 * getPaginate3Ficha
	 *
	 * @param Integer $pageSize
	 * @param Integer $currentPage
	 * @param JAA\SeguroBundle\Entity\Usuario $user
	 * @param JAA\SeguroBundle\Entity\Tipoficha $title
	 * @return Doctrine\ORM\Tools\Pagination\Paginator
	 */
    public function getPaginate3Ficha($pageSize=3,$currentPage,$user, $title){
        $em=$this->getEntityManager();
        
        
        $dql = "SELECT p FROM JAA\SeguroBundle\Entity\Ficha p WHERE p.usuario = :user AND p.title LIKE :title ORDER BY p.id DESC";
		$query = $em->createQuery($dql);
		$query->setParameters(array(
   			 'user' => $user,
  			 'title' => $title,
    		 ));

		$query->setFirstResult($pageSize * ($currentPage - 1))
                               ->setMaxResults($pageSize);

        $paginator = new Paginator($query, $fetchJoinCollection = true);

        return $paginator;
    }
    
        
}
