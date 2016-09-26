<?php

namespace Servicios;
 
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Symfony\Component\HttpFoundation\Session\Session;

class Paginacion{
    private $count;
    private $currentPage;
    private $totalPages;
    //private $session;
    
    /*public function __construct(Session $session) {
        $this->session = $session;
        var_dump($session);
    }*/
 
    /**
    * paginate results
    *
    * @param $query - naming is a bit off as it can be a NativeQuery OR QueryBuilder, we'll survive eventually
    * @param int $page
    * @param $limit
    * @return array
    */
    public function paginar($query, $page, $limit){

        //$session = $GLOBALS['kernel']->getContainer()->get('session');
        
        

        // setting current page
        /*if ($session->has('paginaActual')){
            $page = $session->get('paginaActual', 1);
        }else{
            $page = 1;
            $session->set('paginaActual', 1);
        }*/
        //var_dump($session->all());exit();
        
        $this->currentPage = (int)$page;
        
        // set the limit
        $limit = (int)$limit;

        // this covers the NativeQuery case
        if (is_a($query, '\Doctrine\ORM\NativeQuery'))        {

            // do a count for all query, create a separate NativeQuery only for that
            $sqlInitial = $query->getSQL();
 
            $rsm = new ResultSetMappingBuilder($query->getEntityManager());
            $rsm->addScalarResult('count', 'count');
 
            $sqlCount = 'select count(*) as count from (' . $sqlInitial . ') as item';
            $qCount = $query->getEntityManager()->createNativeQuery($sqlCount, $rsm);
            $qCount->setParameters($query->getParameters());
 
            $resultCount = (int)$qCount->getSingleScalarResult();
            $this->count = $resultCount;
 
            // then, add the limit - paginate for current page
            $query->setSQL($query->getSQL() . ' limit ' . (($page - 1) * $limit) . ', ' . $limit);
        }
        // this covers the QueryBuilder case, turning it into Query
        elseif(is_a($query, '\Doctrine\ORM\QueryBuilder')){

            // set limit and offset, getting the query out of queryBuilder
            $query = $query->setFirstResult(($page -1) * $limit)->setMaxResults($limit)->getQuery();
 
            // using already build Doctrine paginator to get a count
            // for all records. Saves load.
            $paginator = new DoctrinePaginator($query, $fetchJoinCollection = true);
            $this->count = count($paginator);
        }
 
        // set total pages
        $this->totalPages = ceil($this->count / $limit);

        return $query->getResult();
    }
 
    /**
    * get current page
    *
    * @return int
    */
    public function getCurrentPage(){
        return $this->currentPage;
    }
 
    /**
    * get total pages
    *
    * @return int
    */
    public function getTotalPages(){
       return $this->totalPages;
    }

    /**
    * get total result count
    *
    * @return int
    */
    public function getCount(){
         return $this->count;
    }
}