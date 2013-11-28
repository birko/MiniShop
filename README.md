MiniShop
========

E-shop system based on Symfony2 framework


Installation:
--
1. download codes (via github ;))
2. open preferred shell and locate the source code directory.
3.  you will need to copy these files in app/config directory
    - security.yml.dist -> security.yml 
    - app.yml.dist -> app.yml
4. run $ composer install (from http://www.getcomposer.org)
5. run $ php app/console doctrine:schema:udpate --force
6. run $ php app/console cache:clear --env=prod
7. run $ php app/console assets:install --env=prod
8. run $ php app/console assetic:dump --env=prod

Update:
--
1. get the newest version (via github;))
2. run $ php app/console doctrine:schema:udpate --force
3. run $ php app/console cache:clear --env=prod
4. run $ php app/console assets:install --env=prod
5. run $ php app/console assetic:dump --env=prod
