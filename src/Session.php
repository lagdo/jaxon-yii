<?php

namespace Jaxon\Yii;

class Session
{
    /**
     * The Yii session manager
     *
     * @var object
     */
    protected $xSession = null;

    public function __construct()
    {
        $this->xSession = \Yii::$app->session;
    }

    /**
     * Get the current session id
     *
     * @return string           The session id
     */
    public function getId()
    {
        return $this->xSession->getId();
    }

    /**
     * Generate a new session id
     *
     * @param bool          $bDeleteData         Whether to delete data from the previous session
     *
     * @return void
     */
    public function newId($bDeleteData = false)
    {
        $this->xSession->regenerateID($bDeleteData);
    }

    /**
     * Save data in the session
     *
     * @param string        $sKey                The session key
     * @param string        $xValue              The session value
     *
     * @return void
     */
    public function set($sKey, $xValue)
    {
        $this->xSession->set($sKey, $xValue);
    }

    /**
     * Check if a session key exists
     *
     * @param string        $sKey                The session key
     *
     * @return bool             True if the session key exists, else false
     */
    public function has($sKey)
    {
        return $this->xSession->has($sKey);
    }

    /**
     * Get data from the session
     *
     * @param string        $sKey                The session key
     * @param string        $xDefault            The default value
     *
     * @return mixed|$xDefault             The data under the session key, or the $xDefault parameter
     */
    public function get($sKey, $xDefault = null)
    {
        return $this->xSession->get($sKey, $xDefault);
    }

    /**
     * Get all data in the session
     *
     * @return array             An array of all data in the session
     */
    public function all()
    {
        $aSessionData = [];
        $it = $this->xSession->getIterator();
        foreach($it as $xData)
        {
            $aSessionData[$it->key()] = $it->current();
        }
        return $aSessionData;
    }

    /**
     * Delete a session key and its data
     *
     * @param string        $sKey                The session key
     *
     * @return void
     */
    public function delete($sKey)
    {
        $this->xSession->remove($sKey);
    }

    /**
     * Delete all data in the session
     *
     * @return void
     */
    public function clear()
    {
        $this->xSession->removeAll();
    }
}
