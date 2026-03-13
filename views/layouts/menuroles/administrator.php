<?php
use yii\helpers\Html;
use app\components\widgets\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;  
echo Nav::widget([
    'activateItems'=>true,
    'activateParents' => true,
    'items' => [
         // '<li class="header">MAIN NAVIGATION</li>',
        [
        'label' => '<i class="fa fa-dashboard"></i> '.Yii::t('app','<span>Dashboard</span>'), 'url' => ['/'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/'])
        ],
        /*start of students*/
         [
          'label' => '<i class="fa fa-group"></i>'.Yii::t('app','<span>Student</span>'),
            'items' => [
                /*['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Assignments'), 'url' => ['/assigments/display-ass'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/assigments/display-ass'])],*/
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Class Summary'), 'url' => ['/analysis'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/analysis'])
                    ],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Search Student'), 'url' => ['/student'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/student'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Today Registered'), 'url' => ['/student/register'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/student/register'])],
                 ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Alumni'), 'url' => ['/analysis/alumni'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/analysis/alumni'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Admission'), 'url' => ['/student/create'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/student/admission'])],
               
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','All Students'), 'url' => ['/general/all'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/general/all'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Add OutSider(Acadmy)'), 'url' => ['/student-outside'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/student-outside'])],

               /* ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','All Inactive Students'), 'url' => ['/student/inactive-student'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/student/inactive-student'])],*/
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Promote Students'), 'url' => ['/student/action'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/student/action'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Shift Alumni'), 'url' => ['/student/alumni'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/student/alumni'])],
                /*['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Student Attendance'), 'url' => ['/student/calendar'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/student/calendar'])],*/
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Attendance'), 'url' => ['/student/attendance-list'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/student/attendance-list'])],
                 ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Attendance Calendar'), 'url' => ['/student/cal'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/student/cal'])],
                 ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Selected SMS'), 'url' => ['student/selected-sms'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/student/selected-sms'])],
                 ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','SLC'), 'url' => ['student/slc'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/student/slc'])],
                    ],
                ],

        /*end of students*/
        /*start of employee*/
        
         [
            'label' => '<i class="fa fa-user-md"></i>'.Yii::t('app','<span>Faculty/Staff </span>'),
            'items' => [
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Add Departments'), 'url' => ['/department'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/department'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Add Designation'), 'url' => ['/designation'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/designation'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Employee List'), 'url' => ['/employee'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/employee'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Attendance'), 'url' => ['/employee/attendance-list'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/employee/attendance-list'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Attendance Calendar'), 'url' => ['/employee/cal'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/employee/cal'])],
                
                
            ],
        ],
        /*end of employee*/
        
        /*start of Exams*/
         [
            'label' => '<i class="fa fa-file-text"></i>'.Yii::t('app','<span>Exams</span>'),
            'items' => [
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Add Exam'), 'url' => ['/exams/create-exam'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/exams/create-exam'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Date Sheet'), 'url' => ['/exams/exam-details'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/exams/exam-details'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Award List'), 'url' => ['/exams/award-list'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/exams/award-list'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','DMC'), 'url' => ['/exams/dmc'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/exams/dmc'])],

                 ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','DMC SMS'), 'url' => ['/exams/sms-class'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/exams/sms-class'])],

                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Previous DMC'), 'url' => ['/exams/dmc-past'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/exams/dmc-past'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Roll No. Slips'), 'url' => ['/exams/roll-no'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/exams/roll-no'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Quiz'), 'url' => ['/exams/quizs'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/exams/add-quiz'])], 
                
            ],
        ],
        /*end of Exams*/

        [
        'label' => '<i class="fa fa-files-o"></i> '.Yii::t('app','<span>Home Tasks</span>'), 'url' => ['/general/task'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/general/task'])
        ],


        
        [
            'label' => '<i class="fa fa-calendar"></i>'.Yii::t('app','<span>NoticeBoard / ToDo</span>'),
            'items' => [
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','NoticeBoard'), 'url' => ['/noticeboard'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/noticeboard'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','To Do List'), 'url' => ['/todo-list'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/todo-list'])], 
            ],
        ],
        /*class timetable*/
        [
            'label' => '<i class="fa fa-user-md"></i>'.Yii::t('app','<span>Class Timetable</span>'),
            'items' => [
                 ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Create Timetable'), 'url' => ['/class-timetable/create'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/class-timetable/create'])],
                 ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Timetable List'), 'url' => ['/class-timetable/index'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/class-timetable/index'])],
                 ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Search Class Timetable'), 'url' => ['/class-timetable/searchtimetable'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/class-timetable/searchtimetable'])],
                
            ],
        ],
        /*end class timetable*/
        /*start of Reports*/
         [
            'label' => '<i class="fa fa-bar-chart-o"></i> '.Yii::t('app','<span>Reports</span>'),
            'items' => [
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','General'), 'url' => ['/reports/general-report'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/reports/general-report'])],
                
                 //['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Expense'), 'url' => ['/expenses/expense-report'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/expenses/expense-report'])],
                  ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Receivable'), 'url' => ['/receivable/receivable-report'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/receivable/receivable-report'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Certificates'), 'url' => ['/reports/slc'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/reports/slc'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Quiz'), 'url' => ['/reports/quiz'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/reports/quiz'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','SMS Log'), 'url' => ['/reports/sms-log'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/reports/quiz'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Fine'), 'url' => ['/reports/fine'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/reports/fine'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Examination'), 'url' => ['/reports/exam'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/reports/exam'])],
                
                 ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Transport'), 'url' => ['/reports/transport'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/reports/transport'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Student Attendance'), 'url' => ['/reports/student-attendance-report'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/reports/student-attendance-report'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Staff Attendance'), 'url' => ['employee/empl-attnd-report'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/employee/empl-attnd-report'])],
                
                
                  
                  /*['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Hostel Report'), 'url' => ['/'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/employee/empl-attnd-report'])],*/

               
            ],
        ],
        /*start of library*/
         [
            'label' => '<i class="fa fa-book" aria-hidden="true"></i>'.Yii::t('app','<span>Library</span>'),
            'items' => [
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Category Lists'), 'url' => ['/addlibrary-category'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/addlibrary-category'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Book Lists'), 'url' => ['/add-books'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/add-books'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Issue Books'), 'url' => ['/book-issue'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/book-issue'])], 
            ],
        ],
        
        /*start of leave mangement*/
        [
            'label' => '<i class="fa fa-user-md"></i>'.Yii::t('app','<span>Leave Management </span>'),
            'items' => [
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Leave Category'), 'url' => ['/leave-category'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/leave-category'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Leave Details'), 'url' => ['/leave-details'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/leave-details'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Leave App & Approval'), 'url' => ['/leave-application/approval'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/leave-application/approval'])],
            ],  
        ],
        /*end of leave mangement and start of noticeboard*/        
        ['label' => '<span><i class="fa fa-envelope-o"></i></span> '.Yii::t('app','<span>Messages</span>'), 'url' => ['/site/sms','id'=>base64_encode('quick')],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/site/sms','id'=>base64_encode('quick')])],
        /*messages*/
        /*end  of Reports and start of transport*/
   [
    'label' => '<i class="fa fa-truck"></i> '.Yii::t('app','<span>Transport</span>'),
    'items' => [
        ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Allocate Students'), 'url' => ['/transport-allocation'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/transport-allocation'])],
        ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Vehicle Management'), 'url' => ['/vehicle-info'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/vehicle-info'])],
        ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Zone/route/stop Management'), 'url' => ['/zone'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/zone'])],
        ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Assign Driver'), 'url' => ['/transport-main'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/transport-main'])],
    ],
   ],
        /*end of Transport and start of Hostel*/
         [
            'label' => '<i class="fa fa-home"></i>'.Yii::t('app','<span>Hostel</span>'),
            'items' => [

                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Hostel Management'), 'url' => ['/hostel'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/hostel'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Allocate Hostel'), 'url' => ['/hostel-detail'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/hostel-detail'])],
            ],
        ],
        /*end of Hostel and start of salary settings*/

        [
            'label' => '<i class="fa fa-eye"></i>'.Yii::t('app','<span>HR/Payroll</span>'),
            'options' => ['class' => 'mega-items setting-opt'],
            'items' => [
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Pay Head'), 'url' => ['/salary-pay-groups'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/salary-pay-groups'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Pay Head Type'), 'url' => ['/salary-pay-stages'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/salary-pay-stages'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Pay Allownce'), 'url' => ['/salary-allownces'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/salary-allownces'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Pay Deduction'), 'url' => ['/salary-deduction-type'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/salary-deduction-type'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Pay Tax'), 'url' => ['/salary-tax'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/salary-tax'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Payment Method'), 'url' => ['/payment-method'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/payment-method'])],
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Generate PaySlip'), 'url' => ['salary-main/leave-department'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/salary-main/leave-department'])],
            ],
       ],     
    ],
]);