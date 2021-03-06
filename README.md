Jaxon Library for Yii
=============================

This package integrates the [Jaxon library](https://github.com/jaxon-php/jaxon-core) into the Yii 2 framework.

Features
--------

- Automatically register Jaxon classes from a preset directory.
- Read Jaxon options from a config file.

Installation
------------

Add the following lines in the `composer.json` file, and run the `composer update` command.
```json
"require": {
    "jaxon-php/jaxon-yii": "~3.1"
}
```

Declare the Jaxon module in the `config/web.php` file.
```php
    'modules' => [
        'jaxon' => [
            'class' => 'Jaxon\Yii\Module',
        ],
    ],
```

Configuration
------------

The Jaxon library settings are defined in the `@app/config/jaxon.php` file, and separated into two sections.
The options in the `lib` section are those of the Jaxon core library, while the options in the `app` sections are those of the Yii application.

The following options can be defined in the `app` section of the config file.

| Name | Description |
|------|---------------|
| directories | An array of directory containing Jaxon application classes |
| views   | An array of directory containing Jaxon application views |
| | | |

By default, the `views` array is empty. Views are rendered from the framework default location.
There's a single entry in the `directories` array with the following values.

| Name | Default value | Description |
|------|---------------|-------------|
| directory | @app/jaxon/classes  | The directory of the Jaxon classes |
| namespace | \Jaxon\App  | The namespace of the Jaxon classes |
| separator | .           | The separator in Jaxon class names |
| protected | empty array | Prevent Jaxon from exporting some methods |
| | | |

Usage
-----

This is an example of a Yii controller using the Jaxon library.
```php
namespace app\controllers;

use Yii;
use yii\web\Controller;

class DemoController extends Controller
{
    public function actionIndex()
    {
        // Set the layout
        $this->layout = 'demo';
        // Get the Jaxon module
        $jaxon = Yii::$app->getModule('jaxon');
        $jaxon->register();

        return $this->render('index', array(
            'jaxonCss' => $jaxon->css(),
            'jaxonJs' => $jaxon->js(),
            'jaxonScript' => $jaxon->script()
        ));
    }
}
```

Before it prints the page, the controller calls the `$jaxon->css()`, `$jaxon->js()` and `$jaxon->script()` functions to get the CSS and javascript codes generated by Jaxon, which it inserts into the page.

### The Jaxon classes

The Jaxon classes can inherit from `\Jaxon\CallableClass`.
By default, they are located in the `@app/jaxon/classes` dir of the Yii application, and the associated namespace is `\Jaxon\App`.

This is an example of a Jaxon class, defined in the `@app/jaxon/classes/HelloWorld.php` file.

```php
namespace Jaxon\App;

class HelloWorld extends \Jaxon\CallableClass
{
    public function sayHello()
    {
        $this->response->assign('div2', 'innerHTML', 'Hello World!');
        return $this->response;
    }
}
```

### Request processing

By default, the Jaxon request are handled by the controller in the `src/Controllers/JaxonController.php` file.
The `/jaxon` route is defined in the `src/Module/Module.php` file, and linked to the `JaxonController::actionIndex()` method.

Contribute
----------

- Issue Tracker: github.com/jaxon-php/jaxon-yii/issues
- Source Code: github.com/jaxon-php/jaxon-yii

License
-------

The package is licensed under the BSD license.
