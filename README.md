# ecoride

## Mise en place de l'environnement local.

Cet environnement ne prend pas en charge la mise en place du chiffrement TLS. Seul l'environnement de production dispose de cette fonctionalité.

Le parmétrage d'envoi de mail n'est pas présent. Pour activer les envois de mail, il faut renseigner les paramètres du serveur smtp, comme décrit dans la deuxième étape, ci-dessous.

Il nécessite également un serveur ou poste de travail ayant docker installé.

- cloner le repo github

```
git clone https://github.com/etupdt/ecoride.git
```

- Sur la branche main, vous pouvez éditer le fichier **.env** à la racine du répertoire. Facultativement, vous pouvez y modifier les password des bases de données qui sont initialisés par défaut à la valeur "password". Vous pouvez également renseigner les paramètres du smtp :
```
MAIL_HOST=<adresse du smtp (ex : smtp.orange.fr)>
MAIL_PORT=<port du serveur smtp>
MAIL_USER=<identifiant auprès du serveur smtp>
MAIL_PASSWORD=<password auprès du serveur smtp>
```

- Lancer, à la racine du répertoire **ecoride**, la commande :

```
docker compose -f compose_dev.yaml --env-file .env up -d
```

- Dans un gestionnaire de base de données, executer le script sql d'initialisation :

```
doc/postgres/init_postgresql.sql
```

- Lancer, à la racine du répertoire **ecoride**, les commandes :

```
composer install
npm update
php bin/console sass:build
symfony server:start
```

L'environnement est près. Le serveur écoute sur le port 443. 
L'identifiant de l'administrateur est "admin@test.com" et son mot de passe par défaut est "password"
L'environnement local comprend également un jeu de données de test et des images copiées au moment de l'installation. Un employé y est également créé "employee@test.com". Tout deux ayant le même mot de passe que l'adminitrateur.
