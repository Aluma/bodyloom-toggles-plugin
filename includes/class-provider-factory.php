<?php
namespace Bodyloom\DynamicToggles;

use Bodyloom\DynamicToggles\Providers\ACF_Provider;
use Bodyloom\DynamicToggles\Providers\Metabox_Provider;
use Bodyloom\DynamicToggles\Providers\Pods_Provider;

if (!defined('ABSPATH')) {
    exit;
}

class Provider_Factory
{

    /**
     * Get the first active provider.
     *
     * @return \Bodyloom\DynamicToggles\Interfaces\Field_Provider|null
     */
    public static function get_provider()
    {
        $providers = [
            new ACF_Provider(),
            new Metabox_Provider(),
            new Pods_Provider(),
        ];

        foreach ($providers as $provider) {
            if ($provider->is_active()) {
                return $provider;
            }
        }

        return null;
    }
}
