<?php
use yii\helpers\Html;
use app\components\widgets\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;   
$getEmployee=\app\models\EmployeeInfo::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
$empId=$getEmployee->emp_id;
echo Nav::widget([
    'items' => [
        '<li class="header">MAIN NAVIGATION</li>',
        [
        'label' => '<i class="fa fa-dashboard"></i> '.Yii::t('app','<span>Dashboard</span>'), 'url' => ['site/account-dashboard'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['site/account-dashboard'])
        ],
        ['label' => '<i class="fa fa-calendar"></i>'.Yii::t('app','<span>Profile</span>'), 'url' => ['/employee/view/','id'=>$empId],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/employee/view/','id'=>$empId])],
        /*start of students*/
         [
          'label' => '<i class="fa fa-group"></i>'.Yii::t('app','<span>Student</span>'),
            'items' => [
                /*['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Assignments'), 'url' => ['/assigments/display-ass'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/assigments/display-ass'])],*/
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Class Summary'), 'url' => ['/analysis'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/analysis'])
                    ],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Find Student'), 'url' => ['/student'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/student'])],

                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Promote Students'), 'url' => ['/student/promote-students'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/student/promote-students'])],

                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Change Section'), 'url' => ['/student/shuffle-students'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/student/shuffle-students'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Shift Alumni'), 'url' => ['/student/alumni'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/student/alumni'])],
                /*['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Student Attendance'), 'url' => ['/student/calendar'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/student/calendar'])],*/
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Selected SMS'), 'url' => ['student/selected-sms'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/student/selected-sms'])],
                    ],
                ],

        /*end of students*/
        
         /*start of Finance*/
         [
            'label' => '<i class="fa fa-money"></i>'.Yii::t('app','<span>Finance</span>'),
            'items' => [
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Fee Submission'), 'url' => ['fee/fee-submission'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/fee/fee-submission'])],
                
                 
                 ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Submit Admission'), 'url' => ['/fee-submission/create'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/fee-submission/create'])],
                 ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Fee Summary'), 'url' => ['fee/structure'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/fee/structure'])],
                
                 /*class absent fine if needed then show,its work fine*/
                 ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Class Absent Fine'), 'url' => ['/fine-management'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/fine-management'])],
                 /*class absent fine if needed then show,its work fine ends*/
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app',date("M") . ' Arrears Move'), 'url' => ['fee/arrears-move'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['fee/arrears-move'])],
                 ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Update Last Fee'), 'url' => ['/fee-submission'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/fee-submission'])],  
                 ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Transport Upate'), 'url' => ['fee-submission/tranpost-edit-arears'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['fee-submission/tranpost-edit-arears'])],
                /* ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Update Arrears'), 'url' => ['/fee-arears/list'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/fee-arears/list'])],*/
               /* ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Take Current Arrears'), 'url' => ['/fee-arears'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/fee-arears'])], */             
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Fine Type'), 'url' => ['/fine-type'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/fine-type'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Fine Details'), 'url' => ['/fine-detail'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/fine-detail'])],
            ],
        ],
        /*end of Finance*/
         /*start of Exams*/
         [
            'label' => '<i class="fa fa-file-text"></i>'.Yii::t('app','<span>Exams</span>'),
            'items' => [
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Add New Exam'), 'url' => ['/exams/create-exam'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/exams/create-exam'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Exams Schedule'), 'url' => ['/exams/exam-details'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/exams/exam-details'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Award List'), 'url' => ['/exams/award-list'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/exams/award-list'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Detail Marks Sheet'), 'url' => ['/exams/dmc'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/exams/dmc'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Previous DMC'), 'url' => ['/exams/dmc-past'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/exams/dmc-past'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Quiz'), 'url' => ['/exams/quizs'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/exams/add-quiz'])], 
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Roll No. Slips'), 'url' => ['/exams/roll-no'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/exams/roll-no'])],
            ],
        ],
        /*end of Exams*/
        /* start of expenses*/
         [
            'label' => '<i class="fa fa-calendar-o"></i> '.Yii::t('app','<span>Expenses</span>'),
            'items' => [
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Add Expense'), 'url' => ['/expenses'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/expenses'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Expense Category'), 'url' => ['/expense-category'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/expense-category'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Receivable Category'), 'url' => ['/receivable-category'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/receivable-category'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Add Receivable'), 'url' => ['/receivable'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/receivable'])],
            ],
        ],
        /* end of expenses*/
        
        ['label' => '<span><i class="fa fa-calendar"></i></span> '.Yii::t('app','Attendance Calendar'), 'url' => ['site/attendance-employee'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['site/attendance-employee'])], 
         ['label' => '<span><i class="fa fa-calendar"></i></span> '.Yii::t('app','Noticeboard'), 'url' => ['site/parent-noticeboard'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['site/parent-noticeboard'])],

         ['label' => '<i class="fa fa-envelope-o"></i>'.Yii::t('app','<span>Messages</span>'), 'url' => ['/messages'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/messages'])],
        /*['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Assignments'), 'url' => ['/assigments/display-ass'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/assigments/display-ass'])],*/
        /*['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Fine Details'), 'url' => ['/fine-detail'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/fine-detail'])],*/
        /*end of Finance*/
        /*start of salary settings*/

        [
            'label' => '<i class="fa fa-eye"></i>'.Yii::t('app','<span>HR/Payroll</span>'),
            'options' => ['class' => 'mega-items setting-opt'],
            'items' => [
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Pay Head'), 'url' => ['/salary-pay-groups'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/salary-pay-groups'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Pay Head Type'), 'url' => ['/salary-pay-stages'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/salary-pay-stages'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Pay Allownce'), 'url' => ['/salary-allownces'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/salary-allownces'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Pay Deduction'), 'url' => ['/salary-deduction-type'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/salary-deduction-type'])],
                // ['label' => '<span><img src="'.Url::to('@web/img/attandance.svg').'" /></span> Manage Groups','url' => ['/group']],
                // ['label' => '<span><img src="'.Url::to('@web/img/attandance.svg').'" /></span> Manage Sections','url' => ['/section']],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Pay Tax'), 'url' => ['/salary-tax'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/salary-tax'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Payment Method'), 'url' => ['/payment-method'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/payment-method'])],
               /* ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Leave Settings'), 'url' => ['/leave-settings'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/leave-settings'])],*/
                //   ['label' => '<span><img src="'.Url::to('@web/img/attandance.svg').'" /></span> Mode Of Advertisement','url' => ['/visitor-advertisement']],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Generate PaySlip'), 'url' => ['salary-main/leave-department'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/salary-main/leave-department'])],
            ],
        ],
        /*end of salary settings*/

        /*start of leave mangement*/
        [
            'label' => '<i class="fa fa-user-md"></i>'.Yii::t('app','<span>Leave Management </span>'),
            'items' => [
               
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Leave Application & Approval'), 'url' => ['/leave-application'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/leave-application'])],
                
            ],
        ],
        /*end of leave mangement*/
        /*start of Reports*/
         [
            'label' => '<i class="fa fa-bar-chart-o"></i> '.Yii::t('app','<span>Reports</span>'),
            'items' => [
               ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','General'), 'url' => ['/reports/general-report'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/reports/general-report'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Finance'), 'url' => ['/reports/accounts'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/reports/accounts'])],

                  ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Examination'), 'url' => ['/reports/exam'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/reports/exam'])],

                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Expense'), 'url' => ['/expenses/expense-report'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/expenses/expense-report'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Quiz'), 'url' => ['/reports/quiz'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/reports/quiz'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Transport'), 'url' => ['/reports/transport'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/reports/transport'])],
            ],
        ],
        /*start of library*/
         [
            'label' => '<i class="fa fa-book" aria-hidden="true"></i>'.Yii::t('app','<span>Library Management</span>'),
            'items' => [
                
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Book Return/Renewal'), 'url' => ['/book-issue/return-role'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/book-issue/return-role'])],
            ],
        ],
        /*end of library*/
     
        

       
    ],


]);
