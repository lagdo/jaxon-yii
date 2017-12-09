<?php

namespace Jaxon\Yii;

class Module extends \yii\base\Module
{
    use \Jaxon\Sentry\Traits\Armada;

    /**
     * Default route for this package
     *
     * @var string
     */
    public $defaultRoute = 'jaxon';

    /**
     * Namespace of the controllers in this package
     *
     * @var string
     */
    public $controllerNamespace = 'Jaxon\\Yii\\Controllers';

    /**
     * Create a new Jaxon instance.
     *
     * @return void
     */
    public function __construct()
    {
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
        // Initialize the Jaxon plugin
        $this->_jaxonSetup();
    }

    /**
     * Set the module specific options for the Jaxon library.
     *
     * @return void
     */
    protected function jaxonSetup()
    {
        $isDebug = ((YII_ENV_DEV) ? true : false);
        $appPath = rtrim(\Yii::getAlias('@app'), '/');
        $baseUrl = rtrim(\Yii::getAlias('@web'), '/');
        $baseDir = rtrim(\Yii::getAlias('@webroot'), '/');

        $jaxon = jaxon();
        $sentry = $jaxon->sentry();

        // Read and set the config options from the config file
        $this->appConfig = $jaxon->readConfigFile($appPath . '/config/jaxon.php', 'lib', 'app');

        // Jaxon library default settings
        $sentry->setLibraryOptions(!$isDebug, !$isDebug, $baseUrl . '/jaxon/js', $baseDir . '/jaxon/js');

        // Set the default view namespace
        $sentry->addViewNamespace('default', '', '', 'yii');
        $this->appConfig->setOption('options.views.default', 'default');

        // Add the view renderer
        $sentry->addViewRenderer('yii', function () {
            return new View();
        });

        // Set the session manager
        $sentry->setSessionManager(function () {
            return new Session();
        });
    }

    /**
     * Set the module specific options for the Jaxon library.
     *
     * This method needs to set at least the Jaxon request URI.
     *
     * @return void
     */
    protected function jaxonCheck()
    {
        // Todo: check the mandatory options
    }

    /**
     * Wrap the Jaxon response into an HTTP response.
     *
     * @param  $code        The HTTP Response code
     *
     * @return HTTP Response
     */
    public function httpResponse($code = '200')
    {
        // Create and return a Yii HTTP response
        header('Content-Type: ' . $this->ajaxResponse()->getContentType() .
            '; charset=' . $this->ajaxResponse()->getCharacterEncoding());
        \Yii::$app->response->statusCode = $code;
        \Yii::$app->response->content = $this->ajaxResponse()->getOutput();
        return \Yii::$app->response;
    }
}
