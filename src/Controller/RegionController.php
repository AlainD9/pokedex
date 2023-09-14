<?php

namespace App\Controller;

use App\Entity\Region;
use App\Repository\RegionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    #[Route('/regions/{regionName<[a-zA-Z]+>}', name: 'app_region')]
    public function show(string $regionName, EntityManagerInterface $entityManager, RegionRepository $regionRepository): Response
    {
        $region = $regionRepository->findOneBy(['name' => $regionName]);
    
        if (!$region) {
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
    
    
}
