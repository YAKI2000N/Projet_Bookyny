# Projet_BOOKYNY
**Votre Bibliothéque en ligne**

### 1. Théme de l'Application 

L'application "BOOKYNY" permet à une communauté de personnes de partager en ligne des livres de leur collection personnelle. Les membres de la communauté peuvent gérer leur propre inventaire de livres et rendre une partie de cet inventaire publique sous forme de galeries, ce qui les rend accessibles aux autres membres. Les caractéristiques des livres contenus dans l'inventaire peuvent varier en fonction des préférences du membre, et les membres peuvent créer des fiches détaillées pour chaque livre.

### 2. Modéle de données : 

L'application utilise un modéle de données composé des entités suivantes : 
1. **MyBooks (Inventaire)**
   - Description : Représente l'inventaire d'un membre, qui contient des livres.
   - Attributs Actuels :
     - `id` (identifiant unique)
     - `nameINV` (nom de l'inventaire) 
   - Relations :
     - Plusieurs inventaires peuvent appartenir à un membre (relation ManyToOne).
     - Un inventaire peut contenir plusieurs livres (relation OneToMany).

2. **Books (Objet)**
   - Description : Représente les livre dans l'inventaire. Aussi (ces livres peuvent etre publique)
   - Attributs Actuels:
     - `id` (identifiant unique)
     - `title` (titre du livre)
     - `author` (auteur du livre)
     - `note` ( note du livre)
     - `BookDesc` ( description du livre)
     - `dateDeParution` ( Date de parution du livre)
     - `image` ( image de la couverturedu livre)

   - Relations :
     - Plusieurs livres peuvent appartenir à un seul inventaire (relation ManyToOne).
     - Plusierus livres peuvent appartenir à plusieurs galeries (relation ManyToMany).
3. **Member (membre)**
   - Description : Représente un membre de la communauté et gére des inventaires. 
   - Attributs Actuels:
     - `id` (identifiant unique)
     - `Name` (nom du membre)
     - `Description` (description du membre)
   - Relations :
     - Un membre peut géré plusieurs inventaires de livres (relation OneToMany).
     - Un membre peut gérer plusieurs galeries. (relation OneToMany)
     - Un membre est un user. (relation OneToOne )
4. **GBooky (Galerie)**
   - Description : Représente la gallerie qui contient les livres. En effet les membres peuvent créer leur 
   propre galerie en choisissant son statut soit : Publiée (tous les visiteurs de sites (authentifiés ou non) 
   peuvent la consulter) ou non publiée (personne ne peut la voir).
   - Attributs Actuels:
     - `id` (identifiant unique)
     - `Published` (booleen qui indique si la galerie est publique ou pas).
     - `GDescription` (description du Gallerie)
   - Relations :
     - Des galeries peuvent etre gérées par un membre (relation ManyToOne).
     - Plusieurs livres peuvent appartenir à plusieurs galeries. (relation ManyToMany)
5. **User (User)**
   - Description : Représente l'utilisateur authentifié. 
   - Attributs Actuels:
     - `id` 
     - `email` 
     - `Password`
     - `roles` (peut etre ADMIN ou USER)
   - Relations :
     - Un utilisateur peut etre un membre (relation OneToOne).
   - Remarque utile sur les données d'authentification :
    - `ADMIN` :
       - **email** : yakine@localhost
       - **Password** : yakine
    - `USER` :
        - **email** : sabrine@localhost 
        - **Password** : sabrine 
        - **email** : saif@localhost 
        - **Password** : usersaif
    - `Pour visiter en tant que visiteur normal sans authentifications `  : entrer la route /g/booky/
5. **Remarque utile pour le Test** : 
quand j'ai fait la protection des de l'accés au CRUD sur les données aux seuls propriétaires de ces données : Ca marché que pour l'utilisateur dont le role est "ADMIN", Pour tous les autres utilisateurs (y compris le propriétaire de ces données) l'accés est interdit. 
Donc pour tester les fonctionnalités de show, edit et pour que vous aurez l'accés à tout, faites l'authentification avec le compte ADMIN définit ci-dessus. 
6. **Appercu sur les routes si nécessaire** : 
liste des galleries : /g/booky
liste des livres : /books/list
liste des inventaires : /my/books/list
liste des membres : /member
admin : /admin 
le reste des routes dérivent de celles ci sinon vous pouvez composer la commander "symfony console debug:router"

     
  ### 3. Nomenclature des Entité :

- **Member (Membre)** : Représente les membres de la communauté, chaque membre peut avoir son propre inventaire de livres.
- **MyBooks (Inventaire)** : Représente l'inventaire d'un membre, qui peut contenir plusieurs livres.
- **Books (Objet)** : Représente les livres individuels qui font partie de l'inventaire.
- **GBooky(Galerie)** : Représente la galerie des livres.

 →L'application permet donc aux membres de créer, gérer, et partager leurs collections de livres en ligne, en favorisant l'interaction au sein de la communauté. Chaque livre peut être accompagné d'une fiche détaillée, ce qui permet aux membres de partager des informations spécifiques sur chaque livre de leur collection. Le membre peut ainsi créer une galerie qui inclue ses livres et c'est à lui de décider de la rendre publique 
 ou non. Dans le cas "Published" tous le monde peut la voir (que ce soit un utilisateur authentifié ou pas) sinon elle reste privée. 

### 4. Fonctionnalités actuelles de l'application :
→ L'application marche normalement avec l'affichage des Membres, Livres, Inventaires, Galleries dans la navbar.

→ Vous ne pouvez consulter ni l'inventaire, ni la liste des livres ni la liste des membres que si vous authentifiés. → L'accés aux routes interdites est restreint pour les utilisateurs non authentifiés.

→ Une fois authentifié, vous allez etre redirigé vers la page de la liste des galleries. Si la galerie est publique alors vous pouvez le voir sinon un messsage s'affiche indiquant :"access denied" si l'utilisateur est authentifié sinon il nous redirige à la page de login.

→ Aussi vous pouvez créer une gallerie que si vous etes authentifié ou modifier votre propre gallerie.

→ Consulter Livres :  cette page affiche tous les livres disponibles aussi bien que leurs détails (je l'ai fait publique et je l'ai affiché sur la navbar juste pour s'assurer de la fonction d'affichage et consultation de tous les livres marche

→ Consulter Inventaires : Tableau contenant la liste des inventaires à partir duquel on peut voir les objets contenant dans chaque inventaire et on peut creer un livre dans l'inventaire courant. Aussi une création et édition d'inventaire est possible.[ donc cette page fait la liaison entre Inventaire ->objets]

→ Consulter Members :  liste le nom des membres de la communautés aussi bien que l'ensemble des inventaires qu'ils gérent. La fonction show affiche les inventaires lié à chaque membre  et affiche son contenu( c'est à dire les livres) et permet au membre de créer un autre inventaire. [ donc cette page fait la liaison entre membre -> inventaire->objets]

→ Message Flash : des messages flash apparaissent dés l'ajout d'un inventaire et d'un objet. 

→ Toutes les EntityCrudController sont faites, donc toutes les entités apparaissent dans le dashboard avec les dataFixtures.






