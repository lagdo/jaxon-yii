Jaxon Library for Yii
=============================

This package integrates the Jaxon library into the Yii 2 framework.

Features
--------

- Automatically register Jaxon classes from a preset directory.
- Read Jaxon options from a config file.

Installation
------------

Add the following lines in the `composer.json` file, and run the `composer update` command.
```json
"require": {
    "jaxon-php/jaxon-core": "dev-master",
    "jaxon-php/jaxon-framework": "dev-master",
    "jaxon-php/jaxon-yii": "dev-master"
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

Copy the content of the `app/config/` directory of this repo to the `config/` dir of the Yii application.
Copy the content of the `app/controllers/` directory of this repo to the `controllers/` dir of the Yii application.
This will install a controller to process Jaxon requests and a default config file.

Update the routing to redirect post requests to `jaxon` to the above controller.

Configuration
------------

The settings in the jaxon.php config file are separated into two sections.
The options in the `lib` section are those of the Jaxon core library, while the options in the `app` sections are those of the Yii application.

The following options can be defined in the `app` section of the config file.

| Name | Default value | Description |
|------|---------------|-------------|
| dir | @app/jaxon | The directory of the Jaxon classes |
| namespace | \Jaxon\App | The namespace of the Jaxon classes |
| excluded | empty array | Prevent Jaxon from exporting some methods |
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
            'JaxonCss' => $jaxon->css(),
            'JaxonJs' => $jaxon->js(),
            'JaxonScript' => $jaxon->script()
        ));
    }
}
```

Before it prints the page, the controller makes a call to `$jaxon->register()` to export the Jaxon classes.
Then it calls the `$jaxon->css()`, `$jaxon->js()` and `$jaxon->script()` functions to get the CSS and javascript codes generated by Jaxon, which it inserts in the page.

### The Jaxon classes

The Jaxon classes must inherit from `\Jaxon\Framework\Controller`.
They must all be located in the directory indicated by the `app.dir` option in the `jaxon.php` config file, by default `@app/jaxon`.
If there is a namespace associated, the `app.namespace` option should be set accordingly.
The `app.namespace` option must be explicitely set to `null`, `false` or an empty string if there is no namespace.

This is a simple example of Jaxon class, located at `@app/jaxon/HelloWorld.php`.

```php
namespace Jaxon\App;

class HelloWorld extends \Jaxon\Framework\Controller
{
    public function sayHello()
    {
        $this->response->assign('div2', 'innerHTML', 'Hello World!');
        return $this->response;
    }
}
```

Contribute
----------

- Issue Tracker: github.com/jaxon-php/jaxon-yii/issues
- Source Code: github.com/jaxon-php/jaxon-yii

License
-------

The project is licensed under the BSD license.
