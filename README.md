MiniShop
========

E-shop system based on Symfony2 framework


Installation:
--
1. download codes (via github ;))
2. open preferred shell and locate the source code directory.
3. run $ composer install (from http://www.getcomposer.org)
   Configuration is loaded via postInstall script in composer
4. run $ php app/console doctrine:schema:udpate --force
5. run $ php app/console cache:clear --env=prod
6. run $ php app/console assets:install --env=prod
7. run $ php app/console assetic:dump --env=prod

Update:
--
1. get the newest version (via github;))
2. run $ php app/console doctrine:schema:udpate --force
3. run $ php app/console cache:clear --env=prod
4. run $ php app/console assets:install --env=prod
5. run $ php app/console assetic:dump --env=prod

##Features:
### Sendy.co
Add to composer.json
```
	"require": {
	...
		"jacobbennett/sendyphp": "dev-master"
	}
```

Add sendy configuration to app.yml file according sendy server
```yml
core_newsletter:
    sendy:
        installation_url: #url
        list_id: #list_id
        api_key: #api_Key
```
### PHPOffice
Support for Excel export
Add to composer.json
export definiton type must be set to "Excel"
```
	"require": {
	...
		"phpoffice/phpexcel": "dev-master"
	}
```
