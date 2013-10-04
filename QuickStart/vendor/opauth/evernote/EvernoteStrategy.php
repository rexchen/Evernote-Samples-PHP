<?php
/**
 * Evernote strategy for Opauth
 * based on http://dev.evernote.com/start/core/authentication.php
 *
 * More information on Opauth: http://opauth.org
 *
 * @copyright    Copyright © 2013 Evernote (http://evernote.com)
 * @link         http://opauth.org
 * @package      Opauth.EvernoteStrategy
 * @license      MIT License
 */

/**
 * Evernote strategy for Opauth
 * based on http://dev.evernote.com/start/core/authentication.php
 *
 * @package      Opauth.Evernote
 */
class EvernoteStrategy extends OpauthStrategy
{
    /**
     * Compulsory config keys, listed as unassociative arrays
     */
    public $expects = array('api_key', 'secret_key');

    /**
     * Optional config keys, without predefining any default values.
     */
    public $optionals = array();

    /**
     * Optional config keys with respective default values, listed as associative arrays
     * eg. array('scope' => 'email');
     */
    public $defaults = array(
        'method' => 'POST',     // The HTTP method being used. e.g. POST, GET, HEAD etc
        'oauth_callback' => '{complete_url_to_strategy}oauth_callback',

        // For Evernote
        'sandbox' => true,
        'base_url' => 'https://sandbox.evernote.com/',
        'request_token_path' => 'oauth',
        'authorize_path'     => 'OAuth.action',
        'access_token_path'  => 'oauth',

        // From tmhOAuth
        'user_token'                  => '',
        'user_secret'                 => '',
        'use_ssl'                     => true,
        'debug'                       => false,
        'force_nonce'                 => false,
        'nonce'                       => false, // used for checking signatures. leave as false for auto
        'force_timestamp'             => false,
        'timestamp'                   => false, // used for checking signatures. leave as false for auto
        'oauth_version'               => '1.0',
        'curl_connecttimeout'         => 30,
        'curl_timeout'                => 10,
        'curl_ssl_verifypeer'         => false,
        'curl_followlocation'         => false, // whether to follow redirects or not
        'curl_proxy'                  => false, // really you don't want to use this if you are using streaming
        'curl_proxyuserpwd'           => false, // format username:password for proxy, if required
        'is_streaming'                => false,
        'streaming_eol'               => "\r\n",
        'streaming_metrics_interval'  => 60,
        'as_header'                   => true,
    );

    public function __construct($strategy, $env)
    {
        parent::__construct($strategy, $env);

        $this->strategy['consumer_key'] = $this->strategy['api_key'];
        $this->strategy['consumer_secret'] = $this->strategy['secret_key'];

        if (!$this->strategy['sandbox']) {
            $this->strategy['base_url'] = 'https://www.evernote.com/';
        }
        $this->strategy['request_token_url'] = $this->strategy['base_url'].$this->strategy['request_token_path'];
        $this->strategy['authorize_url'] = $this->strategy['base_url'].$this->strategy['authorize_path'];
        $this->strategy['access_token_url'] = $this->strategy['base_url'].$this->strategy['access_token_path'];

        require dirname(__FILE__).'/Vendor/tmhOAuth/tmhOAuth.php';
        $this->tmhOAuth = new tmhOAuth($this->strategy);
    }

    /**
     * Auth request
     */
    public function request()
    {
        $params = array(
            'oauth_callback' => $this->strategy['oauth_callback']
        );

        $results =  $this->_request('POST', $this->strategy['request_token_url'], $params);

        if ($results !== false && !empty($results['oauth_token']) && !empty($results['oauth_token_secret'])) {
            session_start();
            $_SESSION['_opauth_evernote'] = $results;

            $this->_authorize($results['oauth_token']);
        }
    }

    /**
     * Receives oauth_verifier, requests for access_token and redirect to callback
     */
    public function oauth_callback()
    {
        session_start();
        $session = $_SESSION['_opauth_evernote'];
        unset($_SESSION['_opauth_evernote']);

        if ($_REQUEST['oauth_token'] == $session['oauth_token'] && isset($_REQUEST['oauth_verifier'])) {

            $this->tmhOAuth->config['user_token'] = $session['oauth_token'];
            $this->tmhOAuth->config['user_secret'] = $session['oauth_token_secret'];

            $params = array(
                'oauth_verifier' => $_REQUEST['oauth_verifier']
            );

            $results =  $this->_request('POST', $this->strategy['access_token_url'], $params);

            if ($results !== false && !empty($results['oauth_token'])) {
                $this->auth = array(
                    'credentials' => array(
                        'token' => $results['oauth_token'],
                        'secret' => $results['oauth_token_secret']
                    ),
                    'info' => array(
                        'shardId' => $results['edam_shard'],
                        'userId' => $results['edam_userId'],
                        'expires' => $results['edam_expires'],
                        'noteStoreUrl' => $results['edam_noteStoreUrl']
                    )
                );
                $this->callback();
            } else {
                $error = array(
                    'code' => 'oauth_token_expected',
                    'message' => 'OAuth token and secret expected.',
                    'raw' => $results
                );
                $this->errorCallback($error);
            }
        } else {
            $error = array(
                'code' => 'access_denied',
                'message' => 'User denied access.',
                'raw' => $_GET
            );
            $this->errorCallback($error);
        }

    }

    private function _authorize($oauth_token)
    {
        $params = array(
            'oauth_token' => $oauth_token
        );

        $this->clientGet($this->strategy['authorize_url'], $params);
    }

    /**
     * Wrapper of tmhOAuth's request() with Opauth's error handling.
     *
     * request():
     * Make an HTTP request using this library. This method doesn't return anything.
     * Instead the response should be inspected directly.
     *
     * @param string $method    the HTTP method being used. e.g. POST, GET, HEAD etc
     * @param string $url       the request URL without query string parameters
     * @param array  $params    the request parameters as an array of key=value pairs
     * @param string $useauth   whether to use authentication when making the request. Default true.
     * @param string $multipart whether this request contains multipart data. Default false
     * @param string $hander    Set to 'json' or 'xml' to parse JSON or XML-based output.
     */
    private function _request($method, $url, $params = array(), $useauth = true, $multipart = false, $handler = null)
    {
        $code = $this->tmhOAuth->request($method, $url, $params, $useauth, $multipart);

        if ($code == 200) {
            if (is_null($handler)) {
                if (strpos($url, '.json') !== false) {
                    $handler = 'json';
                } elseif (strpos($url, '.xml') !== false) {
                    $handler = 'xml';
                }
            }

            if ($handler == 'json') {
                $response = json_decode($this->tmhOAuth->response['response']);
            } elseif ($handler == 'xml') {
                $response = simplexml_load_string($this->tmhOAuth->response['response']);
            } else {
                $response = $this->tmhOAuth->extract_params($this->tmhOAuth->response['response']);
            }

            return $response;
        } else {
            $error = array(
                'code' => $code,
                'raw' => $this->tmhOAuth->response['response']
            );
            $this->errorCallback($error);

            return false;
        }
    }

}
