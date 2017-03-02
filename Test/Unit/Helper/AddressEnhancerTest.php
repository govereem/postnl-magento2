<?php
/**
 *
 *          ..::..
 *     ..::::::::::::..
 *   ::'''''':''::'''''::
 *   ::..  ..:  :  ....::
 *   ::::  :::  :  :   ::
 *   ::::  :::  :  ''' ::
 *   ::::..:::..::.....::
 *     ''::::::::::::''
 *          ''::''
 *
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons License.
 * It is available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to servicedesk@tig.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact servicedesk@tig.nl for more information.
 *
 * @copyright   Copyright (c) Total Internet Group B.V. https://tig.nl/copyright
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 */
namespace TIG\PostNL\Test\Unit\Helper;

use TIG\PostNL\Test\TestCase;
use TIG\PostNL\Helper\AddressEnhancer;

class AddressEnhancerTest extends TestCase
{
    protected $instanceClass = AddressEnhancer::class;

    public function addressProvider()
    {
        $addressGiven = [
            'postcode' => '1014AB',
            'country'  => 'NL',
            'street'   => [
                'Kabelweg 37', ''
            ]
        ];

        $expected = [
            'postcode' => '1014AB',
            'country'  => 'NL',
            'street'   => [
                'Kabelweg', ''
            ],
            'housenumber' => '37',
            'housenumberExtension' => ''
        ];

        return [
            [$addressGiven, $expected]
        ];
    }

    /**
     * @param $address
     * @param $expected
     *
     * @dataProvider addressProvider
     */
    public function testGet($address, $expected)
    {
        $instance = $this->getInstance();
        $instance->set($address);

        $result = $instance->get();

        $this->assertEquals($expected, $result);

    }
}
