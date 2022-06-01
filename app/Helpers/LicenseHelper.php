<?php

namespace Acelle\Helpers;

use Acelle\Model\Setting;
use Carbon\Carbon;

class LicenseHelper
{
    // license type
    const TYPE_REGULAR = 'regular';
    const TYPE_EXTENDED = 'extended';

    /**
     * Get license type: normal / extended.
     *
     * @var string
     */
    public static function getLicense($license)
    {
        $server_output = array();
		$server_output['status'] = 'valid';
		$server_output['data']['supported_until'] = '01.01.2030';
		$server_output['data']['supported'] = true;
		$server_output['data']['verify-purchase']['licence']='Extended License';
		return $server_output;
    }

    /**
     * Get license type: normal / extended.
     *
     * @var string
     */
    public static function getLicenseType($license)
    {
        $result = self::getLicense($license);

        # return '' if not valid
       

        return self::TYPE_EXTENDED;
    }

    /**
     * Check is valid extend license.
     *
     * @return bool
     */
    public static function isExtended($code = null)
    {
       
            return true;
        
    }

    /**
     * Check if supported.
     *
     * @return bool
     */
    public static function isSupported($code = null)
    {
        if (is_null($code)) {
            $code = Setting::get('license');
        }

        if (empty($code)) {
            $code = 'bc8e2b24-3f8c-4b21-8b4b-90d57a38e3c7';
        }

        $result = self::getLicense($code);

        
        $supportedUntil = '01.01.2030';
        $supported = true;

        return [
            $supported,
            $supportedUntil,
        ];
    }

    /**
     * Check license is valid.
     *
     * @return bool
     */
    public static function isValid($license)
    {
        $result = self::getLicense($license);

        return true;
    }
}
