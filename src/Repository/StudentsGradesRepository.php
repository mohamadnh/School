<?php

namespace App\Repository;

use App\Entity\StudentsGrades;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StudentsGrades|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudentsGrades|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudentsGrades[]    findAll()
 * @method StudentsGrades[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentsGradesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudentsGrades::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(StudentsGrades $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(StudentsGrades $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return StudentsGrades[] Returns an array of StudentsGrades objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StudentsGrades
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getFilterResult($type,$data){

        if($type == 'classe'){
            $query = $this->getEntityManager()->createQuery(
                'SELECT sg
                    FROM App:Student s, App:Classe c, App:StudentsGrades sg
                    WHERE s.classe = c.id and c.name = :data and sg.student = s.id'
            )
                ->setParameter('data', $data);
        }else if($type == 'course'){
            $query = $this->getEntityManager()->createQuery(
                'SELECT sg
                    FROM App:Course c, App:StudentsGrades sg
                    WHERE sg.course = c.id and c.name = :data'
            )
                ->setParameter('data', $data);
        } else if($type == 'grade'){
            $query = $this->getEntityManager()->createQuery(
                'SELECT sg
                    FROM App:StudentsGrades sg, App:Student s
                    WHERE sg.grade = :data and s.id = sg.student'
            )
                ->setParameter('data', $data);
        } else {
            $query = $this->getEntityManager()->createQuery(
                'SELECT sg
                    FROM App:StudentsGrades sg, App:Student s
                    WHERE s.'.$type.' = :data and s.id = sg.student'
            )
                ->setParameter('data', $data);
        }
        return $query->getResult();
    }
}
