<?php

namespace App\Repository;

use App\Entity\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Session>
 *
 * @method Session|null find($id, $lockMode = null, $lockVersion = null)
 * @method Session|null findOneBy(array $criteria, array $orderBy = null)
 * @method Session[]    findAll()
 * @method Session[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

//    /**
//     * @return Session[] Returns an array of Session objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Session
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    // Afficher les stagiaires non inscrits dans une session
    public function findStagiaireNotIn($session_id) {
        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder();

        $qb = $sub;
        // sélectionner tous les stagiaires d'une session dont l'id est passé en paramètre
        $qb->select('s')
            ->from('App\Entity\Stagiaire', 's')
            ->leftJoin('s.sessions', 'se')
            ->where('se.id = :id');

        $sub = $em->createQueryBuilder();
        // sélectionner tous les stagiaires qui ne SONT PAS (NOT IN) dans le résultat précédent
        // on obtient donc les stagiaires non inscrits pour une session définie
        $sub->select('st')
            ->from('App\Entity\Stagiaire', 'st')
            ->where($sub->expr()->notIn('st.id', $qb->getDQL()))
            // requête paramétrée
            ->setParameter('id', $session_id)
            // trier la liste des stagiaires sur le nom de famille
            ->orderBy('st.nomStagiaire');

        // renvoyer le résultat
        $query = $sub->getQuery();
        return $query->getResult();
    }

    // Afficher les modules non programmée
    // public function findModuleNotIn($module_id) {
    //     $em = $this->getEntityManager();
    //     $sub = $em->createQueryBuilder();

    //     $qb = $sub;

    //     $qb->select('m')
    //         ->from('App\Entity\Module', 'p')
    //         ->leftJoin('p.programmes', 'pr')
    //         ->leftJoin('m.module', 'mo')
    //         ->where('pr.id = :id');
        
    //     $sub = $em->createQueryBuilder();
    //     // sélectionner tous les stagiaires qui ne SONT PAS (NOT IN) dans le résultat précédent
    //     // on obtient donc les modules non programmée
    //     $sub->select('mo')
    //         ->from('App\Entity\Module', 'mo')
    //         ->where($sub->expr()->notIn('mo.id', $qb->getDQL()))
    //         // requête paramétrée
    //         ->setParameter('id', $module_id)
    //         // trier la liste des modules par catégorie
    //         ->orderBy('mo.categorie'); 

    //         // renvoyer le résultat
    //         $query = $sub->getQuery();
    //         return $query->getResult();
    // }

    public function findNonProgrammer($session_id)
    {
        $entityManager = $this->getEntityManager();

        $subQuery = $entityManager->createQueryBuilder();
        $subQuery->select('IDENTITY(programme.module)')
            ->from('App\Entity\Programme', 'programme')
            ->where('programme.session = :session_id');

        $qb = $entityManager->createQueryBuilder();
        $qb->select('module')
            ->from('App\Entity\Module', 'module')
            ->where($qb->expr()->notIn('module.id', $subQuery->getDQL()))
            ->setParameter('session_id', $session_id)
            ->orderBy('module.nomModule');
        // Exécuter la requête
        $result = $qb->getQuery()->getResult();

        return $result;
    }
}
