[![Build Status](https://travis-ci.com/gfabrizi/PlainSimpleFramework.svg?branch=master)](https://travis-ci.com/gfabrizi/PlainSimpleFramework)
[![codecov](https://codecov.io/gh/gfabrizi/PlainSimpleFramework/branch/master/graph/badge.svg)](https://codecov.io/gh/gfabrizi/PlainSimpleFramework)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/c365567b49c64970b3ac082cca3a2516)](https://www.codacy.com/app/gfabrizi/PlainSimpleFramework?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=gfabrizi/PlainSimpleFramework&amp;utm_campaign=Badge_Grade)

# PlainSimpleFramework
**What is this?**  
This is a simple PHP framework, written as a code test for a job interview. It is written in pure PHP, without external dependencies.

**Can I use for my next big work-related project?**  
What?!? NO!!

**Can I use for my small personal website?**  
Are you sure? I mean, I don't see why you want to do this... anyway if you are so inclined...

**Can I use it for learning the basics of a simple PHP framework?**  
Yes, sure! Now we're talking!

**No external dependencies... so why there are a `composer.json` and a `composer.lock`?**  
The `composer.json` file is used primary for autoloading classes. Also for installing a local version of phpunit used for testing the framework (dev dependency).

## How to use
### Create a new project
Create a new project with composer, by issuing the command:
```bash
composer create-project --prefer-dist --stability=dev gfabrizi/plain-simple-framework my-app
```
(here `my-app` is the name of the new folder where you want the project to be downloaded)

Assuming that you have PHP >= 8.0 installed and configured on your machine, you can start a local web server with:
```bash
php -S localhost:8080 -t my-app/web/
```

Now you can open you web browser and go to `http://localhost:8080` to see your app.

### The config file
There is a basic config file located at `app/config/config.ini`; it contains the parameters used to connect to your MySql database and some other basic settings.

### The router
The route file is located in `web/index.php`; here you can declare all the routes that you want to manage.  
The `Router` component has 4 methods, each one corresponding to one HTTP verb:

*   `get()`
*   `post()`
*   `put()`
*   `delete()`

You can define your routes with a callback:
```php
$router->get('/test', function() {
    echo "This is a very bad example of code design";
});
```
or with a classic controller:
```php
$router->get('/', 'HomeController@index');
```

You can also define a named placeholder in your route like:
```php
$router->get('/users/{id}', 'UserController@edit');
```

### Controllers
Controllers are located in `app/Controllers`. There are no conventions imposed for the naming, but it's a good practice to use the `*Controller.php` suffix pattern.  
Each methods in a Controller receive a `Request` object (used to access get/post parameters) and returns a `ResponseInterface` response.

You can access to all the get/post parameters passed to your route with a `$request->getBody()` call.

To access the named placeholder in your route, you can add it in your method signature:
```php
public function edit(RequestInterface $request, $id): ResponseInterface {
   // do something with $id
}
```
**Note:** actually the placeholder parameters are not type-casted/hinted, so you have to check for the correct data type in your code.

### Entities
An Entity class should extends the `framework\Entities\BaseEntity` abstract class. This is an example of a simple Entity:
```php
class User extends BaseEntity
{
    protected static $tableName = 'users';
    protected static $fields = [
        'id' => ['type' => 'int'],
        'first_name' => [],
        'last_name' => []
    ];
}
```
in the `$tablename` property you have to specify the name of the table this Entity refers to; in the `$fields` property you should write an array in wich each field is a column name on the db (key) plus an array of optional parameters (value).  
For now you can only specify the base data type of the column ('int', 'string' or 'float'). You can omit the optional parameters altogheter; in this case you still have to specify an empty array (i.e. `first_name' => []`).

### Identity mappers
Identity mappers are what `map` an Entity to the corresponding db entry. You can place Mappers in `app/Mappers`.

Each Entity should have one Identity Mapper, and each Identity Mapper should extends `framework\Mappers\IdentityMapper` abstract class.  
For each mapper you have to implement:

*   `getTargetClass()` returns the name of the Entity class

You can provide your own code for the `doInsert()` and `doUpdate()` methods; otherwise, generic ones will be used.  
If you want to customize the way datas are casted to Entity when you retrieve them from db (for instance if your Entity has correlation with another Entity), then you should implement your own `doHydrateEntity()` method.  
In the constructor of an Identity Mapper you can also define relations between Entities (for now only HasOne is implemented):
```php
$this->addRelation(new HasOne(User::class, 'user_id', 'id'));
```

### Collections
Collections are aggregates of entities.  
You can place Collections in `app/Mappers` alongside with your Mappers.
Each collections have to extends `framework\Mappers\Collection`.

Custom Collections are not really necessaries, unless you want to customize the standard behaviour. Otherwise, you can use the base Collection class.

### Views
Views are simple html files saved as `.php` file in `app/Views`.  
You can specify a base layout for your view with the code:
```php
<?php $_mainLayout = 'BaseLayout'; ?>
```
From your base layout you can output the view code by accessing the `$contentInLayout` variable.

### Responses
There are 2 Responses available out of the box:

*   `framework\Responses\Response` used to return an HTML view  
    You can pass to the `Response` constructor: the view you intend to use, then an array with data you want to pass to the view and finally an optional HTTP status code (default is 200).

*   `framework\Responses\JsonResponse` used to return a JSON response
    You can pass to the `JsonResponse` constructor: the data you wish to output (preferably as an array) and an optional HTTP status code (default is 200).

### Service Container
PSF has a simple Service Container for dependency injection:

```php
// Model Logger depends on FileLogger
class Logger {
    public function __construct(private FileLogger $logger)
    {
    }
    
    public function doLog($message): void
    {
        $this->logger->log($message);
    }
}

// Model FileLogger is a dependency of Logger
class FileLogger {
    private const LOGFILE = "/var/log/app.log";

    public function log($message): void
    {
        file_put_contents(self::LOGFILE, $message . PHP_EOL, FILE_APPEND);
    }
}

// In your Controller:
$container = new Container;
$logger = $container->get(Logger::class);
$logger->doLog("This is a log");
```
Unfortunately (for now) the dependency injection can be made only with concrete classes, not with interfaces.

## Test suite
The test suite can be run with the command:
```sh
/vendor/bin/phpunit --configuration framework/phpunit.xml
```