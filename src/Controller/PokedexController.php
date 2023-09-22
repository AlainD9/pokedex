<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Form\PokemonType;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PokedexController extends AbstractController
{
    #[Route('/', name:'app_index')]
    public function index(PokemonRepository $pokemonRepository): Response
    {
        return $this->render('pokedex/index.html.twig', [
            'pokemon' => $pokemonRepository->findAll(),

        ]);
    }

    #[Route('/pokedex', name: 'app_pokedex')]
    public function showAllPokemon(PokemonRepository $pokemonRepository): Response
    {
        return $this->render('pokedex/pokedex.html.twig', [
            'pokemon' => $pokemonRepository->findAll(),

        ]);
    }

    #[Route('pokemon/{slug}', name: 'app_pokemon')]
    public function show(string $slug, EntityManagerInterface $entityManager, PokemonRepository $pokemonRepository): Response
    {
        $pokemon = $pokemonRepository->findOneBy(['slug' => $slug]);
    
        if (!$pokemon)
        {
            throw $this->createNotFoundException('Pokemon not found');
        }
    
        $pokemonId = $pokemon->getId();
    
        $previousPokemon = $entityManager->createQueryBuilder()
            ->select('p')
            ->from(Pokemon::class, 'p')
            ->where('p.id < :currentPokemonId')
            ->orderBy('p.id', 'DESC')
            ->setParameter('currentPokemonId', $pokemonId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    
        $nextPokemon = $entityManager->createQueryBuilder()
            ->select('p')
            ->from(Pokemon::class, 'p')
            ->where('p.id > :currentPokemonId')
            ->orderBy('p.id', 'ASC')
            ->setParameter('currentPokemonId', $pokemonId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    
        return $this->render('pokedex/pokemon.html.twig', [
            'slug' => $slug,
            'pokemon' => $pokemon,
            'previousPokemon' => $previousPokemon,
            'nextPokemon' => $nextPokemon,
        ]);
    }
    
    #[Route('add/pokedex', name: 'app_add_to_pokedex')]
    #[IsGranted('ROLE_ADMIN')]
    public function addToPokedex(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $form = $this->createForm(PokemonType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $pokemon = $form->getData();
            $entityManagerInterface->persist($pokemon);
            $entityManagerInterface->flush();
            return $this->redirectToRoute('app_pokedex');
        }
        $this->addFlash(
            'success',
            'New pokemon found'
        );
        return $this->render('pokedex/add.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('edit/pokedex/{slug}', name: 'app_edit_from_pokedex')]
    #[IsGranted('ROLE_ADMIN')]

    public function editFrompokedex(Request $request, EntityManagerInterface $entityManagerInterface, string $slug): Response
    {
        $pokemon = $entityManagerInterface->getRepository(Pokemon::class)->findOneBy(['name' => $slug]);
    
        if (!$pokemon) {
            throw $this->createNotFoundException('pokemon not found');
        }
    
        $form = $this->createForm(PokemonType::class, $pokemon);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManagerInterface->flush();
            
            return $this->redirectToRoute('app_pokedex');
        }
        $this->addFlash(
            'success',
            'Pokemon was eddited'
        );
        return $this->render('pokedex/edit.html.twig', [
            'slug' => $slug,
            'pokemon' => $pokemon,
            'form' => $form->createView(),
        ]);
    }

}

