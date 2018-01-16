
DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      models/             contains model classes
      runtime/            contains files generated during runtime
      vendor/             contains dependent 3rd-party packages



REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 5.4.0.


INSTALLATION
------------
 git clone https://github.com/holaspyker/test.git

CONFIGURATION
-------------

### Database


run the sql.<br>

source config/database.sql.<br>


Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

**NOTES:**
- Yii won't create the database for you, this has to be done manually before you can access it.
- Check and edit the other files in the `config/` directory to customize your application as required.
- Refer to the README in the `tests` directory for information specific to basic application tests.




USE CASE
--------

User 

create --> POST  server_name/test/index.php/user <br>
update --> PUT   server_name/test/web/index.php/user/:id <br>
view   --> GET   server_name/test/web/index.php/user/:id <br>
index  --> GET   server_name/test/web/index.php/user <br>


Transaction 
report   --> GET server_name/test/index.php/transaction?days:number <br>
create   -->POST server_name/test/index.php/transaction <br>
view     --> GET server_name/test/index.php/transaction/:id <br>



