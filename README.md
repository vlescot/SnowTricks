
# SnowTricks
  
**[Click here to visit](http://snowtricks.vincentlescot.fr)**  
  
This project was built during my [web develpment learning path](https://openclassrooms.com/paths/developpeur-se-d-application-php-symfony) with OpenClassrooms.  
  
SnowTricks is a sharing website about snowboard tricks.   
This site is built with *Symfony ~4.0* and *[ADR (Action-Domain-Responder)](https://youtu.be/y7c-XWLYMVA) architectural pattern.*  
  
### Built with
##### Back-end
* Symfony 4 (Flex)
* Doctrine 
* PHPUnit and BrowerKit, CssSelector, Panther

##### Front-end
* Twig
* Jquery & Jquery UI
* Bootstrap

### Friendly with :  
 1. PSR-0, PSR-1, PSR-2, PSR-4  
 2. Symfony best practices  
 3. Doctrine best practices  
  
## Install
 1. Clone or download the repository into your environment.  
    ```https://github.com/vlescot/SnowTricks.git  ```
 2. Change the file *.env.dist* with your own data :  
    ```.env 
    APP_SECRET= random string with around 32 caracters
    DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
    # You could keep the line below
    DATABASE_URL_TEST=sqlite:///%kernel.project_dir%/var/data.db
    MAILER_URL=gmail://username:password@localhost?encryption=tls&auth_mode=oauth
    APP_ENV=dev
    ```
 3. Open your terminal and run the following commands :
    
    ```$ php bin/console doctrine:database:create```
    
    ```$ php bin/console doctrine:migrations:diff```
    
    Add the file code *TRIGGER_delete_unlinked_groups.sql* in the migration's file.
     
    ```$ php bin/console doctrine:migrations:migrate```
    
    ```$ php bin/console doctrine:fixtures:load```
    
    ```$ php bin/console server:run```
 4. To run tests, execute the commands :
    
    ```$ php bin/console doctrine:database:create --env=test ```
    
    ```$ php bin/console doctrine:schema:update --env=test --force```
    
    ```$ php bin/console doctrine:fixtures:load --env=test```
    
    ```$ php bin/phpunit  ```
     
     If you encounter any database not found issue during tests, you would probably have to help the very new [Panther](https://github.com/symfony/panther) component by editing the file */config/packages/doctrine.yaml* with :
    ```yaml
    parameters:
        env(DATABASE_URL): 'mysql://db_user:db_password@127.0.0.1:3306/db_name'```   
&nbsp;

That's all ! :smile:
