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
namespace TIG\PostNL\Service\Options;

use TIG\PostNL\Config\Source\Options\ProductOptions;
use TIG\PostNL\Config\Provider\ProductOptions as OptionsProvider;

class ShipmentSupported
{
    /**
     * @var ProductOptions
     */
    private $productOptions;

    /**
     * @var OptionsProvider
     */
    private $optionsProvider;

    /**
     * @var array
     */
    private $allowedCountries = ['NL', 'BE'];

    public function __construct(
        ProductOptions $productOptions,
        OptionsProvider $optionsProvider
    ) {
        $this->productOptions  = $productOptions;
        $this->optionsProvider = $optionsProvider;
    }

    /**
     * @param \Magento\Sales\Api\Data\OrderInterface|\Magento\Sales\Model\Order $order
     * @return array
     */
    public function get($order)
    {
        $address = $order->getShippingAddress();
        return $this->availableOptions($address->getCountryId());
    }

    /**
     * @param $country
     *
     * @return array
     */
    private function availableOptions($country)
    {
        // These are the options selected in the configuration by user.
        $supportedOptions = explode(',', $this->optionsProvider->getSupportedProductOptions());

        $optionsAllowed = $this->getProductOptions($country);
        if (!in_array($country, $this->allowedCountries)) {
            $optionsAllowed = $this->productOptions->getEpsProductOptions();
        }

        $availableOptions = array_filter($supportedOptions, function ($value) use ($optionsAllowed) {
            return isset($optionsAllowed[$value]);
        });

        return $availableOptions;
    }

    /**
     * @param $country
     *
     * @return array
     */
    private function getProductOptions($country)
    {
        $options = $this->productOptions->get();
        return array_filter($options, function ($value) use ($country) {
            return (!$value['countryLimitation'] || $value['countryLimitation'] == $country);
        });
    }
}
