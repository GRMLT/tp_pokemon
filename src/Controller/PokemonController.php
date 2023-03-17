<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pokemon', name: 'pokemon_')]
class PokemonController extends AbstractController
{
    #[Route('/listAll', name: 'listAll')]
    public function listAll(PokemonRepository $pokemonRepository):Response{
        $pokemons = $pokemonRepository->findAll();
        return $this->render('pokemon/list.html.twig', [
            'tabPokemon' => $pokemons
        ]);
    }

    #[Route('/listCaught', name: 'listByCaught')]
    public function listByCaught(PokemonRepository $pokemonRepository): Response
    {
        $pokemons = $pokemonRepository->findPokemonCaught();
        return $this->render('pokemon/list.html.twig', [
            'tabPokemon' => $pokemons
        ]);
    }

    #[Route('/listName', name: 'listByName')]
    public function listByName(PokemonRepository $pokemonRepository): Response
    {
        $pokemons = $pokemonRepository->sortPokemonByName();
        return $this->render('pokemon/list.html.twig', [
            'tabPokemon' => $pokemons
        ]);
    }

    #[Route('/details/{id}', name: 'details')]
    public function details(int $id, PokemonRepository $pokemonRepository) : Response
    {
        $details = $pokemonRepository->find($id);
        return $this->render('pokemon/details.html.twig', [
            'pokemon' => $details
        ]);
    }
    #[Route('/catch/{id}', name: 'catch')]
    public function catch(EntityManagerInterface $em, int $id) : Response
    {
        $pokemon = $em->getRepository(Pokemon::class)->find($id);
        $pokemon->isIsCaught() ? $pokemon->setIsCaught(false) : $pokemon->setIsCaught(true);
        $em->flush();
        $pokemons = $em->getRepository(Pokemon::class)->findAll();
        return $this->render('pokemon/list.html.twig', [
            'tabPokemon' => $pokemons
        ]);
    }
}