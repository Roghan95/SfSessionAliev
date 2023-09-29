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

        // récupérer l'entity manager
        $em = $this->getEntityManager();

        // créer une sous-requête
        $sub = $em->createQueryBuilder();

        $qb = $sub;
        // sélectionner tous les stagiaires d'une session dont l'id est passé en paramètre
        $qb->select('s') // SELECT stagiaire
            ->from('App\Entity\Stagiaire', 's') // FROM stagiaire
            ->leftJoin('s.sessions', 'se') // LEFT JOIN stagiaire_session
            ->where('se.id = :id'); // WHERE stagiaire_session.session_id = :id

        $sub = $em->createQueryBuilder(); // créer une sous-requête

        // sélectionner tous les stagiaires qui ne SONT PAS (NOT IN) dans le résultat précédent
        // on obtient donc les stagiaires non inscrits pour une session définie
        $sub->select('st') // SELECT stagiaire

            ->from('App\Entity\Stagiaire', 'st') // FROM stagiaire

            ->where($sub->expr()->notIn('st.id', $qb->getDQL())) // WHERE stagiaire.id NOT IN (sous-requête)

            // requête paramétrée
            ->setParameter('id', $session_id) // paramètre :id = $session_id

            // trier la liste des stagiaires sur le nom de famille
            ->orderBy('st.nomStagiaire');

        // renvoyer le résultat
        $query = $sub->getQuery();
        return $query->getResult();
    }


    // Afficher les modules non programmer dans une session
    public function findNonProgrammer($session_id)
    {
        // Récupérer l'entity manager
        $entityManager = $this->getEntityManager();

        // Créer une sous-requête
        $subQuery = $entityManager->createQueryBuilder();
        // Sélectionner tous les formateurs qui sont dans une session dont l'id est passé en paramètre
        $subQuery->select('IDENTITY(programme.module)') // SELECT programme.module
            ->from('App\Entity\Programme', 'programme') // FROM programme
            ->where('programme.session = :session_id'); // WHERE programme.session = :session_id

        
        $qb = $entityManager->createQueryBuilder(); // Créer une requête
        // Sélectionner tous les formateurs qui ne sont pas dans le résultat de la sous-requête
        $qb->select('module') // SELECT module
            ->from('App\Entity\Module', 'module') // FROM module

             // WHERE module.id NOT IN (sous-requête)
            ->where($qb->expr()->notIn('module.id', $subQuery->getDQL()))

            // requête paramétrée
            ->setParameter('session_id', $session_id)

            // trier la liste des formateurs sur le nom de famille
            ->orderBy('module.nomModule');

        // Exécuter la requête
        $result = $qb->getQuery()->getResult();

        // Renvoyer le résultat
        return $result;
    }
}
