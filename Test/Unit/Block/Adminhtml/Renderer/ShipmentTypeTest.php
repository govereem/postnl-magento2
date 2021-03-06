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

namespace TIG\PostNL\Test\Unit\Block\Adminhtml\Renderer;

use TIG\PostNL\Block\Adminhtml\Renderer\ShipmentType;
use TIG\PostNL\Config\Source\Options\ProductOptions;
use TIG\PostNL\Test\TestCase;

class ShipmentTypeTest extends TestCase
{
    public $instanceClass = ShipmentType::class;

    public function returnsTheCorrectResultProvider()
    {
        return [
            'Daytime' => ['3085', 'Daytime', 'Domestic', ''],
            'Evening' => ['3090', 'Evening', 'Domestic', 'Evening'],
            'Evening BE' => ['4941', 'Evening', 'EPS', 'Evening'],
            'Extra@Home' => ['3790', 'ExtraAtHome', 'Extra@Home', ''],
            'Sunday' => ['3385', 'Sunday', 'Domestic', 'Sunday'],
            'Pickup Delivery' => ['3533', 'PG', 'Post office', ''],
            'Pickup Delivery Early' => ['3543', 'PGE', 'Post office', 'Early morning pickup'],
            'EPS' => ['4950', 'EPS', 'EPS', ''],
        ];
    }

    /**
     * @param $productCode
     * @param $shipmentType
     * @param $expectedType
     * @param $expectedComment
     *
     * @dataProvider returnsTheCorrectResultProvider
     */
    public function testReturnsTheCorrectResult($productCode, $shipmentType, $expectedType, $expectedComment)
    {
        $optionsMock = $this->getFakeMock(ProductOptions::class)->getMock();
        $getLabel = $optionsMock->method('getLabel');
        $getLabel->willReturn([
            'label' => $expectedType,
            'comment' => $expectedComment
        ]);

        /** @var ShipmentType $instance */
        $instance = $this->getInstance([
            'productOptions' => $optionsMock
        ]);

        $result = $instance->render($productCode, $shipmentType);

        $this->assertContains($expectedType, $result);

        if ($expectedComment) {
            $this->assertContains($expectedComment, $result);
        }
    }
}
