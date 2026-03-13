<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\components\widgets\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
    

echo Nav::widget([
    'items' => [
        '<li class="header">MAIN NAVIGATION</li>',

        [
        'label' => '<i class="fa fa-dashboard"></i> '.Yii::t('app','<span>Branch Details</span>'), 'url' => ['/branch'],
        ],
        


        /*start of students*/
        
                   [                            
                   'label' => '<i class="fa fa-bar-chart-o"></i> '.Yii::t('app','<span>Reports</span>'),
                    'items' => [
                        ['label' => '<span><i class="fa fa-circle-o"></i> '.Yii::t('app','Statistics'), 'url' => ['/branch-reports/statistic']],
                        ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Finances'), 'url' => ['/branch-reports/finances']],
                        ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Academics'), 'url' => ['/branch-reports/academics']],
                    ],
                    ],

        /*end of students*/
 

       
    ],


]);

