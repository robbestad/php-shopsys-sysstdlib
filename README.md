# SysStdLib for ShopSys

###More info

http://blog.robbestad.com

### How to use

In your composer.json, add the following line

    "shopsys/php-shopsys-sysstdlib": "dev-master"


In your code, include the class:

    use SysReports/SysStdLib as lib;

(or use composer's autoloader)

and then in your functions, use it like this:

     lib::getConfig();

Tests:

execute **phpunit vendor/shopsys/sysmandrill/tests/** from the root of your project to run the tests

#####License:

Sven Anders Robbestad (C) 2014

<img src="http://i.creativecommons.org/l/by/3.0/88x31.png" alt="CC BY">

