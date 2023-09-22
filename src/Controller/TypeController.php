<?php

namespace App\Controller;

use App\Entity\Type;
use App\Form\TypeType;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TypeController extends AbstractController
{
    #[Route('/types', name: 'app_types')]
    public function showAll(TypeRepository $typeRepository): Response
    {
        return $this->render('type/types.html.twig', [
            'types' => $typeRepository->findAll() ,
        ]);
    }

    #[Route('/type/{slug}', name: 'app_type')]
    public function show(string $slug, EntityManagerInterface $entityManager, TypeRepository $typeRepository): Response
    {
        $type = $typeRepository->findOneBy(['slug' => $slug]);
    
        if (!$type)
        {
            throw $this->createNotFoundException('Type not found');
        }
    
        $typeId = $type->getId();
    
        $previousType = $entityManager->createQueryBuilder()
            ->select('t')
            ->from(Type::class, 't')
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
            'slug' => $slug,
            'type' => $type,
            'previousType' => $previousType,
            'nextType' => $nextType,
        ]);
    }
   
    #[Route('add/types', name: 'app_add_to_types')]
    #[IsGranted('ROLE_ADMIN')]
    public function addToType(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $form = $this->createForm(TypeType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $type = $form->getData();
            $entityManagerInterface->persist($type);
            $entityManagerInterface->flush();
            return $this->redirectToRoute('app_types');
        }
        $this->addFlash(
            'success',
            'New type found'
        );
        return $this->render('type/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('edit/types/{slug}', name: 'app_edit_from_types')]
    #[IsGranted('ROLE_ADMIN')]
    public function editFromTypes(Request $request, EntityManagerInterface $entityManagerInterface, string $slug): Response
    {
        $type = $entityManagerInterface->getRepository(Type::class)->findOneBy(['slug' => $slug]);
    
        if (!$type) {
            throw $this->createNotFoundException('type not found');
        }
    
        $form = $this->createForm(TypeType::class, $type);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManagerInterface->flush();
            
            return $this->redirectToRoute('app_types');
        }
        $this->addFlash(
            'success',
            'Type was eddited'
        );
        return $this->render('type/edit.html.twig', [
            'slug' => $slug,
            'type' => $type,
            'form' => $form->createView(),
        ]);
    }
}