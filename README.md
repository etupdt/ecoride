# ecoride

## Mise en place de l'environnement local.

Cet environnement ne prend pas en charge la mise en place du chiffrement TLS. Seul l'environnement de production dispose de cette fonctionalité.

Le parmétrage d'envoi de mail n'est pas présent. Pour activer les envois de mail, il faut renseigner les paramètres du serveur smtp, comme décrit dans la deuxième étape, ci-dessous.

Il nécessite également un serveur ou poste de travail ayant docker installé.

- cloner le repo github

```
git clone https://github.com/etupdt/ecoride.git
```

- Sur la branche main, vous pouvez éditer le fichier **.env** à la racine du répertoire. Facultativement, vous pouvez y modifier les password des bases de données qui sont initialisés par défaut à la valeur "password" (variables DB_PASSWORD pour la base PostgreSql et MONGO_PASSWORD pour la base MongoDb. Vous pouvez également renseigner les paramètres du smtp :
```
MAIL_HOST=<adresse du smtp (ex : smtp.orange.fr)>
MAIL_PORT=<port du serveur smtp>
MAIL_USER=<identifiant auprès du serveur smtp>
MAIL_PASSWORD=<password auprès du serveur smtp>
```

- Lancer, à la racine du répertoire **ecoride**, les commandes :

```
docker compose -f docker-compose-back-demo.yml --env-file env.demo.properties up -d
```

- allez dans le répertoire du projet

```
cd ecoride
```

L'environnement est près. Le serveur écoute sur le port 443. 
L'identifiant de l'administrateur est "admin@test.com" et son mot de passe par défaut est "password"
L'environnement local comprend également un jeu de données de test et des images copiées au moment de l'installation. Un employé y est également créé "employee@test.com" et un utilisateur "user@test.com". Tout deux ayant le même mot de passe que l'adminitrateur.
