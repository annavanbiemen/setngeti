# SetnGeti

Using SetnGeti allows you to define automatic setters and getters for object
properties in your PHP 5.4+ projects.

## Automatic setter and getter methods

By using SetnGeti methods, you can eliminate the effort that usually goes into
writing needless amounts of get and set methods by just adding an @get and/or
@set tag to the properties you'd like to expose:

    class User
    {

        use \SetnGeti\Methods;

        /**
         * Sample property
         *
         * @get
         * @set
         * @var string
         */
        private $username;

    }

    $user = new User();
    $user->setUsername('johndoe');

    echo $user->getUsername();

By adding only an @get tag to a property, you can ensure that the property will
only be available for read-only access. On the other hand, a property with just
and @set tag will be write only.

Trying to access a setMethod() on a property without @set, or getMethod() for a
property without @get, you will cause a RuntimeException to be thrown.

## Automatic filtering and typecasting

When setting a property using an automatic setter, you can control the datatype
of the property using the @var tag. The @var tag can one of the following
types:

* A class name
* An interface name
* mixed
* boolean
* bool
* integer
* int
* double
* string
* array
* object
* resource
* null

When setting the property, its value will be tested and in case of a scalar
value be typecasted into the required datatype.

When setting an object to the property value, it will be validated for
conformance to the required class or interface name. When an incompatible
object is passed, an InvalidArgumentException will be thrown.

## Setter Method chaining

The automatic setter methods of SetnGeti allow method chaining by returning the
owner object by default. This allows you to write code like:

    $user->setUsername('johndoe')
         ->setPassword('secret')
         ->store();

## Property access instead of getter and setter methods

As an alternative to having getter and setter methods added to your properties
you might want to access your properties from the outside without marking them
public so you can still perform validation


I'm not saying this approach is generally a good idea, but with a legacy code
base depending on public object properties, this might be a reasonable option
to start controlling property access.
