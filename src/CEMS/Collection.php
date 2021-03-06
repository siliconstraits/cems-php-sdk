<?php
/**
 * Created by PhpStorm.
 * User: pnghai
 * Date: 02/07/2014
 * Time: 13:32
 */

namespace CEMS;

/**
 * Class Collection contains array of items from response data.
 * @package CEMS
 */
class Collection
{
    /**
     * Collection data
     *
     * @var array
     */
    protected $collection = array();

    /**
     * Collection results' params
     *
     * @var array{
     *      An array of  Collection's Parameters
     * @type int $per_page Per Page
     * @type int $current_page Current Page
     * @type int $total_pages Total Pages
     * @type int $total_entries Total  Entries
     * @type int $offset Offset
     * @type int $length Length
     * }
     */
    protected $params = array();

    /**
     * Collection Constructor
     *
     * If there is no entry return from response data, it will return an empty collection.
     *
     * @param        $raw_response
     * @param string $type
     */
    function __construct($raw_response, $type = 'CEMS\Resource')
    {
        $this->collection = array();

        //Save page num, current page to this class
        $params = $raw_response;
        unset($params['collection']);
        if ($params['total_entries'] == 0)
            return;
        $class = ucwords($type);
        if (class_exists($class)) {
            foreach ($raw_response['collection'] as $item) {
                $object = new $class($item);
                array_push($this->collection, $object);
            }
        }
    }

    /**
     * Get collection data
     * @return array
     */
    public function toArray()
    {
        return $this->collection;
    }

    /**
     * Getter for the parameters of collection
     * @param $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        if (array_key_exists($key, $this->params)) {
            return $this->params[$key];
        }

        return null;
    }

    /**
     * Check if parameters associated with collection exist
     * @param $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->params[$key]);
    }

} 