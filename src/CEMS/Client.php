<?php
/**
 * Created by PhpStorm.
 * User: pnghai179
 * Date: 24/06/2014
 * Time: 13:45
 */

namespace CEMS;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception as GuzzleException;

/**
 * Class Client
 * @package CEMS
 */
class Client
{
    /**
     * @var string
     */
    protected $_accessToken = '';

    /**
     * @var string
     */
    protected $_apiUrl = '';

    /**
     * @var array|\GuzzleHttp\Client
     */
    protected $_client = array();

    /**
     * parse arguments and go to corresponding constructor
     */
    function __construct()
    {
        $this->_client = new GuzzleClient();
        $a = func_get_args();
        $i = func_num_args();

        if (method_exists($this, $f = '__construct' . $i)) {
            call_user_func_array(array($this, $f), $a);
        }
    }

    /**
     * @param string $access_token
     * @param string $api_url
     */
    function __construct2($access_token = '', $api_url = '')
    {
        $this->_apiUrl = $api_url;
        $this->_accessToken = $access_token;
    }

    /**
     * Construct new Client Object
     * @param string $email
     * @param string $password
     * @param string $api_url
     *
     * @throws AuthorizeException
     * @throws ServerException
     * @throws Error
     *
     * @return Client Client object
     */
    function __construct3($email = '', $password = '', $api_url = '')
    {

        $this->_apiUrl = $api_url;
        try {
            $res = $this->_client->post($this->_apiUrl . '/staffs/sign_in.json', [
                'body' => [
                    'staff[email]'    => $email,
                    'staff[password]' => $password
                ]
            ]);
        } catch (GuzzleException\ClientException $e) {
            //catch error 404
            $error_message=$e->getResponse()->json();
            throw new AuthorizeException($error_message['error'], $e->getCode(),$e->getPrevious());
        } catch (GuzzleException\ServerErrorResponseException $e){
            throw new ServerException($e, $e->getCode(),$e->getPrevious());
        } catch (GuzzleException\BadResponseException $e) {
            throw new Error($e->getResponse(), $e->getCode(),$e->getPrevious());
        }
        if (isset($res))
        {
            $JSON_response = $res->json();

            $this->_accessToken = $JSON_response['access_token'];
        }
    }

    function __destruct()
    {
    }

    /**
     * Request method
     * @param      $httpMethod
     * @param      $path
     * @param null $params
     * @param null $version
     *
     * @throws ClientException
     * @throws ServerException
     * @throws Error
     *
     * @return Response return CEMS\Response
     */
    public function request($httpMethod, $path, $params = null, $version = null)
    {
        switch ($httpMethod) {
            case 'GET':
                $request = $this->_client->createRequest($httpMethod, $this->_apiUrl . $path);
                $query = $request->getQuery();
                $query->set('access_token', $this->_accessToken);
                if ($params!=null)
                    foreach ($params as $param => $value)
                        $query->set($param, $value);
                break;
            default:
                //pre-process form data
                $post_params = array();
                $post_params['access_token'] = $this->_accessToken;

                $api_callback = $this->getBetween($path, 'admin/', '.json');
                $api_callback = substr(explode('/',$api_callback)[0],0,-1);
                if ($params!=null)
                    foreach ($params as $param => $value) {
                        $post_params[$api_callback . '[' . $param . ']'] = $value;
                    }
                $request = $this->_client->createRequest($httpMethod, $this->_apiUrl . $path, ['body' => $post_params]);
        }

        try {
            $res = $this->_client->send($request);
        } catch (GuzzleException\ClientException $e) {
            //catch error 404
            $error_message=$e->getResponse()->json();
            throw new ClientException($error_message['error'], $e->getCode(),$e->getPrevious());
        }
        catch (GuzzleException\ServerErrorResponseException $e){
            throw new ServerException($e, $e->getCode(),$e->getPrevious());
        }
        catch (GuzzleException\BadResponseException $e) {
                throw new Error($e->getResponse(), $e->getCode(),$e->getPrevious());
        }
        $response = new Response($res->json());
        return $response;
    }

    /**
     * @param      $path
     * @param null $params
     * @param null $version
     *
     * @return Response
     */
    public function get($path, $params = null, $version = null)
    {
        return $this->request('GET', $path, $params, $version);
    }

    /**
     * @param      $path
     * @param null $params
     * @param null $version
     *
     * @return Response
     */
    public function post($path, $params = null, $version = null)
    {
        return $this->request('POST', $path, $params, $version);
    }

    /**
     * @param      $path
     * @param null $params
     * @param null $version
     *
     * @return Response
     */
    public function put($path, $params = null, $version = null)
    {
        return $this->request('PUT', $path, $params, $version);
    }

    /**
     * @param      $path
     * @param null $params
     * @param null $version
     *
     * @return Response
     */
    public function delete($path, $params = null, $version = null)
    {
        return $this->request('DELETE', $path, $params, $version);
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->_accessToken;
    }

    /**
     * @return string
     */
    public function getApiUrl()
    {
        return $this->_apiUrl;
    }

    /**
     * @param $content
     * @param $start
     * @param $end
     *
     * @return string
     */
    private function getBetween($content, $start, $end)
    {
        $r = explode($start, $content);
        if (isset($r[1])) {
            $r = explode($end, $r[1]);

            return $r[0];
        }

        return '';
    }
}