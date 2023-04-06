<?php
/**
 * Copyright © 2022 Trienekens Online. All rights reserved.
 *
 * @author Rudie Wang <rudie.wang.web@gmail.com>
 * @package Trienekens_UrlTrailer
 */

namespace Trienekens\UrlTrailer\Plugin\Magento\Framework;

/**
 * Class Current
 * @package Trienekens\UrlTrailer\Plugin\Magento\Framework
 */
class Current
{
    /**
     * Remove trailing slash for all links output via getHref
     * @param $subject
     * @param $result
     * @return string
     */
    public function afterGetHref($subject, $result)
    {
        if (empty($result) || !is_string($result)) {
            return $result;
        }

        return rtrim($result, '/');
    }
}