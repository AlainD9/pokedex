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

class RegionController extends AbstractController
{
    #[Route('/regions', name: 'app_regions')]
    public function showAll(RegionRepository $regionRepository): Response
    {
        return $this->render('region/regions.html.twig', [
            'regions' => $regionRepository->findAll() ,
        ]);
    }

    #[Route('region/{regionName<[a-zA-Z_\s-]+>}', name: 'app_region')]
    public function show(string $regionName, EntityManagerInterface $entityManager, RegionRepository $regionRepository): Response
    {
        $region = $regionRepository->findOneBy(['name' => $regionName]);
    
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
            'regionName' => $regionName,
            'previousRegion' => $previousRegion,
            'nextRegion' => $nextRegion,
        ]);
    }
    
    #[Route('regions/add', name: 'app_add_to_regions')]
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
        return $this->render('region/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('regions/edit/{regionName<[a-zA-Z_\s-]+>}', name: 'app_edit_from_regions')]
    public function editFromRegions(Request $request, EntityManagerInterface $entityManagerInterface, string $regionName): Response
    {
        $region = $entityManagerInterface->getRepository(Region::class)->findOneBy(['name' => $regionName]);
    
        if (!$region) {
            throw $this->createNotFoundException('Region not found');
        }
    
        $form = $this->createForm(RegionType::class, $region);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManagerInterface->flush();
            
            return $this->redirectToRoute('app_regions');
        }
    
        return $this->render('region/edit.html.twig', [
            'regionName' => $regionName,
            'region' => $region,
            'form' => $form->createView(),
        ]);
    }

}
