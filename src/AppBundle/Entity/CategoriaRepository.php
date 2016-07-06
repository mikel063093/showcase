<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CategoriaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoriaRepository extends EntityRepository
{
	public function findTodos($limit,$offset){
		
		$em = $this->getEntityManager();

		$consulta = $em->createQueryBuilder()
                        ->addSelect('c')
                        ->from('AppBundle:Categoria', 'c');
        $consulta->setFirstResult($offset);
		$consulta->setMaxResults($limit);
        return $consulta->getQuery()->getResult();
	}
}
