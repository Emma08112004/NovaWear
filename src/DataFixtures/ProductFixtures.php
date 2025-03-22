<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $productsData = [
            // Femme
            ['Veste Blazer cintrée', 'Veste élégante pour femme.', 265.00, 'VesteBlazercintrée.png', 10, 'femme'],
            ['Blouson effet mouton', 'Blouson chaud et confortable.', 225.00, 'Blousoneffetmouton.png', 8, 'femme'],
            ['Veste courte bouclée', 'Veste tendance et chic.', 175.00, 'Vestecourtebouclee.png', 12, 'femme'],
            ['Blouson de motard court', 'Blouson style motard.', 239.00, 'Blousondemotardcourt.png', 5, 'femme'],
            ['Jean Taille Haute', 'Jean ajusté pour un look moderne.', 125.00, 'JeanTailleHaute.png', 15, 'femme'],
            ['Jean Taille Croisée', 'Jean coupe originale et stylée.', 150.00, 'JeanTailleCroisée.png', 9, 'femme'],
            ['Jean Coupe Droite', 'Jean classique coupe droite.', 135.00, 'JeanCoupeDroite.png', 11, 'femme'],
            ['Jean Baggy', 'Jean large pour un style streetwear.', 95.00, 'JeanBaggy.png', 7, 'femme'],

            // Homme
            ['Sweat à col zippé', 'Sweat confortable avec col zippé.', 155.00, 'SweatColZippe.png', 13, 'homme'],
            ['Gilet en maille zippé', 'Gilet en maille doux et chaud.', 99.00, 'GiletMailleZippe.png', 10, 'homme'],
            ['Veste contrastée', 'Veste élégante avec détails contrastés.', 125.00, 'VesteContrastee.png', 8, 'homme'],
            ['Blouson effet mouton', 'Blouson chaud et tendance.', 115.00, 'BlousonEffetMoutonn.png', 6, 'homme'],
            ['Pantalon de costume', 'Pantalon chic et élégant.', 75.00, 'PantalonCostume.png', 9, 'homme'],
            ['Jean coupe étroite', 'Jean slim ajusté.', 65.00, 'JeanCoupeEtr.png', 6, 'homme'],
            ['Jean slim fit', 'Jean moderne coupe slim.', 90.00, 'JeanSlimFit.png', 14, 'homme'],
            ['Pantalon cargo', 'Pantalon décontracté et stylé.', 55.00, 'PantalonCargo.png', 12, 'homme'],
        ];

        foreach ($productsData as [$nom, $description, $prix, $image, $stock, $categorie]) {
            $product = new Product();
            $product->setNomProduct($nom)
                ->setDescriptionProduct($description)
                ->setPrixProduct($prix)
                ->setImageUrl($image)
                ->setStockProduct($stock)
                ->setCategorie($categorie);
            $manager->persist($product);
        }

        $manager->flush();
    }
}