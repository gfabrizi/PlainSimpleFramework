# PlainSimpleFramework
**What is this?**  
This is a simple PHP framework, written as a code test for a job interview. It is written in pure PHP, without external dependencies.

**Can I use for my next big work-related project?**  
What?!? NO!!

**Can I use for my small personal website?**  
Are you sure? I mean, I don't see why you want to do this... anyway if you are so inclined...

**Can I use it for learning the basics of a simple PHP framework?**  
Yes, sure! Now we're talking!

## How to use
### Create a new project
Create a new project with composer, by issuing the command:
```bash
composer create-project --prefer-dist --stability=dev gfabrizi/plain-simple-framework my-app
```
(here `my-app` is the name of the new folder where you want the project to be downloaded)

Assuming that you have PHP >= 7.2 installed and configured on your machine, you can start a local web server with:
```bash
php -S localhost:8000 -t my-app/web/
```

Now you can open you web browser and go to `http://localhost:8000` to see your app.

### The config file
There is a basic config file located at `app/config/config.php`; it contains the parameters used to connect to your MySql database and some other basic settings.

### The router
The route file is located in `web/index.php`; here you can declare all the routes that you want to manage.  
The `Router` component has 4 methods, each one corresponding to one HTTP verb:

*  `get()`
*  `post()`
*  `put()`
*  `delete()`

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
    protected static $fields = ['id', 'first_name', 'last_name'];

    protected $id;
    protected $firstName;
    protected $lastName;

    public function __construct(int $id, string $firstName, string $lastName)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }
}
```
in the `$tablename` property you have to specify the name of the table this Entity refers to; in the `$fields` property you should write an array with che column names of the table.

### Identity mappers
Identity mappers are what `map` an Entity to the corresponding db entry. You can place Mappers in `app/Mappers`.

Each Entity should have one Identity Mapper, and each Identity Mapper should extends `framework\Mappers\IdentityMapper` abstract class.  
For each mapper you have to implement:

*  `doInsert()` the code to insert an entity to the db
*  `doHydrateEntity()` this method take an array and *hydrates* it back to an Entity
*  `getTargetClass()` returns the name of the Entity class
*  `getCollection()` returns a collection of entities (used when the `findAll()` method is invoked)

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

*  `framework\Responses\Response` used to return an HTML view  
You can pass to the `Response` constructor: the view you intend to use, then an array with data you want to pass to the view and finally an optional HTTP status code (default is 200). 
*  `framework\Responses\JsonResponse` used to return a JSON response
You can pass to the `JsonResponse` constructor: the data you wish to output (preferably as an array) and an optional HTTP status code (default is 200).