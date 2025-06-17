# Reservation UX Description


# Déroulé pour un utilisateur non connecté 

#### Page Principale
---
J'arrive sur le site, je peux voir des annonces de réservation de salle que je peux trier grâce à des filtres 

Je peux consulter une annonce en cliquant ou sur le bouton "réserver"

#### Page de Salle
---
Je peux voir 

l'image en banniére

 voir une description, l'équipement , les logiciels et les options d'érgonomies   

 la capacité 

 et un calendrier grisé avec marqué "connectez-vous pour réserver une salle" 

lorsque l'on clique dessus ça nous améne à la page de connexion 

#### Page de connexion
---
si on as déjà un compte on rentre nos coordonées si elle sont juste on est ramené à la derniére page de retour \*

si on n'as pas de compte on peut cliquer sur le "inscrivez vous" pour s'inscrire

#### Page d'inscription
---
une fois l'inscription réalisée on retourne à la derniére page de retour \*

#### Header
---
 se connecter
 
  s'inscrire

---

\* page de retour = page du site (ex: homepage, reservation)

## Description Utilisateur connecté

#### Page de Salle
---
Je peux placer une reservation sur une annonce qui m'intéresse 

Je veux pouvoir séléctionner une date de début et de fin 

Lors de cette séléction je veux voir en rouge les dates non disponibles 

Lorsque je valide ma préreservation le site m'améne vers une page de validation avec un texte m'affirmant que ma demande va être traiter prochainement 

Sur cette j'ai un bouton qui me raméne vers la page principale 

#### Page Principale
---
Sur la page principale je peux accéder à mes réservations 

#### Page Liste réservation
---

Cette page listant mes réserve me permet de voir l'état de mes demandes (en attentes/ accépté)

de les modifier ou supprimer 

Si je veux modifier une réservation le calendrier s'affiche sous forme de pop et je peux modifier ma date

Si je veux supprimer une réservation un pop up apparait me demander de confirmer mon choix, si je valide la réservation est annulé

Si je clique sur la réservation je suis amener vers la page de la salle où je peux faire une nouvelle réservation 

#### Header
---
mes réservations

se déconnecter


## Description Admin 

#### Page Principale
---

Lorsque je me connecte si je suis admin la page principale est la même qu'un utilisateur lambda

le dashboard est aussi accessible grâce à une icône sur le menu pricipal

A coté de cette icone un plus qui permet de creer une annonce 

#### Page de salle (Si elle nous appartient)
---
Même qu'un utilisateur mais avec les boutons:
* Modifier
* Supprimer (avec confirmation et si il n'y a pas de réservations)
* Rendre Disponible/Bloqué

On peut aussi réserver une salle 

#### Page de salle (Si elle nous appartient pas)
---
Même qu'un utilisateur



#### Dashboard 
---
Une cloche avec les notifications des préreservation < 5 jours


##### Salles

Un onglet qui nous permet de voir une version compact des salles que nous avons, avec les mêmes options de recherche que la page principale


Les boutons modifier | supprimer | Rendre Disponible/Bloqué 
sont directement accessible 


##### Réservations 

Une liste des préréservation et réservation

l'affichage par défault est fait avec cet ordre d'importance :

* les préréservation avec la date de début le plus proche de la date actuelle 
* les préréservation en attentes que on peut accepter ou rejeter
* les réservations accepter (avec confirmation)


##### Equipement/Ergonomie/Logiciels  

Liste des équipements disponible

On peut les modifier/créer/supprimer 

Créer fait apparaitre une boite de texte on valide la création en appuyant entrée ou sur le bouton 

#### Header 
Mes réservations

Dashboard

Se déconnecter
