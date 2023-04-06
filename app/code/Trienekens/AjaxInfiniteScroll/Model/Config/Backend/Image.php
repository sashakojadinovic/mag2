<?php
/**
 * Class Image
 *
 * PHP version 7
 *
 * @category Trienekens
 * @package  Trienekens_AjaxInfiniteScroll
 * @author   Rudie Wang <rudie.wang.web@gmail.com>
 */
namespace Trienekens\AjaxInfiniteScroll\Model\Config\Backend;

/**
 * Class Image
 *
 * @category Trienekens
 * @package  Trienekens_AjaxInfiniteScroll
 * @author   Rudie Wang <rudie.wang.web@gmail.com>
 */
class Image extends \Magento\Config\Model\Config\Backend\Image
{
    /**
     * UPLOAD_DIR
     */
    const UPLOAD_DIR = 'trienekens/ajax_infinite_scroll'; // Folder save image

    /**
     * @return string
     */
    protected function _getUploadDir()
    {
        return $this->_mediaDirectory->getAbsolutePath($this->_appendScopeInfo(self::UPLOAD_DIR));
    }

    /**
     * @return bool
     */
    protected function _addWhetherScopeInfo()
    {
        return true;
    }

    /**
     * @return array|string[]
     */
    protected function _getAllowedExtensions()
    {
        return ['jpg', 'jpeg', 'gif', 'png', 'svg'];
    }
}
