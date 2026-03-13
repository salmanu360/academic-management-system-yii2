<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
Class MakefileController extends Controller
{
    public function actionMake(){
        // root of directory yii2
        // /var/www/html/<yii2>
        $rootyii = realpath(dirname(__FILE__).'/../../');
 
        // create file <hours:menu:seconds>.txt
        $filename = date('H:i:s') . '.txt';
        $folder = $rootyii.'/cronjob/'.$filename;
        $f = fopen($folder, 'w');
        $fw = fwrite($f, 'now : ' . $filename);
        fclose($f);
    }
}
