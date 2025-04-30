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

![WhatsApp Image 2025-04-21 à 14 21 39_b4d61f93](https://github.com/user-attachments/assets/51dbdb57-1291-41b7-bc44-d462d54a67b7)


[DiagClasse.pdf](https://github.com/user-attachments/files/19833924/DiagClasse.pdf)

---

##  Concernant notre code

> - Comme nous avons pu vous l'expliquer en cours, nous avons rencontré de nombreux problèmes avec nos machines. Par conséquent, le dernier commit à prendre en compte ne se trouve pas dans la branche Main mais dans la branche dev
>   
> - Par ailleurs, comme nous avon pu vous le préciser en classe, en raison de ces problèmes techniques, tous les membres du groupe n'ont pas pu pousser leur code individuellement. Nous avons tous travaillé via Live Share, une extension qui nous permettait de coder en simultané à partir d’un seul et même ordinateur, le seul qui fonctionnait correctement.

---

## Instructions d’installation et de lancement

### Étapes à suivre

1. **Cloner le dépôt GitHub :**
```bash
git clone https://github.com/Emma08112004/NovaWear.git
cd NovaWear
```

2. **Lancer les conteneurs Docker**

```bash
docker compose up -d
```
3. **Accéder à l'application
```bash
http://novawear.local


