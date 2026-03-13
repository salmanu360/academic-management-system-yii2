<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/font-awesome.min.css',
        'css/AdminLTE.min.css',
        'css/ionicons.min.css',
       // 'css/site1.css',
        'css/skins/_all-skins.min.css',
       //'css/site.css',
        'css/responsive.css',
        'css/color/color-1.css',
        'css/color/color-2.css',
        'css/color/color-3.css',
        'css/color/color-4.css',
        'css/color/color-5.css',
        'css/color/color-6.css',
        'css/color/color-7.css',
        'css/color/color-8.css',
        'css/dark-blue.css',
        'css/styles.css',
        'css/star-rating.min.css',
        'css/jquery.mCustomScrollbar.css',
        'plugins/select2/select2.min.css',
    ];
    public $js = [
        'plugins/slimScroll/jquery.slimscroll.min.js',
        'plugins/fastclick/fastclick.js',
        'plugins/select2/select2.full.min.js',
        'js/app.min.js',
        'js/demo.js',
        'js/site.js',
        'js/custom.js',
        'js/star-rating.min.js',
        'js/jquery.mCustomScrollbar.concat.min.js',
       // 'js/dashboard2.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
