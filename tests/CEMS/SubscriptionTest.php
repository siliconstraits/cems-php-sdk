<?php

/**
 * Created by PhpStorm.
 * User: pnghai
 * Date: 04/07/2014
 * Time: 17:47
 */

namespace CEMS\Tests;
use CEMS;

/**
 * Class SubscriptionTest
 * @package CEMS\Tests
 */
class SubscriptionTest extends AbstractResourceTest
{
    public function testCreateSubscription()
    {
        $this->_resource=new CEMS\Subscription('');
        $this->_resource->name='';
        $this->_resource->name='';
        $this->_resource->name='';
        $this->_resource->name='';
        $this->_client->post('/admin/subscriptions.json',$this->_resource->toArray());
    }

    public function testGetSingleSubscription()
    {
        $_resource=$this->_client->get('subscriptions/');
    }

    public function testFetchSubscriptionCollection()
    {
        #todo: return page num, cur page.
    }


    public function testUpdateSubscription()
    {

    }

    public function testDeleteSubscription()
    {

    }
}
 