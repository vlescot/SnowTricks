
# SnowTricks

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/f1ed27777ecf4d239046bb3ec5f26514)](https://app.codacy.com/app/vlescot/SnowTricks?utm_source=github.com&utm_medium=referral&utm_content=vlescot/SnowTricks&utm_campaign=Badge_Grade_Settings)

  
**[Have a look](http://snowtricks.vincentlescot.fr)**  
  
SnowTricks is a sharing website about snowboard tricks. This project was built during my [web develpment learning path](https://openclassrooms.com/paths/developpeur-se-d-application-php-symfony) with OpenClassrooms. 

This application is built with *Symfony ~4.0* and *[ADR (Action-Domain-Responder)](https://youtu.be/y7c-XWLYMVA) architectural pattern.*  
  
#### Friendly with :  
   1. PSR-0, PSR-1, PSR-2, PSR-4  
   2. Symfony Best Practices  
   3. Doctrine Best Practices
   
## Built with
##### Back-end
* Symfony 4 (Flex)
* Doctrine 
* PHPUnit and BrowerKit, CssSelector, Panther

##### Front-end
* Twig
* Jquery & Jquery UI
* Bootstrap
  
  
# Install
 1. Clone or download the repository into your environment.  
    ```https://github.com/vlescot/SnowTricks.git  ```
 2. Change the file *.env.dist* with your own data :  
 3. Install the database and inject the fixtures.
 4. Add the file code *TRIGGER_delete_unlinked_groups.sql* in your database, via the migration's file for exemple.
     
 NOTE : If you encounter any database not found issue during tests, you would probably have to help the very new [Panther](https://github.com/symfony/panther) component by editing the file */config/packages/doctrine.yaml* with :
 ```yaml
parameters:
    env(DATABASE_URL): 'mysql://db_user:db_password@127.0.0.1:3306/db_name'

```
&nbsp;

That's all ! :smile:
