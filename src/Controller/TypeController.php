<?php

namespace App\Controller;

use App\Entity\Type;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TypeController extends AbstractController
{
    #[Route('/types', name: 'app_types')]
    public function showAll(TypeRepository $typeRepository): Response
    {
        return $this->render('type/types.html.twig', [
            'types' => $typeRepository->findAll() ,
        ]);
    }

    #[Route('/types/{typeName<[a-zA-Z_\s-]+>}', name: 'app_type')]
    public function show(string $typeName, EntityManagerInterface $entityManager, TypeRepository $typeRepository): Response
    {
        $type = $typeRepository->findOneBy(['name' => $typeName]);
    
        if (!$type) {
            throw $this->createNotFoundException('Type not found');
        }
    
        $typeId = $type->getId();
    
        $previousType = $entityManager->createQueryBuilder()
            ->select('t')
            ->from(type::class, 't')
            ->where('t.id < :currentTypeId')
            ->orderBy('t.id', 'DESC')
            ->setParameter('currentTypeId', $typeId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    
        $nextType = $entityManager->createQueryBuilder()
            ->select('t')
            ->from(Type::class, 't')
            ->where('t.id > :currentTypeId')
            ->orderBy('t.id', 'ASC')
            ->setParameter('currentTypeId', $typeId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    
        return $this->render('type/type.html.twig', [
            'typeName' => $typeName,
            'previousType' => $previousType,
            'nextType' => $nextType,
        ]);
    }
    
    
}