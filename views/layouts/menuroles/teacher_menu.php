<?php
use yii\helpers\Html;
use app\components\widgets\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use app\models\EmployeeInfo;
$getEmployee=EmployeeInfo::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
$empId=$getEmployee->emp_id;
echo Nav::widget([
    'items' => [
        '<li class="header">MAIN NAVIGATION</li>',
        [
        'label' => '<i class="fa fa-dashboard"></i> '.Yii::t('app','<span>Dashboard</span>'), 'url' => ['site/account-dashboard'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['site/account-dashboard'])
        ],
        ['label' => '<i class="fa fa-calendar"></i>'.Yii::t('app','<span>Profile</span>'), 'url' => ['/employee/view/','id'=>$empId],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/employee/view/','id'=>$empId])],
        ['label' => '<span><i class="fa fa-calendar"></i></span> '.Yii::t('app','Attendance Calendar'), 'url' => ['site/attendance-employee'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['site/attendance-employee'])], 
        ['label' => '<span><i class="fa fa-files-o"></i></span> '.Yii::t('app','Home Tasks'), 'url' => ['/general/task'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/general/task'])],
         ['label' => '<span><i class="fa fa-calendar"></i></span> '.Yii::t('app','Noticeboard'), 'url' => ['site/parent-noticeboard'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['site/parent-noticeboard'])],
         ['label' => '<span><i class="fa fa-user-md"></i></span> '.Yii::t('app','Student Attendance'), 'url' => ['/student/calendar'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/student/calendar'])],
         [
            'label' => '<i class="fa fa-file-text"></i>'.Yii::t('app','<span>Exams</span>'),
            'items' => [
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','View Exams'), 'url' => ['/exams/exam-details'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/exams/exam-details'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Award List'), 'url' => ['/exams/award-list'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/exams/award-list'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Quiz'), 'url' => ['/exams/quizs'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/exams/add-quiz'])], 

            ],
        ],
        
        [
            'label' => '<i class="fa fa-user-md"></i>'.Yii::t('app','<span>Leave Management </span>'),
            'items' => [
               
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Leave Application & Approval'), 'url' => ['/leave-application'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/leave-application'])],
                
            ],
        ],
        /*['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Assignments'), 'url' => ['/assigments/display-ass'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/assigments/display-ass'])],*/
        ['label' => '<i class="fa fa-envelope-o"></i>'.Yii::t('app','<span>Messages</span>'), 'url' => ['/messages'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/messages'])],
         [
            'label' => '<i class="fa fa-book" aria-hidden="true"></i>'.Yii::t('app','<span>Library Management</span>'),
            'items' => [
                
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Book Return/Renewal'), 'url' => ['/book-issue/return-role'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/book-issue/return-role'])],
            ],
        ],
        
        
        [
            'label' => '<i class="fa fa-user-md"></i>'.Yii::t('app','<span>Class Timetable</span>'),
            'items' => [

                 ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Search Class Timetable'), 'url' => ['/class-timetable/searchtimetable'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/class-timetable/searchtimetable'])],
            ],
        ],     
    ],
]);
