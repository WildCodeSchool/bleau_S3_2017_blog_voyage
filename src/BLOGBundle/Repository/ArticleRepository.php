<?php

// src/BLOGBundle/Repository/ArticleRepository.php

namespace BLOGBundle\Repository;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends \Doctrine\ORM\EntityRepository
{
	
		public function myFindAll()
		{
			$queryBuilder = $this->createQueryBuilder('a'); // Nom de l'entité (première lettre suffit)
			return $queryBuilder->getQuery()->getResult();
		}
		
		public function myFindOne($id)
		{
			$qb = $this->createQueryBuilder('a');
			$qb->where('a.id = :id')->setParameter('id', $id);
			return $qb->getQuery()->getResult();
		}
		
		public function myFindByTitle($title, $date)
		{
			$qb = $this->createQueryBuilder('a');
			$qb->where('a.title = :title')->setParameter('title', $title)
			   ->andWhere('a.date > :date')->setParameter('date', $date);
			return $qb->getQuery()->getResult();
		}
		
		public function myFindByDateRange($start, $end)
		{
			$qb = $this->createQueryBuilder('a');
			$qb->where('a.date BETWEEN :start AND :end')
			   ->setParameter('start', $start)
			   ->setParameter('end', $end);
			return $qb->getQuery()->getResult();
		}
}
