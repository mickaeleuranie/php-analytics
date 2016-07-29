<?php

/**
 * Google Analytics class
 * @version 1.0
 * @author Mickaël Euranie
 */
class Analytics
{

    /**
     * @var string
     */
    private $url = 'www.google-analytics.com/collect';

    /**
     * @var string
     */
    private $gaProperty = null;

    /**
     * @var array
     */
    private $fieldTranslations = [
        // basic
        'type'       => 't',
        'trackingId' => 'tid',
        'clientId'   => 'cid',
        'userId'     => 'uid',
        'version'    => 'v',
        // page
        'hostname'   => 'dh',
        'path'       => 'dp',
        'title'      => 'title',
        // event
        'category'   => 'ec',
        'action'     => 'ea',
        'label'      => 'el',
        'value'      => 'ev',
    ];

    /**
     * @var array
     */
    private $config = [
        'v' => 1,
        't' => 'pageview',
    ];

    /**
     * Construct object
     * @access public
     * @param integer $version Facultative version number
     */
    public function __construct($version = null)
    {
        $this->gaProperty = defined('GA_PROPERTY') ? GA_PROPERTY : null;

        if (null !== $version && true === is_int($version)) {
            $this->config['v'] = $version;
        }
    }

    /**
     * Send tracking to google analytics
     * Without any params, it will send a pageview tracking type
     * @access public
     * @param string $type Event hit type
     * @param array $params Parameters
     * @return boolean
     */
    public function send($type = null, array $params = [])
    {
        if (false === empty($type)) {
            $this->config['t'] = $type;
        }

        // construct configuration
        $this->constructConfiguration($params);

        // do analytics call
        return $this->sendToAnalytics();
    }

    /**
     * Construct configuration from params
     * @todo Handle params like il1pi1id, il2pi1nm, etc.
     * @access private
     * @param array $params
     * @return void
     */
    private function constructConfiguration(array $params = []) {
        $this->config['tid'] = $this->gaProperty;
        $this->config['dh'] = $_SERVER['HTTP_HOST'];
        $this->config['dp'] = $_SERVER['REQUEST_URI'];

        foreach ($params as $key => $value) {
            if (true === empty($this->fieldTranslations[$key])) {
                throw new AnalyticsException('Unknow parameter : ' . $key);
            }

            $this->config[$this->fieldTranslations[$key]] = $value;
        }
    }

    /**
     * Translate from human readable field names to analytics readable names
     * @access private
     * @param string $field Field to translate
     * @return string Translated field
     */

    /**
     * Call to analytics function
     * @access private
     * @throws AnalyticsException
     * @return boolean
     */
    private function sendToAnalytics()
    {
        if (null === $this->gaProperty) {
            throw new AnalyticsException('You need to specify your Google Analytics ID');
        }

        $query = http_build_query($this->config);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);

        if (0 !== curl_errno($ch)) {
            throw new AnalyticsException(curl_error($ch));
        }

        curl_close($ch);

        return $result;
    }
}

class AnalyticsException extends Exception {}
