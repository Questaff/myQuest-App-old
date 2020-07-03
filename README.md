# myQuest

Dans ce repo vous trouverez le front de l'application de myQuest, celui-ci est développé en ionic et angular. Vous trouverez ici trois dossiers. Le dossier my-quest contient le front de l'application, les dossiers back_test et bdd_test contiennent des fichiers php et sql qui m'ont permit de tester les fonctionnalitées du back. Le back officiel de l'application se trouve dans le repo : myQuest-backend situé sur la page suivante : https://github.com/Questaff/myQuest-backend

## Prérequis d'installation

### Le front

Pour pouvoir lancer le front il vous faut d'avoir installé sur votre environnement de travail 'npm'. Une fois celui-ci installé par vos soins il faut se rendre dans le dossier my-quest puis lancer la commande suivante 'npm install', une fois le node_modules installé la commande 'ionic serve' permettra de lancer le front de l'application. Il faudra par la suite se rendre dans le dossier service (src/app/services) puis de modifier les fichiers .ts en y ajoutant les liens du back que vous utilisez. 

### Si vous voulez utiliser les dossiers test du repo

Il vous faudra d'avoir installé PHP et MySQL. il faudra donc par la suite executer la commande SQL pour installer la base de données vierge. Ensuite pour le php il faudra mettre les fichiers PHP dans un dossier puis changer les informations du fichier header.php en y inscrivant les informations de la bdd que vous utilisez, les informations inscrites par défaut sont celle de base. La dernière étape pour lancer le back de test est de se rendre avec un invité de commande là où se situe les fichiers PHP et d'executer la commande 'php -S 127.0.0.1:80' si vous travaillez en local.
