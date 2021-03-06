<?php
/**
 * Created by PhpStorm.
 * User: pnghai
 * Date: 7/12/14
 * Time: 1:21 PM
 */

namespace CEMS;

/**
 * Interface GuzzleStrategy
 * @package CEMS
 */
interface GuzzleStrategy
{
    /**
     * Abstract HTTP Request method using Guzzle
     *
     * @param string $httpMethod
     * @param string $path
     * @param array $params
     * @param null $version
     * @param bool $isAuthorization
     *
     * @return Response|mixed
     * @throws ClientException
     * @throws AuthorizeException
     * @throws ServerException
     * @throws Error
     */
    public function request($httpMethod = 'GET', $path = '', $params = array(), $version = null, $isAuthorization = false);
} 