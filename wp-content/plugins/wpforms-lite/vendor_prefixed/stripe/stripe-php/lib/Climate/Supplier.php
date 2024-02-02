<?php

// File generated from our OpenAPI spec
namespace WPForms\Vendor\Stripe\Climate;

/**
 * A supplier of carbon removal.
 *
 * @property string $id Unique identifier for the object.
 * @property string $object String representing the object’s type. Objects of the same type share the same value.
 * @property string $info_url Link to a webpage to learn more about the supplier.
 * @property bool $livemode Has the value <code>true</code> if the object exists in live mode or the value <code>false</code> if the object exists in test mode.
 * @property \Stripe\StripeObject[] $locations The locations in which this supplier operates.
 * @property string $name Name of this carbon removal supplier.
 * @property string $removal_pathway The scientific pathway used for carbon removal.
 */
class Supplier extends \WPForms\Vendor\Stripe\ApiResource
{
    const OBJECT_NAME = 'climate.supplier';
    use \WPForms\Vendor\Stripe\ApiOperations\All;
    use \WPForms\Vendor\Stripe\ApiOperations\Retrieve;
    const REMOVAL_PATHWAY_BIOMASS_CARBON_REMOVAL_AND_STORAGE = 'biomass_carbon_removal_and_storage';
    const REMOVAL_PATHWAY_DIRECT_AIR_CAPTURE = 'direct_air_capture';
    const REMOVAL_PATHWAY_ENHANCED_WEATHERING = 'enhanced_weathering';
    const REMOVAL_PATHWAY_VARIOUS = 'various';
}
