# GTI619 - Guide d'installation

## Pré-requis : 
- WSL sous Windows
- PHP 8.0.25 (<code>sudo apt install php8</code>) <b>UNIQUEMENT</b>
- php8.0-xml, php8.0-mysql (php8.0-curl pour télécharger plus vite les dépendances)
- docker desktop et composer

## Démarche à suivre :
- `composer install`
- `./vendor/bin/sail up -d`

Normalement vous devriez voir le téléchargement des images docker nécessaire puis la création des containers
sur docker desktop.

- Ouvrir le fichier .env à la racine du projet et modifier temporairement DB_HOST en lui donnant la valeur `127.0.0.1`.
- `php artisan migrate:fresh --seed`
- Rétablir l'ancienne valeur de DB_HOST (`mysql`)

Lorsque les containers sont lancés et que la base de donnée est peuplée,  le site est déjà accessible via https://localhost .

Pour accéder à Mailhog et Mysql, il vous suffit de regarder les informations sur votre docker desktop.

## Erreurs
- Le package php8.0 n'est pas trouvé (remplacer 8.1 par 8.0): https://www.cloudbooklet.com/how-to-install-or-upgrade-php-8-1-on-ubuntu-20-04/
- `composer install` - Vous n'avez pas la bonne version de php ou bien vous n'avez pas installé le module xml et mysql.
- Erreur concernant un l'accès à un fichier dans le répertoire /storage - `chmod -R 777 /storage`
