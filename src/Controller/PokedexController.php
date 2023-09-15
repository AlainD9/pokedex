<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Form\Pokemonpokemon;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('pokemon/{pokemonName<[a-zA-Z_\s-]+>}', name: 'app_pokemon')]
    public function show(string $pokemonName, EntityManagerInterface $entityManager, PokemonRepository $pokemonRepository): Response
    {
        $pokemon = $pokemonRepository->findOneBy(['name' => $pokemonName]);
    
        if (!$pokemon) {
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
            'pokemonName' => $pokemonName,
            'pokemon' => $pokemon,
            'previousPokemon' => $previousPokemon,
            'nextPokemon' => $nextPokemon,
        ]);
    }
    

    #[Route('pokedex/add', name: 'app_add_to_pokedex')]
    public function addToPokedex(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $form = $this->createForm(Pokemonpokemon::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $pokemon = $form->getData();
            $entityManagerInterface->persist($pokemon);
            $entityManagerInterface->flush();
            return $this->redirectToRoute('app_index');
        }
        return $this->render('pokedex/add.html.twig', [
            'form' => $form,
        ]);
    }

}

