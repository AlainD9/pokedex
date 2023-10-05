<?php

namespace App\Controller\Admin;

use App\Entity\Pokemon;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;

class PokemonCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Pokemon::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield Field::new('number');
        yield Field::new('name');
        yield Field::new('image')
            ->setFormTypeOption('attr', [ 'placeholder' => 'https://projectpokemon.org/images/normal-sprite/pokemon-name' ])
            ->setCustomOption('default_value','https://projectpokemon.org/images/normal-sprite/');
        yield AssociationField::new('region');
        yield AssociationField::new('type');
    }
}