<?php
namespace app\controllers;
ini_set('max_execution_time', 300);
use app\models\Dashboard;
use app\models\RefClass;
use app\models\RefGroup;
use app\models\RefSection;
use app\models\RefSectionSearch;
use app\models\search\SubjectsSearch;
use app\models\Subjects;
use app\models\Exam;
use app\models\UserLog;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use app\models\SmsSettings;
use app\models\User;
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index','get-group'], // add all actions to take guest to login page
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'logoutget' => ['GET'],
                ],
            ],
        ];
    }
    public function actionCalendar(){
        return $this->render('calendar');
    } 
     
    public function actionSms(){
      if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }else{
          if(Yii::$app->request->post()){
             $smsActive=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
            $data=Yii::$app->request->post();
            $contact=$data['number'];
            $msg=$data['msg'];
            if($smsActive->status == 1){
               $send=Yii::$app->common->SendSmsSimple($contact,$msg);
               Yii::$app->session->setFlash('success', "Message Successfully send..!");
               return $this->redirect(['sms']);
          }else{
            echo 'Oops some issue occur,contact to the support team';
          }
          }else{

       return $this->render('sms');
          }
        }
    }
     public function actionSend(){
       $mesg='ٹیسٹ پیغام';
       //$msg='Hello this is test message';
       // $message = urlencode($msg);

        $parentContact=923459535220;            
        Yii::$app->common->SendSmsSimple($parentContact,$mesg); 
        /*$url = "http://api.bizsms.pk/api-send-branded-sms.aspx?username=krypton@bizsms.pk&pass=CEOkryptonsTechnology2S&text=$message&masking=ALHuda%20S-C&destinationnum=923469475085&language=Urdu";
     
        $ch  =  curl_init();
        $timeout  =  30;
        curl_setopt ($ch,CURLOPT_URL, $url) ;
        curl_setopt ($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch,CURLOPT_CONNECTTIMEOUT, $timeout) ;
        $response = curl_exec($ch);
        curl_close($ch) ; 
        if ($response == 'SMS Sent Successfully.') {
    } else {  
    } 
        echo $response ;*/         
}

