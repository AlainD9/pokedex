<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PokedexController extends AbstractController
{

    #[Route('/pokedex', name: 'app_pokedex')]
    public function showAllPokemon(PokemonRepository $pokemonRepository): Response
    {
        return $this->render('pokedex/index.html.twig', [
            'pokemon' => $pokemonRepository->findAll(),

        ]);
    }
        
    #[Route('/pokedex/{id<\d+>}', name: 'app_pokedex_pokemon')]
    public function show(int $id, PokemonRepository $pokemonRepository, Pokemon $pokemon, EntityManagerInterface $entityManager):response
    {
        $currentPokemonId = $pokemon->getId();

        // Fetch the previous pokemon (if exists)
        $previousPokemon = $entityManager->getRepository(pokemon::class)
            ->createQueryBuilder('p')
            ->where('p.id < :currentPokemonId')
            ->orderBy('p.id', 'DESC')
            ->setParameter('currentPokemonId', $currentPokemonId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        // Fetch the next pokemon (if exists)
        $nextPokemon = $entityManager->getRepository(pokemon::class)
            ->createQueryBuilder('p')
            ->where('p.id > :currentPokemonId')
            ->orderBy('p.id', 'ASC')
            ->setParameter('currentPokemonId', $currentPokemonId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        $pokemon = $pokemonRepository->find($id);
        if (!$pokemon){throw $this->createNotFoundException();}
        return $this->render('pokedex/pokemon.html.twig', [
            'pokemon' => $pokemon,

        ]);
    }
}

