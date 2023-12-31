# Dragon Treasure

- Api plaform v3
- symfony 6.3.10
- docker (postgreSQL)
- PHP 8.1

## helper

Debug la configuration actuelle
```
php bin/console debug:config api_platform
```

Montre toute la configuration possible
```
php bin/console config:dump api_platform
```

## Documentation

Désactiver la documentation  ```config/packages/api_platform.yaml```


Lorsque ```enable_docs: false```, cela supprime la page d'accueil de l'API et la documentation

- l'url ```/api/docs.json``` ou ```/api/docs.jsonld```retourne une 404 (documentation)
- l'url ```/api``` retourne une 500 (Page d'acceuil de l'api), car l'url vers la documention n'existe plus (```/api/docs.json```),
```yaml
api_platform:
    enable_docs: false
```

Désactiver le entry_point (url: ```/api```)

Désactive seulement la page d'accueil de l'API pas la documentation, retourne une 404
```yaml
api_platform:
    entry_point: false
```

Désactiver la documentation swagger
```yaml
api_platform:
    enable_swagger: false
    enable_swagger_ui: false
```
