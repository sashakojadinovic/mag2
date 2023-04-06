<?php
/**
 * Copyright © 2022 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_UrlTrailer
 */

namespace Trienekens\UrlTrailer\Plugin\Magento\Framework;

use Magento\Framework\UrlInterface as UrlInterfaceFramework;

/**
 * Class UrlInterface
 * @package Trienekens\UrlTrailer\Plugin\Magento\Framework
 */
class UrlInterface
{
    /**
     * Remove trailing slash for getUrl
     *
     * @param UrlInterfaceFramework $subject
     * @param string $result
     * @return string
     */
    public function afterGetUrl(
        UrlInterfaceFramework $subject,
                              $result
    ) {
        if (empty($result) || !is_string($result)) {
            return $result;
        }

        return rtrim($result, '/');
    }
}