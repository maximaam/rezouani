<?php
declare(strict_types=1);

namespace App\Utils;

use InvalidArgumentException;
use function sprintf, strtok, filter_var;

/**
 * Class SeoUtils
 * @package App\Utils
 */
class SeoUtils
{
    /**
     * @param string $host
     * @param string $pathInfo
     * @return string
     */
    public static function canonicalUrl(string $host, string $pathInfo): string
    {
        if (false === filter_var($host, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) {
            throw new InvalidArgumentException(sprintf('Provided Host [%s] is invalid', $host));
        }

        if (false === strpos($pathInfo, '/')) {
            throw new InvalidArgumentException(sprintf('Provided PathInfo [%s] is invalid', $pathInfo));
        }

        $url = sprintf('https://%s%s', $host, $pathInfo);

        //Make sure query string is always stripped, in worse cases, even with $_SERVER['pathInfo']
        $url = strtok($url, '?');

        return $url;
    }

}