# NovaWear

**NovaWear** est un site de vêtements en ligne. Il permet aux utilisateurs de parcourir une boutique, ajouter des produits au panier ou aux favoris, effectuer un paiement sécurisé via Stripe, consulter l’historique de leurs commandes, et rechercher des articles.

## Fonctionnalités principales

1. **Ajout au panier avec gestion des tailles** : chaque article peut être ajouté avec une taille spécifique.
2. **Système de favoris** : les utilisateurs peuvent ajouter ou retirer des articles favoris.
3. **Paiement sécurisé via Stripe** : intégration complète avec Stripe pour le paiement.
4. **Page “Mes commandes”** : historique regroupé par commande avec détails.
5. **Récapitulatif post-commande(summary)** : affichage clair après paiement réussi.
6. **Système de recherche** : une barre permet de filtrer les articles disponibles.

---

## Technologies utilisées

| Technologie         | Rôle dans le projet                                            |
|---------------------|----------------------------------------------------------------|
| **Symfony (PHP)**   | Framework principal pour la structure du projet               |
| **Twig**            | Moteur de template intégré pour les vues                      |
| **Doctrine ORM**    | Gestion de la base de données via les entités                 |
| **MySQL**           | Base de données relationnelle                                 |
| **Bootstrap**       | Mise en page responsive des vues                              |
| **CSS**             | Personnalisation du design                                    |
| **Stripe**          | Paiement en ligne sécurisé                                    |
| **Docker & Compose**| Conteneurisation des services (PHP, MySQL, Adminer, etc.)     |
| **Adminer**         | Interface web pour gérer la base de données                   |

---

## Méthodologie de travail

Le projet a été mené en méthode agile avec un fonctionnement Scrum hebdomadaire :

- **Lundi** : Sprint planning (définition des tâches)
- **Mercredi** : Suivi des tâches sur Notion ("En cours")
- **Vendredi** : Push GitHub, tests, validations, Sprint Review

### Outils utilisés :

- **Discord / WhatsApp** : communication instantanée
- **Notion** : gestion des tâches et du backlog
- **GitHub** : versionnage du code

---

## Schéma de la base de données

> Le diagramme de classe de la base de données :
> Il présente les relations entre les entités `User`, `Product`, `Order`, `Summary`, `Basket`, `Favorites`, `Payment`.



---

## Instructions d’installation et de lancement

### Étapes à suivre

1. **Cloner le dépôt GitHub :**
```bash
git clone https://github.com/Emma08112004/NovaWear.git
cd NovaWear


2. **Lancer les conteneurs Docker**

```bash
docker compose up -d

3. **Accéder à l'application
```bash
http://novawear.local
