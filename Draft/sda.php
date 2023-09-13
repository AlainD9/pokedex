<!-- class PokemonController extends AbstractController
{
    #[Route('/pokemon/{pokemonController<[a-z]+>}', name: 'app_pokemon')]
    public function index(
        string $pokemonController = 'MissingNo.'
    ): Response
    {
        return $this->render('pokemon/index.html.twig', [
            'pokemonName' => $pokemonController,
        ]);
    } 
} -->