## About

This is module that calls the fruits API and retrieve all existing fruits. 
You can find more information about API here https://www.fruityvice.com/doc/index.html.
The module provides a grid in BO with one button on top of the page, that synchronize data between PS database and API. 
Existing data is not added to the grid. The same information is displayed in displayHome hook in FO.

## Installation

This module contain composer.json file. If you clone or download the module from github
repository, run the ```composer install --no-dev``` from the root module folder or `composer install` for development 
purposes.

See the [composer documentation](https://getcomposer.org/doc/) to learn more about the composer.json file.

## Compiling assets
**For development**

We use _Webpack_ to compile our javascript and scss files.  
In order to compile those files, you must :
1. have _Node 10+_ installed locally
2. run `npm install` in the root folder to install dependencies
3. then run `npm run watch` to compile assets and watch for file changes
4. Be attentive, the admin directory should be `admin-dev`, otherwise the build will fail

**For production**

Run `npm run build` to compile for production.  
Files are minified, `console.log` and comments dropped.

## Cs fixer

`php vendor/bin/php-cs-fixer fix --no-interaction --dry-run --diff` to show code lines to be fixed.

`php vendor/bin/php-cs-fixer fix` to fix automatically all files.

## PHP Stan

You can use phpstan (if composer is in DEV mode).