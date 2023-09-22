<?php

namespace App\Controller;

use App\Entity\Region;
use App\Form\RegionType;
use App\Repository\RegionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RegionController extends AbstractController
{
    #[Route('/regions', name: 'app_regions')]
    public function showAll(RegionRepository $regionRepository): Response
    {
        return $this->render('region/regions.html.twig', [
            'regions' => $regionRepository->findAll() ,
        ]);
    }

    #[Route('region/{slug}', name: 'app_region')]
    public function show(string $slug, EntityManagerInterface $entityManager, RegionRepository $regionRepository): Response
    {
        $region = $regionRepository->findOneBy(['slug' => $slug]);
    
        if (!$region)
        {
            throw $this->createNotFoundException('Region not found');
        }
    
        $regionId = $region->getId();
    
        $previousRegion = $entityManager->createQueryBuilder()
            ->select('r')
            ->from(Region::class, 'r')
            ->where('r.id < :currentRegionId')
            ->orderBy('r.id', 'DESC')
            ->setParameter('currentRegionId', $regionId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    
        $nextRegion = $entityManager->createQueryBuilder()
            ->select('r')
            ->from(Region::class, 'r')
            ->where('r.id > :currentRegionId')
            ->orderBy('r.id', 'ASC')
            ->setParameter('currentRegionId', $regionId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    
        return $this->render('region/region.html.twig', [
            'slug' => $slug,
            'region' => $region,
            'previousRegion' => $previousRegion,
            'nextRegion' => $nextRegion,
        ]);
    }
    
    #[Route('add/regions', name: 'app_add_to_regions')]
    #[IsGranted('ROLE_ADMIN')]

    public function addToRegion(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $form = $this->createForm(RegionType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $region = $form->getData();
            $entityManagerInterface->persist($region);
            $entityManagerInterface->flush();
            return $this->redirectToRoute('app_regions');
        }
        $this->addFlash(
            'success',
            'New Region found'
        );
        return $this->render('region/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('edit/regions/{slug}', name: 'app_edit_from_regions')]
    #[IsGranted('ROLE_ADMIN')]

    public function editFromRegions(Request $request, EntityManagerInterface $entityManagerInterface, string $slug): Response
    {
        $region = $entityManagerInterface->getRepository(Region::class)->findOneBy(['slug' => $slug]);
    
        if (!$region) {
            throw $this->createNotFoundException('Region not found');
        }
    
        $form = $this->createForm(RegionType::class, $region);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManagerInterface->flush();
            
            return $this->redirectToRoute('app_regions');
        }
        $this->addFlash(
            'success',
            'Region was eddited'
        );
        return $this->render('region/edit.html.twig', [
            'slug' => $slug,
            'region' => $region,
            'form' => $form->createView(),
        ]);
    }

}
