# Runmap

## Présentation de projet
RunMap est un site de référencement de stade où pratiquer de la course à pied.
Le porteur de projet est un coureur qui actuellement paye une license pour pouvoir courir dans le stade de sa ville. La license coûte 190 euros alors qu'il y ne court qu’une fois par semaine. Le site veut répondre à ce type de problématique : 

où puis-je courir avec ou sans license ? 

RunMap est donc un site de référencement mais également un site de passionnés de sport qui pourront se retrouver autour d’une piste de course.

Le site est reponsive et donc accessible sur pc et smartphone.

## Public visé
Le site s’adresse aux sportifs de tous niveaux désirant chercher facilement un stade pour courir et qui correspond à leurs besoins. 

## Arborescence et fonctionnalités
Le site s’articule autour d’une carte de type GoogleMap mentionnant les lieux référencés.

Page d’accueil: permet d’accéder au site internet, de connaitre les derniers stades créés et accéder aux autres pages.

Page d’inscription: permet de créer un compte utilisateur.

Page de connexion: permet de s’identifier lorsque l’utilisateur a déja un compte.

Page de recherche: permet de chercher une ville sur la carte.

Page d’ajout d’un lieu: permet d’ajouter un lieu.

Page d’un lieu: permet de consulter la fiche d’un lieu et ajouter un commentaire.

Page admin: permet de supprimer un compte utilisateur.

## Rôle des utilisateurs
Trois types d’utilisateurs on étés définis : le visiteur non connecté, l’utilisateur connecté, l’administrateur connecté. À chaque rôle, des droits ont été attribués afin de réserver certaines actions à certains profils.

## Visiteur non connecté
Le visiteur non connecté peut s’inscire, effectuer une recherche sur la carte, consulter la fiche d’un lieu.
Utilisateur connecté
Le visiteur une fois connecté peut ajouter ou modifier un lieu, ajouter un avis sur la fiche d’un lieu. 
Administrateur connecté 
L’administrateur à tous les droits de l’utilisateur. Il peut en plus, supprimer un lieu, un commentaire, un utilisateur.

## Technologies utilisées
### Front-End
Le front est réalisé grâce au moteur de template Twig de Symfony. 
Pour l’interface utilisateur, nous utilisons Bootstrap et Jquery pour la partie dynamique et responsive.
On utilise FontAwesome pour afficher des icones. 
L’integration de la map se fait avec l’api Mapbox.
Le positionnement des marqueurs sur la map ainsi que la gestion de la barre de recherche se fait à l’aide de l’api geocoder de Mapbox.
Pour s’assurer d’avoir ajouté les bonnes adresses ont passe par l’api du gouvernement.
### Back-End
* framework PHP Symfony ;
* interface avec la base de données : ORM Doctrine de Symfony ;
* Base de données : MySql ;
* Gestion de la base de données : PhpMyAdmin.