public function actionSendAllParents(){
  //echo 'here';die;
      $subjectModel=new Subjects();
       ini_set('max_execution_time', 300);
       if(Yii::$app->request->post()){
       $data=Yii::$app->request->post('Subjects');
       $class_id=$data['class_id'];
       $textArea=Yii::$app->request->post('smsWhole');  
       $smsActive=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
       foreach ($class_id as $key => $class) {
          $classId=$class;
         $stuQuery=yii::$app->db->createCommand("
                SELECT `student_info`.`stu_id` AS `stu_id`,`student_info`.`user_id` AS `user_id`, `student_parents_info`.`contact_no` As `contact`, `student_parents_info`.`cnic` AS `cnic` FROM `student_info` INNER JOIN `student_parents_info` ON student_parents_info.stu_id = student_info.stu_id WHERE (`student_info`.`fk_branch_id`=".yii::$app->common->getBranch().") AND (`student_info`.`is_active`=1) AND (`student_info`.`class_id`=".$classId.") GROUP BY `student_parents_info`.contact_no
                ")->queryAll();
         foreach ($stuQuery as $query) {
           $contct= $query['contact'];
           $stu_id= $query['stu_id'];
                if($smsActive->status == 1){
              $send=Yii::$app->common->SendSms($contct,$textArea,$stu_id);
          }
         }
       }
       Yii::$app->session->setFlash('success', "Message Successfully send");
        $this->redirect(['success']); 
      }else{
        return $this->render('parent/parent-sms',['subjectModel'=>$subjectModel]);
      }
       /*$smsActive=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
            $stuQuery=yii::$app->db->createCommand("
                SELECT `student_info`.`stu_id` AS `stu_id`,`student_info`.`user_id` AS `user_id`, `student_parents_info`.`contact_no` As `contact`, `student_parents_info`.`cnic` AS `cnic` FROM `student_info` INNER JOIN `student_parents_info` ON student_parents_info.stu_id = student_info.stu_id WHERE (`student_info`.`fk_branch_id`=".yii::$app->common->getBranch().") AND (`student_info`.`is_active`=1) GROUP BY `student_parents_info`.cnic
                ")->queryAll(); 
        foreach ($stuQuery as $query){
            $contct= $query['contact'];
            $stu_id= $query['stu_id'];
                if($smsActive->status == 1){
              $send=Yii::$app->common->SendSms($contct,$textArea,$stu_id);
          }
        }
        Yii::$app->session->setFlash('success', "Message Successfully send to all parents");
        $this->redirect(['success']);  */ 
        //die;  
    
    } //end of function
    /*send all department*/
    public function actionSendAllTeacher(){
        ini_set('max_execution_time', 300);
        $smsActive=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
       $textArea=Yii::$app->request->post('smsWholeDepartment');
       $designation=Yii::$app->request->post('designation');
       if(!empty($designation)){
       foreach ($designation as $key => $designationvalue) {
       $getEmployeeContact=\app\models\EmployeeInfo::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'is_active'=>1,'designation_id'=>$designationvalue])->all();
       foreach ($getEmployeeContact as $key => $query) {
               $contct= $query->contact_no;
                $user_id= $query->user_id;
                if($smsActive->status == 1){
                  $send=Yii::$app->common->SendSms($contct,$textArea,$user_id);
                   $status=1;
                }
       }
       }
       }else{
       $getEmployeeContact=\app\models\EmployeeInfo::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'is_active'=>1])->all();
        foreach ($getEmployeeContact as $query){
               $contct= $query->contact_no;
               $user_id= $query->user_id;
                if($smsActive->status == 1){
                $send=Yii::$app->common->SendSms($contct,$textArea,$user_id);
                $status=1;
                
          }
        }
        }
        if($status ==1){
          Yii::$app->session->setFlash('success', "Message Successfully send");
          $this->redirect(['success']);
        }    
    } //end of function
    /*send to outsider*/
    public function actionSendOutsider(){
       ini_set('max_execution_time', 300);
       $smsStatus=Yii::$app->request->post('smsSend');
       $textArea=Yii::$app->request->post('smsOutsider');
       $smsActive=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
       $outsider=\app\models\StudentOutside::find()->where(['branch_id'=>yii::$app->common->getBranch()])->all();
        foreach ($outsider as $query){
               $contct= $query->contact_no;
               $parent= $query->parent_contact;
                if($smsActive->status == 1){
                    if($smsStatus == 'parent'){
              $send=Yii::$app->common->SendSmsSimple($parent,$textArea);
          }else{
              $send=Yii::$app->common->SendSmsSimple($contct,$textArea);

          }
          }
        } 
        Yii::$app->session->setFlash('success', "Message Successfully send");
        $this->redirect(['success']);  
    } //end of function

    /*send to alumni*/
    public function actionSendAlumni(){
       ini_set('max_execution_time', 300);
       $textArea=Yii::$app->request->post('smsalumni');
       $smsActive=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
       $alumni=\app\models\StudentLeaveInfo::find()->where(['branch_id'=>yii::$app->common->getBranch()])->all();
        foreach ($alumni as $query){
            $name = \app\models\StudentInfo::find()->where(['stu_id'=>$query->stu_id])->one();
                $contct= $name->contact_no;
                if($smsActive->status == 1){
              $send=Yii::$app->common->SendSmsSimple($contct,$textArea);
          }
        }
        Yii::$app->session->setFlash('success', "Message Successfully send");
        $this->redirect(['success']);  
    } //end of function
    public function actionSuccess(){
      return $this->render('success');
    }
    
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        
        if(!Yii::$app->user->isGuest){
         
            $studentInfo = \app\models\StudentInfo::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
            $EmployeeInfo = \app\models\EmployeeInfo::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
            if(Yii::$app->user->identity->fk_role_id == 3){
                //echo "student";die;
                return $this->redirect(['dashboard']);
               // return $this->redirect(['student/profile','id'=>$studentInfo->stu_id]);
            }else if(Yii::$app->user->identity->fk_role_id == 4 || Yii::$app->user->identity->fk_role_id == 5 || Yii::$app->user->identity->fk_role_id == 6){
                return $this->redirect(['account-dashboard']);

            }
            /*else if(Yii::$app->user->identity->fk_role_id == 4 || Yii::$app->user->identity->fk_role_id == 6){
                return $this->redirect(['employee/view','id'=>$EmployeeInfo->emp_id]);
                
            }*/
            else{
                
                $dashboard = Dashboard::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch()/*,'status'=>1*/])->all();

                /*student daily attendance*/
                $attendance_std_query = \app\models\StudentAttendance::find()
                    ->select(['count(*) as total','student_attendance.leave_type'])
                    ->innerJoin('student_info si','si.stu_id=student_attendance.fk_stu_id')
                    ->where(['si.fk_branch_id'=>Yii::$app->common->getBranch(),'date(student_attendance.date)'=>date('Y-m-d'),'si.is_active'=>1])
                    ->groupBy('student_attendance.leave_type')
                    ->asArray()
                    ->all();
                /*student attedance array*/
                $attenance_std_data=[];
                foreach ($attendance_std_query as $key=>$attendance_details){
                    $attenance_std_data['leave_type'][]= $attendance_details['leave_type'];
                    $attenance_std_data['total'][]= $attendance_details['total'];

                }
                //print_r($attenance_std_data);die;
                /*employee daily attendance*/
                $attendance_emp_query = \app\models\EmployeeAttendance::find()
                    ->select(['count(*) as total','employee_attendance.leave_type'])
                    ->innerJoin('employee_info ei','ei.emp_id=employee_attendance.fk_empl_id')
                    ->where([
                        'ei.fk_branch_id'=>Yii::$app->common->getBranch(),
                        'date(employee_attendance.date)'=>date('Y-m-d'),
                        'ei.is_active'=>1
                    ])
                    ->groupBy('employee_attendance.leave_type')
                    ->asArray()
                    ->all();
                /*employee attedance array*/
                $attenance_emp_data=[];
                foreach ($attendance_emp_query as $key=>$attendance_emp_details){
                    $attenance_emp_data['leave_type'][]= $attendance_emp_details['leave_type'];
                    $attenance_emp_data['total'][]= $attendance_emp_details['total'];

                }
                $class=RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all();
                /*monthly fee graph array*/
                $monthly_fee_receive=[]; 
                 $year = date('Y');
                //$year = '2017';
                $years = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec'];
                $count_year_available = \app\models\FeeSubmission::find()->where(['like','from_date',$year])->count();
                //echo $count_year_available;die; 
                if($count_year_available >0){
                    foreach ($years as $key => $month) { 
                        if($key >=1 && $key<=3){
                          //$yearNext = date('Y', strtotime('+1 year', strtotime($year)) );
                          $yearNext =$year ;
                            $year_month = $yearNext  .'-'.sprintf("%02d", $key); 
                        }else{
                            $year_month = $year.'-'.sprintf("%02d", $key); 
                        } 

                       // echo $year_month;die;
                        $query =  \app\models\FeeSubmission::find()
                        ->select('sum(head_recv_amount) total_amount_receive,sum(transport_amount) transport_amount_rcv,sum(hostel_amount) hostel_amount_rcv,year_month_interval')
                        ->where(['from_date'=>$year_month])->asArray()->one();
                        $where="from_date like '".$year_month."%'";
                        $fee_arrears_rcv=\app\models\FeeArrearsRcv::find()->where($where)->sum('amount');
                        $month_count = explode(',',$query['year_month_interval']);
                        
                         
                        if($query['total_amount_receive']>0){
                            $monthly_fee_receive[]= [$year_month,$query['total_amount_receive']+ $query['transport_amount_rcv']+$query['hostel_amount_rcv']+$fee_arrears_rcv];
                        }
                    } 
                } 
               // echo '<pre>';print_r($query);die;
               /*$studentAttendanceQueryThirtyDays=Yii::$app->db->createCommand('select count(*) as total from student_attendance where fk_branch_id='.yii::$app->common->getBranch().' and `date` >= DATE_SUB(CURDATE(), INTERVAL 3 DAY)')->queryAll();*/
              $attendance_month_dates = [];
                for($i = 30; $i >= 0; $i--){ 
                    $attendance_month_dates[] = date("Y-m-d", strtotime('-'. $i .' days'));
                }
              $attendance_detail_array=[];
             foreach ($attendance_month_dates as $key => $attdays) { 
                $studentAttendanceQueryThirtyDays=Yii::$app->db->createCommand("SELECT sum(if(leave_type='absent',1,0)) AS `absentCount`,
                    sum(if(leave_type='present',1,0)) AS `presentCount`,
                    sum(if(leave_type='late',1,0)) AS `lateCount`,
                  /*sum(if(leave_type !='absent' and leave_type!='late' and leave_type!='Latewithexcuse' and leave_type !='leave' ,1,0)) AS `presentCount`,*/
                sum(if(leave_type='leave',1,0)) AS `leaveCount` FROM student_attendance where `date` ='".$attdays."'")->queryAll();
                  $attendance_detail_array[$attdays]=$studentAttendanceQueryThirtyDays[0];
             }

             $attendance_employee_dates = [];
                for($i = 30; $i >= 0; $i--){ 
                    $attendance_employee_dates[] = date("Y-m-d", strtotime('-'. $i .' days'));
                }
             $empl_att_array=[];
             foreach ($attendance_employee_dates as $key => $empDays) {
                 $empAttendQuery=Yii::$app->db->createCommand("SELECT sum(if(leave_type='absent',1,0)) AS `absentCount`,
                    sum(if(leave_type='present',1,0)) AS `presentCount`,
                    sum(if(leave_type='late',1,0)) AS `lateCount`,
                  /*sum(if(leave_type !='absent' and leave_type!='late' and leave_type!='Latewithexcuse' and leave_type !='leave' ,1,0)) AS `presentCount`,*/
                sum(if(leave_type='leave',1,0)) AS `leaveCount` FROM employee_attendance where `date` ='".$empDays."'")->queryAll();
                 $empl_att_array[$empDays]=$empAttendQuery[0];
             }
            $totalFeeCollected=\app\models\FeeSubmission::find()->where(['branch_id'=>yii::$app->common->getBranch()])->sum('head_recv_amount + transport_amount + hostel_amount + absent_fine');
            $fee_arrears_rcv=\app\models\FeeArrearsRcv::find()->where(['branch_id'=>yii::$app->common->getBranch()])->sum('amount');
            $totalArrear=\app\models\FeeArears::find()->where(['branch_id'=>yii::$app->common->getBranch(),'status'=>1])->sum('arears');
            $totalFeeSumissionArrears=\app\models\FeeSubmission::find()->where(['branch_id'=>yii::$app->common->getBranch(),'fee_status'=>1])->sum('transport_arrears+hostel_arrears');
            $smssettings=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
               $todayFeeRcv = \app\models\FeeSubmission::find();
               $todayFeeRcv->where(['branch_id'=>Yii::$app->common->getBranch(),'date(recv_date)'=>date('Y-m-d'),'fee_status'=>1]);
               $todayheadAmount = $todayFeeRcv->sum('head_recv_amount');
               $todayTranportAmount = $todayFeeRcv->sum('transport_amount');
               $todayHostelAmount = $todayFeeRcv->sum('hostel_amount');
               $todayAbsentAmount = $todayFeeRcv->sum('absent_fine');
               $todayFeeCollected=$todayheadAmount + $todayTranportAmount +$todayHostelAmount + $todayAbsentAmount;
               $settings = Yii::$app->common->getBranchSettings();
               $sessionStartDate=$settings->current_session_start;
               $sessionEndDate=$settings->current_session_end;
               $totalExpenses=\app\models\Expenses::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])
               ->andWhere(['between', 'date', $sessionStartDate, $sessionEndDate])
               ->sum('amount');

               $todayExpenses=\app\models\Expenses::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'date(date)'=>date('Y-m-d')])->sum('amount');
               $todayFine=\app\models\FineDetail::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'created_date'=>date('Y-m-d')])->sum('payment_received');
               $onlineUsers=\app\models\UserLog::find()->count();
               $onlineUsersName=\app\models\UserLog::find()->all();
                return $this->render('dashboard',[
                    'attendance_data'       =>$attenance_std_data,
                    'attendance_emp_data'   =>$attenance_emp_data,
                    'monthly_fee_receive'   =>json_encode($monthly_fee_receive,JSON_NUMERIC_CHECK),
                    'dashboard'            =>$dashboard,
                    'class'            =>$class,
                    'studentAttendanceQueryThirtyDays'=>$attendance_detail_array,
                    'attendance_month_dates'=>$attendance_month_dates,
                    'attendance_employee_dates'=>$attendance_employee_dates,
                    'empl_att_array'=>$empl_att_array,
                    'totalFeeCollected'=>$totalFeeCollected,
                    'fee_arrears_rcv'=>$fee_arrears_rcv,
                    'totalArrear'=>$totalArrear,
                    'totalFeeSumissionArrears'=>$totalFeeSumissionArrears,
                    'smssettings'=>$smssettings,
                    'todayFeeCollected'=>$todayFeeCollected,
                    'totalExpenses'=>$totalExpenses,
                    'todayExpenses'=>$todayExpenses,
                    'onlineUsers'=>$onlineUsers,
                    'onlineUsersName'=>$onlineUsersName,
                    'todayFine'=>$todayFine,
                   
                ]);
            }

        }else{
            return $this->render('index');
        }
    }

    /* start of student login dashboard child list*/
     public function actionDashboard()
    {
                $dashboard = Dashboard::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch()/*,'status'=>1*/])->all();
                $student=\app\models\StudentInfo::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
                /*student daily attendance*/
               $attendance_std_query = \app\models\StudentAttendance::find()
                    ->select(['count(*) as total','student_attendance.leave_type'])
                    ->innerJoin('student_info si','si.stu_id=student_attendance.fk_stu_id')
                    ->where(['si.fk_branch_id'=>Yii::$app->common->getBranch(),'date(student_attendance.date)'=>date('Y-m-d'),'si.is_active'=>1])
                    ->groupBy('student_attendance.leave_type')
                    ->asArray()
                    ->all();
                /*student attedance array*/
                $attenance_std_data=[];
                foreach ($attendance_std_query as $key=>$attendance_details){
                    $attenance_std_data['leave_type'][]= $attendance_details['leave_type'];
                    $attenance_std_data['total'][]= $attendance_details['total'];

                }
                //print_r($attenance_std_data);die;
                /*employee daily attendance*/
                $attendance_emp_query = \app\models\EmployeeAttendance::find()
                    ->select(['count(*) as total','employee_attendance.leave_type'])
                    ->innerJoin('employee_info ei','ei.emp_id=employee_attendance.fk_empl_id')
                    ->where([
                        'ei.fk_branch_id'=>Yii::$app->common->getBranch(),
                        'date(employee_attendance.date)'=>date('Y-m-d'),
                        'ei.is_active'=>1
                    ])
                    ->groupBy('employee_attendance.leave_type')
                    ->asArray()
                    ->all();
                /*employee attedance array*/
                $attenance_emp_data=[];
                foreach ($attendance_emp_query as $key=>$attendance_emp_details){
                    $attenance_emp_data['leave_type'][]= $attendance_emp_details['leave_type'];
                    $attenance_emp_data['total'][]= $attendance_emp_details['total'];

                }
                $class=RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all();
                /*monthly fee graph array*/
                $monthly_fee_receive=[]; 
                 $year = date('Y');
                //$year = '2017';
                $years = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec'];
                $count_year_available = \app\models\FeeSubmission::find()->where(['like','from_date',$year,'stu_id'=>$student->stu_id])->count();
                //echo $count_year_available;die; 
                if($count_year_available >0){
                    foreach ($years as $key => $month) { 
                        if($key >=1 && $key<=3){
                          //$yearNext = date('Y', strtotime('+1 year', strtotime($year)) );
                          $yearNext =$year ;
                            $year_month = $yearNext  .'-'.sprintf("%02d", $key); 
                        }else{
                            $year_month = $year.'-'.sprintf("%02d", $key); 
                        } 

                       // echo $year_month;die;
                        $query =  \app\models\FeeSubmission::find()
                        ->select('sum(head_recv_amount) total_amount_receive,sum(transport_amount) transport_amount_rcv,sum(hostel_amount) hostel_amount_rcv,year_month_interval')
                        ->where(['from_date'=>$year_month,'stu_id'=>$student->stu_id])->asArray()->one();
                        $where="stu_id='".$student->stu_id."' and from_date like '".$year_month."%'";
                        $fee_arrears_rcv=\app\models\FeeArrearsRcv::find()->where($where)->sum('amount');
                        $month_count = explode(',',$query['year_month_interval']);
                        
                         
                        if($query['total_amount_receive']>0){
                            $monthly_fee_receive[]= [$year_month,$query['total_amount_receive']+ $query['transport_amount_rcv']+$query['hostel_amount_rcv']+$fee_arrears_rcv];
                        }
                    } 
                } 
              $attendance_month_dates = [];
                for($i = 7; $i >= 0; $i--){ 
                    $attendance_month_dates[] = date("Y-m-d", strtotime('-'. $i .' days'));
                }
              $attendance_detail_array=[];
             foreach ($attendance_month_dates as $key => $attdays) { 
                $studentAttendanceQueryThirtyDays=Yii::$app->db->createCommand("SELECT sum(if(leave_type='absent',1,0)) AS `absentCount`,
                    sum(if(leave_type='present',1,0)) AS `presentCount`,
                    sum(if(leave_type='late',1,0)) AS `lateCount`,
                  /*sum(if(leave_type !='absent' and leave_type!='late' and leave_type!='Latewithexcuse' and leave_type !='leave' ,1,0)) AS `presentCount`,*/
                sum(if(leave_type='leave',1,0)) AS `leaveCount` FROM student_attendance where fk_stu_id='".$student->stu_id."' and `date` ='".$attdays."'")->queryAll();
                  $attendance_detail_array[$attdays]=$studentAttendanceQueryThirtyDays[0];
             }
           $totalFeeCollected=\app\models\FeeSubmission::find()->where(['branch_id'=>yii::$app->common->getBranch(),'stu_id'=>$student->stu_id])->sum('head_recv_amount + transport_amount + hostel_amount + absent_fine');
            $fee_arrears_rcv=\app\models\FeeArrearsRcv::find()->where(['branch_id'=>yii::$app->common->getBranch(),'stu_id'=>$student->stu_id])->sum('amount');
            $totalArrear=\app\models\FeeArears::find()->where(['branch_id'=>yii::$app->common->getBranch(),'stu_id'=>$student->stu_id,'status'=>1])->sum('arears');
            $totalFeeSumissionArrears=\app\models\FeeSubmission::find()->where(['branch_id'=>yii::$app->common->getBranch(),'stu_id'=>$student->stu_id,'fee_status'=>1])->sum('transport_arrears+hostel_arrears');

             $previousFeeTakenMonth =  \app\models\FeeSubmission::find()
                        ->select('sum(head_recv_amount) total_amount_receive,sum(transport_amount) transport_amount_rcv,sum(hostel_amount) hostel_amount_rcv,sum(absent_fine) absent_fine_rcv,from_date,to_date')->where(['stu_id'=>intval($student->stu_id),'branch_id'=>yii::$app->common->getBranch(),'fee_status'=>1])->asArray()->one();
                        /*today attendance query*/
              $today_attendance=\app\models\StudentAttendance::find()->where(['fk_stu_id'=>$student->stu_id,'date(date)'=>date("Y-m-d")])->one();

            $smssettings=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
                return $this->render('roles/student-dashboard',[
                    'attendance_data'       =>$attenance_std_data,
                    'attendance_emp_data'   =>$attenance_emp_data,
                    'monthly_fee_receive'   =>json_encode($monthly_fee_receive,JSON_NUMERIC_CHECK),
                    'dashboard'            =>$dashboard,
                    'class'            =>$class,
                    'studentAttendanceQueryThirtyDays'=>$attendance_detail_array,
                    'attendance_month_dates'=>$attendance_month_dates,
                    'totalFeeCollected'=>$totalFeeCollected,
                    'fee_arrears_rcv'=>$fee_arrears_rcv,
                    'totalArrear'=>$totalArrear,
                    'totalFeeSumissionArrears'=>$totalFeeSumissionArrears,
                    'smssettings'=>$smssettings,
                    'student'=>$student,
                    'previousFeeTakenMonth'=>$previousFeeTakenMonth,
                    'today_attendance'=>$today_attendance,
                ]);
            }
    public function actionAttendanceParentCal(){
      $student=\app\models\StudentInfo::find()->where(['is_active'=>1,'fk_branch_id'=>yii::$app->common->getBranch(),'user_id'=>Yii::$app->user->identity->id])->one();
      return $this->render('parent/calendar-parent',['student'=>$student]);
    }
    public function actionParentNoticeboard(){
      return $this->render('parent/parent-noticeboard');
    }

    public function actionAttendanceEmployee(){
      $EmployeeInfo=\app\models\EmployeeInfo::find()->where(['is_active'=>1,'fk_branch_id'=>yii::$app->common->getBranch(),'user_id'=>Yii::$app->user->identity->id])->one();
      return $this->render('employee/calendar-attendance',['EmployeeInfo'=>$EmployeeInfo]);
    }

    public function actionAccountDashboard(){
      if(!Yii::$app->user->isGuest){
            $studentInfo = \app\models\StudentInfo::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
            $EmployeeInfo = \app\models\EmployeeInfo::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
                $dashboard = Dashboard::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch()/*,'status'=>1*/])->all();

                /*student daily attendance*/
                $attendance_std_query = \app\models\StudentAttendance::find()
                    ->select(['count(*) as total','student_attendance.leave_type'])
                    ->innerJoin('student_info si','si.stu_id=student_attendance.fk_stu_id')
                    ->where(['si.fk_branch_id'=>Yii::$app->common->getBranch(),'date(student_attendance.date)'=>date('Y-m-d'),'si.is_active'=>1])
                    ->groupBy('student_attendance.leave_type')
                    ->asArray()
                    ->all();
                /*student attedance array*/
                $attenance_std_data=[];
                foreach ($attendance_std_query as $key=>$attendance_details){
                    $attenance_std_data['leave_type'][]= $attendance_details['leave_type'];
                    $attenance_std_data['total'][]= $attendance_details['total'];

                }
                //print_r($attenance_std_data);die;
                /*employee daily attendance*/
                $attendance_emp_query = \app\models\EmployeeAttendance::find()
                    ->select(['count(*) as total','employee_attendance.leave_type'])
                    ->innerJoin('employee_info ei','ei.emp_id=employee_attendance.fk_empl_id')
                    ->where([
                        'ei.fk_branch_id'=>Yii::$app->common->getBranch(),
                        'date(employee_attendance.date)'=>date('Y-m-d'),
                        'ei.is_active'=>1
                    ])
                    ->groupBy('employee_attendance.leave_type')
                    ->asArray()
                    ->all();
                /*employee attedance array*/
                $attenance_emp_data=[];
                foreach ($attendance_emp_query as $key=>$attendance_emp_details){
                    $attenance_emp_data['leave_type'][]= $attendance_emp_details['leave_type'];
                    $attenance_emp_data['total'][]= $attendance_emp_details['total'];

                }
                $class=RefClass::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all();
                /*monthly fee graph array*/
                $monthly_fee_receive=[]; 
                 $year = date('Y');
                //$year = '2017';
                $years = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec'];
                $count_year_available = \app\models\FeeSubmission::find()->where(['like','from_date',$year])->count();
                //echo $count_year_available;die; 
                if($count_year_available >0){
                    foreach ($years as $key => $month) { 
                        if($key >=1 && $key<=3){
                          //$yearNext = date('Y', strtotime('+1 year', strtotime($year)) );
                          $yearNext =$year ;
                            $year_month = $yearNext  .'-'.sprintf("%02d", $key); 
                        }else{
                            $year_month = $year.'-'.sprintf("%02d", $key); 
                        } 
                       // echo $year_month;die;
                        $query =  \app\models\FeeSubmission::find()
                        ->select('sum(head_recv_amount) total_amount_receive,sum(transport_amount) transport_amount_rcv,sum(hostel_amount) hostel_amount_rcv,year_month_interval')
                        ->where(['from_date'=>$year_month])->asArray()->one();
                        $where="from_date like '".$year_month."%'";
                        $fee_arrears_rcv=\app\models\FeeArrearsRcv::find()->where($where)->sum('amount');
                        $month_count = explode(',',$query['year_month_interval']);
                        
                         
                        if($query['total_amount_receive']>0){
                            $monthly_fee_receive[]= [$year_month,$query['total_amount_receive']+ $query['transport_amount_rcv']+$query['hostel_amount_rcv']+$fee_arrears_rcv];
                        }
                    } 
                } 
              $attendance_month_dates = [];
                for($i = 30; $i >= 0; $i--){ 
                    $attendance_month_dates[] = date("Y-m-d", strtotime('-'. $i .' days'));
                }
              $attendance_detail_array=[];
             foreach ($attendance_month_dates as $key => $attdays) { 
                $studentAttendanceQueryThirtyDays=Yii::$app->db->createCommand("SELECT sum(if(leave_type='absent',1,0)) AS `absentCount`,
                    sum(if(leave_type='present',1,0)) AS `presentCount`,
                    sum(if(leave_type='late',1,0)) AS `lateCount`,
                  /*sum(if(leave_type !='absent' and leave_type!='late' and leave_type!='Latewithexcuse' and leave_type !='leave' ,1,0)) AS `presentCount`,*/
                sum(if(leave_type='leave',1,0)) AS `leaveCount` FROM student_attendance where `date` ='".$attdays."'")->queryAll();
                  $attendance_detail_array[$attdays]=$studentAttendanceQueryThirtyDays[0];
             }

             $attendance_employee_dates = [];
                for($i = 30; $i >= 0; $i--){ 
                    $attendance_employee_dates[] = date("Y-m-d", strtotime('-'. $i .' days'));
                }
             $empl_att_array=[];
             foreach ($attendance_employee_dates as $key => $empDays) {
                 $empAttendQuery=Yii::$app->db->createCommand("SELECT sum(if(leave_type='absent',1,0)) AS `absentCount`,
                    sum(if(leave_type='present',1,0)) AS `presentCount`,
                    sum(if(leave_type='late',1,0)) AS `lateCount`,
                  /*sum(if(leave_type !='absent' and leave_type!='late' and leave_type!='Latewithexcuse' and leave_type !='leave' ,1,0)) AS `presentCount`,*/
                sum(if(leave_type='leave',1,0)) AS `leaveCount` FROM employee_attendance where `date` ='".$empDays."'")->queryAll();
                 $empl_att_array[$empDays]=$empAttendQuery[0];
             }
            $totalFeeCollected=\app\models\FeeSubmission::find()->where(['branch_id'=>yii::$app->common->getBranch()])->sum('head_recv_amount + transport_amount + hostel_amount + absent_fine');
            $fee_arrears_rcv=\app\models\FeeArrearsRcv::find()->where(['branch_id'=>yii::$app->common->getBranch()])->sum('amount');
            $totalArrear=\app\models\FeeArears::find()->where(['branch_id'=>yii::$app->common->getBranch(),'status'=>1])->sum('arears');
            $totalFeeSumissionArrears=\app\models\FeeSubmission::find()->where(['branch_id'=>yii::$app->common->getBranch(),'fee_status'=>1])->sum('transport_arrears+hostel_arrears');
            $smssettings=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
            $todayFeeRcv = \app\models\FeeSubmission::find();
               $todayFeeRcv->where(['branch_id'=>Yii::$app->common->getBranch(),'date(recv_date)'=>date('Y-m-d'),'fee_status'=>1]);
               $todayheadAmount = $todayFeeRcv->sum('head_recv_amount');
               $todayTranportAmount = $todayFeeRcv->sum('transport_amount');
               $todayHostelAmount = $todayFeeRcv->sum('hostel_amount');
               $todayAbsentAmount = $todayFeeRcv->sum('absent_fine');
               $todayFeeCollected=$todayheadAmount + $todayTranportAmount +$todayHostelAmount + $todayAbsentAmount;
                $totalExpenses=\app\models\Expenses::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->sum('amount');
                $totalAbsenFine=\app\models\FeeSubmission::find()->where(['branch_id'=>yii::$app->common->getBranch()])->sum('absent_fine');
                return $this->render('roles/dashboard-account',[
                    'attendance_data'       =>$attenance_std_data,
                    'attendance_emp_data'   =>$attenance_emp_data,
                    'monthly_fee_receive'   =>json_encode($monthly_fee_receive,JSON_NUMERIC_CHECK),
                    'dashboard'            =>$dashboard,
                    'class'            =>$class,
                    'studentAttendanceQueryThirtyDays'=>$attendance_detail_array,
                    'attendance_month_dates'=>$attendance_month_dates,
                    'attendance_employee_dates'=>$attendance_employee_dates,
                    'empl_att_array'=>$empl_att_array,
                    'totalFeeCollected'=>$totalFeeCollected,
                    'fee_arrears_rcv'=>$fee_arrears_rcv,
                    'totalArrear'=>$totalArrear,
                    'totalFeeSumissionArrears'=>$totalFeeSumissionArrears,
                    'smssettings'=>$smssettings,
                    'EmployeeInfo'=>$EmployeeInfo,
                    'todayFeeRcv'=>$todayFeeRcv,
                    'todayFeeCollected'=>$todayFeeCollected,
                    'totalExpenses'=>$totalExpenses,
                    'totalAbsenFine'=>$totalAbsenFine,
                   
                ]);
           

        }else{
            return $this->render('index');
        }

    }
    /* end of student login dashboard child list*/

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        
        $this->layout = 'login';
        
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {

            if($model->login()){
                $client_Ip= Yii::$app->common->get_client_ip();
                $browser_details= Yii::$app->common->getBrowser();
                $user_log = new UserLog();
                $user_log->user_id =Yii::$app->user->id;
                 $user_log->country = 'PK';
                $user_log->ip_address  = (!empty($client_Ip))?$client_Ip:null;
                $user_log->browser=$browser_details['name'];
                $user_log->version = (!empty($browser_details['version']))?$browser_details['version']:null;
                $user_log->platform = (!empty($browser_details['platform']))?$browser_details['platform']:null;
                $user_log->login_date_time = date('Y-m-d H:i:s');
                if(!$user_log->save()){
                  print_r($user_log->getErrors());die;
                }
               //Yii::$app->session->set('program_id',$user->default_program);
                return $this->goBack();
            }else{
                $model->password = '';
                return $this->render('login', [
                    'model' => $model,
                ]);
            }
        }
        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }
    
    public function actionLogout()
    {
        $userlog = UserLog::find()->where(['user_id'=>\Yii::$app->user->id])->orderBy(['id'=>SORT_DESC])->one();
        if( $userlog) {
           $userlog->delete();
       }
        Yii::$app->user->logout();

        return $this->goHome();
    }
    public function actionLogoutget()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }
    public function actionAbout()
    {

        return $this->render('about');
    }
    public function actionGroupSection($cid,$gid=null){
        if(Yii::$app->user->isGuest){
            return $this->goHome();
        }else{
            $searchModel1 = new RefSectionSearch();
            $searchModel2 = new SubjectsSearch();

                /*show all the pharmacist added by nurse logged in.*/
                $querySections = RefSection::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'class_id'=>$cid,'fk_group_id'=>$gid]);

                $querySubjects = Subjects::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'fk_class_id'=>$cid,'fk_group_id'=>$gid]);

            $dataProviderSection = new ActiveDataProvider([
                'query' => $querySections,
            ]);
            $dataProviderSubject = new ActiveDataProvider([
                'query' => $querySubjects,
            ]);

            return $this->render('group-section',[
                'searchModel1' => $searchModel1,
                'dataprovider1' => $dataProviderSection,
                'searchModel2' => $searchModel2,
                'dataprovider2' => $dataProviderSubject,
            ]);

        }
    }

    public function actionGetGroup()
    {
        $class_id= Yii::$app->request->post('depdrop_parents')[0];

        if ($class_id) {
            if ($class_id != null) {
                $out = Yii::$app->common->getGroup($class_id);
                return  Json::encode(['output'=>$out, 'selected'=>'']);
                //return;
            }
        }
        return Json::encode(['output'=>'', 'selected'=>'']);
    }

    public function actionGetSection()
    {
        $group_id = Yii::$app->request->post('depdrop_all_params')['group-id'];
        $class_id = Yii::$app->request->post('depdrop_all_params')['class-id'];
        if (!empty($class_id) && $group_id=='Loading ...') {
            $count_group = RefGroup::find()->where(['fk_class_id'=>$class_id,'fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->count();
            if($count_group ==0){
                $out = Yii::$app->common->getSection($class_id,Null);
                return   Json::encode(['output'=>$out, 'selected'=>'']);
            }else{
                return false;
            }
        }
        elseif(!empty($class_id) && !empty($group_id)){
            $out = Yii::$app->common->getSection($class_id,$group_id);
            return   Json::encode(['output'=>$out, 'selected'=>'']);
        } else{
            $count_group = RefGroup::find()->where(['fk_class_id'=>$class_id,'fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->count();
            if($count_group ==0){
                $out = Yii::$app->common->getSection($class_id,Null);
                return   Json::encode(['output'=>$out, 'selected'=>'']);
            }else{
                return false;
            }
        }

    }
    public function actionGetExamsList(){
        if(Yii::$app->user->isGuest){
            return $this->goHome();
        }
        else {
            if (Yii::$app->request->isAjax) {
                $data=Yii::$app->request->post('depdrop_all_params');
                $class      = $data['class-id'];
                $group      = (isset($data['group-id']))?$data['group-id']:null;
                $section    = $data['exam-section-id'];

                /*listing options.*/
                $filtered_exams = Exam::find()
                    ->select(['exam.fk_exam_type id','et.type name'])
                    ->innerJoin('exam_type et','et.id = exam.fk_exam_type')
                    ->where([
                        'exam.fk_branch_id'  =>Yii::$app->common->getBranch(),
                        'exam.fk_class_id'   =>$class,
                        'exam.fk_group_id'   =>(empty($group))?null:$group,
                        'exam.fk_section_id' =>$section
                    ])
                    ->asArray()
                    ->all();
                return   Json::encode(['output'=>$filtered_exams, 'selected'=>'']);
            }
        }
    }

    public function actionCalendarEvent(){
       $cal= $this->renderAjax('calendrevent');
       return json_encode(['cal'=>$cal]);

    }
    public function actionCalendarTodo(){
       $calendartodolist= $this->renderAjax('calendertodo');
       return json_encode(['calendartodolist'=>$calendartodolist]);
    }

    /*sms activate and inactive*/
     public function actionSmsActive() 
    { 
        $exists=SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
       if(count($exists) > 0){
        $model = SmsSettings::findOne($exists->id);
       }else{
            $model = new SmsSettings();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) { 
            return $this->redirect(['sms-active']); 
        } else { 
            return $this->render('sms-active', [ 
                'model' => $model, 
            ]); 
        } 
    }// end of fucntion
    /*change password*/
    public function actionChangePassword(){
        if(\Yii::$app->request->post()){
            $model=  User::findOne(\Yii::$app->user->id);

            $data=\Yii::$app->request->post();
            $current_password = base64_decode($data['current_password']);
            $new_password = base64_decode($data['new_password']);
            //$confirm_password = base64_decode($data['confrim_password']);
              if(!\Yii::$app->security->validatePassword($current_password, $model->password_hash)){
                  return json_encode(['status'=>0,'message'=>'Current password is Incorrect']);
              }else{
                  $model->setPassword($new_password);
                  if($model->save()){
                      return json_encode(['status'=>1]);
                      //$this->redirect(['site/logout']); 
                  }
              }
        }
    }
    public function actionEvents(){
      return $this->render('events');
    }
} // end of main class
