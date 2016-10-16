<?php

namespace Jaxon\Yii;

class Module extends \yii\base\Module
{
    use \Jaxon\Framework\JaxonTrait;

    /**
     * Create a new Jaxon instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Initialise the properties inherited from JaxonTrait.
        $this->jaxon = jaxon();
        $this->response = new Response();
        $this->view = new View();
        // Call the parent contructor after member initialisation
        parent::__construct('jaxon');
    }

    /**
     * Initialise the Jaxon module.
     *
     * @return void
     */
    public function init()
    {
        $this->setup();
    }

    /**
     * Initialise the Jaxon module.
     *
     * @return void
     */
    public function setup()
    {
        // This function should be called only once
        if(($this->setupCalled))
        {
            return;
        }
        $this->setupCalled = true;

        // initialize the module with the configuration loaded from config.php
        // \Yii::configure($this, require(__DIR__ . '/config.php'));

        $isDebug = ((YII_ENV_DEV) ? true : false);
        $appPath = rtrim(\Yii::getAlias('@app'), '/') . '/';
        $baseUrl = rtrim(\Yii::getAlias('@web'), '/') . '/';
        $baseDir = rtrim(\Yii::getAlias('@webroot'), '/') . '/';

        // Use the Composer autoloader
        $this->jaxon->useComposerAutoloader();
        // Jaxon library default options
        $this->jaxon->setOptions(array(
            'js.app.extern' => !$isDebug,
            'js.app.minify' => !$isDebug,
            'js.app.uri' => $baseUrl . 'jaxon/js',
            'js.app.dir' => $baseDir . 'jaxon/js',
        ));
        // Jaxon library settings
        $config = $this->jaxon->readConfigFile($appPath . 'config/jaxon.php', 'lib');

        // Jaxon application settings
        $appConfig = array();
        if(array_key_exists('app', $config) && is_array($config['app']))
        {
            $appConfig = $config['app'];
        }
        $controllerDir = (array_key_exists('dir', $appConfig) ? $appConfig['dir'] : $appPath . 'jaxon');
        $namespace = (array_key_exists('namespace', $appConfig) ? $appConfig['namespace'] : '\\Jaxon\\App');
        $excluded = (array_key_exists('excluded', $appConfig) ? $appConfig['excluded'] : array());
        // The public methods of the Controller base class must not be exported to javascript
        $controllerClass = new \ReflectionClass('\\Jaxon\\Yii\\Controller');
        foreach ($controllerClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $xMethod)
        {
            $excluded[] = $xMethod->getShortName();
        }

        // Set the request URI
        if(!$this->jaxon->getOption('core.request.uri'))
        {
            $this->jaxon->setOption('core.request.uri', 'jaxon');
        }
        // Register the default Jaxon class directory
        $this->jaxon->addClassDir($controllerDir, $namespace, $excluded);
    }
}
