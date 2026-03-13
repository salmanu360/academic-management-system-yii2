<?php
namespace app\controllers;
ini_set('max_execution_time', 300);
use app\models\FeeDiscounts;
use app\models\FeePlan;
use app\models\FeeHead;
use app\models\FineType;
use app\models\Hostel;
use app\models\SmsLog;
use app\models\StudentLeaveInfo;
use app\models\StuRegLogAssociation;
use Yii;
use app\models\User;
use app\models\StudentInfo;
use app\models\search\StudentInfoSearch;
use app\models\search\StudentAttendanceSearch;
use app\models\StudentParentsInfo;
use app\models\EmployeeAttendance;
use app\models\RefProvince;
use app\models\RefDistrict;
use app\models\RefCities;
use app\models\Exam;
use app\models\RefClass;
use app\models\HostelBed;
use app\models\HostelRoom;
use app\models\HostelFloor;
use app\models\RefGroup;
use app\models\RefSection;
use app\models\Route;
use app\models\UserStepone;
use app\models\Stop;
use app\models\HostelDetail;
use app\models\StudentEducationalHistoryInfo;
use app\models\StudentAttendance;
use app\models\ExamType;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;
use mPDF;
use DateTime;
use DatePeriod;
use DateInterval;
/**
 * StudentInfoController implements the CRUD actions for StudentInfo model.
 */
class StudentController extends Controller
{
  public $enableCsrfValidation = false;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
      return [
      'verbs' => [
      'class' => VerbFilter::className(),
      'actions' => [
      'delete' => ['GET'],
      ],
      ],
      ];
    }
    public function actionFunctions(){

        return $this->renderAjax('StuCalfunctions');
    }
   
    
    public function actionAdmit(){
      if(Yii::$app->request->get('id')){
        $id=base64_decode(Yii::$app->request->get('id'));
        $model=User::find()->where(['id'=>$id])->one();
        }else{
        $model=new User();
        }
      if ($model->load(Yii::$app->request->post())) {
        // echo '<pre>';print_r($_FILES);die;
        $data=Yii::$app->request->post('User');
        $oldUser=User::find()->where(['username'=>$data['username']])->one();
        if(count($oldUser)>0 && !isset($id)){
          Yii::$app->session->setFlash('error', "This Registeration No. is already exist");
        }
            $password=Yii::$app->common->getBranchDetail()->password;
            $random_password= Yii::$app->getSecurity()->generateRandomString($length = 7);
            $random_password=$password;
            $model->setPassword($random_password);
            $model->generateAuthKey();
            $model->fk_role_id= 3;
            $model->status='active';
            $model->fk_branch_id= Yii::$app->common->getBranch();
          if(!empty($_FILES['User']['name']['Image'])){
          if($_FILES['User']['size']['Image'] > 99543){
            Yii::$app->session->setFlash('error', "Only 100kb or less then 100kb image is allowed");
            return $this->render('admit',[
            'model'=>$model,
               ]);
          }else{
          $file =$model->Image= UploadedFile::getInstance($model, 'Image');
            $model->Image=$file;
            $file->saveAs(\Yii::$app->basePath . '/web/uploads/'.$file);
          }
          }

          $model->save();
          return $this->redirect(['edit', 
            'id' => base64_encode($model->id),
            
          ]);
       
      }else 
      {
            return $this->render('admit',[
        'model'=>$model,
        
      ]);
        }

    }
    
    /* leave info */
    public function actionSlc(){
      $model=new User();
      if(Yii::$app->request->post('User')){
        $data=Yii::$app->request->post('User');
        $username=$data['username'];
        $userTable=User::find()->where(['username'=>$username])->one();
        $studentTable=StudentInfo::find()->where(['user_id'=>$userTable->id])->one();
        $sessionDetails=\app\models\RefSession::find()->where(['session_id'=>$studentTable['session_id']])->one();
        $StudentLeaveInfo=\app\models\StudentLeaveInfo::find()->where(['stu_id'=>$studentTable['stu_id']])->one();
        if(empty($StudentLeaveInfo)){
          Yii::$app->session->setFlash('success', "No slc issued");
           return $this->redirect(['slc']);
        }
      return $this->render('leave/index', [
        'userTable' => $userTable,
        'username' => $username,
        'studentTable' => $studentTable,
        'StudentLeaveInfo' => $StudentLeaveInfo,
        'sessionDetails' => $sessionDetails,
      ]);
      }
      if(Yii::$app->request->post('StudentLeaveInfo')){
        // echo '<pre>';print_r($_POST);die;
        $data=Yii::$app->request->post('StudentLeaveInfo');
        $model = StudentLeaveInfo::find()->where(['stu_id'=>$data['stu_id']])->One();
        $model->enrollment_class=$data['enrollment_class'];
        $model->save(false);
        Yii::$app->session->setFlash('success', "Data successfully saved..!");
        return $this->redirect(['slc']);
      }
      return $this->render('leave/first',['model'=>$model]); 
    }

    public function actionSlcslip($id){
      $id = Yii::$app->request->get('id');
      // $levInfo=StudentLeaveInfo::find()->where(['stu_id'=>$id])->one();
      $levInfo = StudentLeaveInfo::findOne($id);
      $slcview = $this->renderAjax('leave/slc-pdf',['levInfo'=>$levInfo]);
      $this->layout = 'pdf';
      $mpdf = new mPDF('c', 'A4-L');
      $mpdf->WriteHTML($slcview);
      $mpdf->Output('Student-SLC.pdf', 'D');
      
   }
    /* leave info end */
     public function actionCreate() 
    { 
      $smssettings=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
        if(Yii::$app->request->get('id')){
        $id=base64_decode(Yii::$app->request->get('id'));
        $model=UserStepone::find()->where(['id'=>$id])->one();
        }else{
         $model = new \app\models\UserStepone(); 
        }
        if ($model->load(Yii::$app->request->post())) {
            $data=Yii::$app->request->post('UserStepone');
            $stringName=$data['username'];
            $password=Yii::$app->common->getBranchDetail()->password;
            $random_password= Yii::$app->getSecurity()->generateRandomString($length = 7);
            $random_password=$password;
            $model->setPassword($random_password);
            $model->generateAuthKey();
          if(!empty($_FILES['UserStepone']['name']['Image'])){
            $file = UploadedFile::getInstance($model, 'Image');
            $model->Image=$file;
            }
         if($model->save()){
           if(!empty($_FILES['UserStepone']['name']['Image'])){
            $file->saveAs(\Yii::$app->basePath . '/web/uploads/'.$file);
           }
          Yii::$app->session->setFlash('success', "Information successfully saved..!");
          return $this->redirect(['edit', 'id' => base64_encode($model->id)]); 
         }else{
          //print_r($model->getErrors());die;
         }
            
        } 

        return $this->render('step1', [ 
            'model' => $model, 
        ]); 
    }
    public function actionRegister() 
    { 
        $dataProvider = new ActiveDataProvider([ 
            'query' => User::find()->where(['fk_role_id'=>3])
            ->andWhere(['date(created_at)'=>date('Y-m-d')]),
        ]); 

        return $this->render('new-register', [ 
            'dataProvider' => $dataProvider, 
        ]); 
    }



    public function actionEdit($id){
      $id=base64_decode($id);
      $model=UserStepone::findOne(['id'=>$id]);
      $old=UserStepone::findOne(['id'=>$id]);
      if ($model->load(Yii::$app->request->post())) { 
        if(!empty($_FILES['UserStepone']['name']['Image'])){
            $file =UploadedFile::getInstance($model, 'Image');
            $model->Image=$file;
             $pth= Yii::$app->basePath . '/web/uploads/'.$old->Image;
              if(!empty($old->Image)){
                 unlink($pth);
             }
            $file->saveAs(\Yii::$app->basePath . '/web/uploads/'.$file,false);
            }else{
             $model->Image=$old->Image; 
            }
         $model->save();
         Yii::$app->session->setFlash('success', "Information successfully updated");
            return $this->redirect(['edit', 'id' =>  base64_encode($id)]); 
        }else{
      return $this->render('step1',['model'=>$model]);
        }
    }

    public function actionOfficial($id){
      $id=base64_decode($id);
      $student_info=StudentInfo::find()->where(['user_id'=>$id])->one();
      if(count($student_info)>0){
        $model=StudentInfo::find()->where(['user_id'=>$id])->one();
      }else{
      $model=new StudentInfo();
      }
      if ($model->load(Yii::$app->request->post())) {
        $model->user_id=$id;
        $model->is_active=1;
        $model->fk_branch_id=Yii::$app->common->getBranch();
      if($model->save()){
         Yii::$app->session->setFlash('success', "Oficial details successfully saved");
            return $this->redirect(['official', 'id' => base64_encode($id)]); 
      }else{
       // print_r($model->getErrors());die;
        } 
      }
      return $this->render('official',[
        'id'=>$id,
        'model'=>$model,
      ]);
    }

    public function actionParent($id){
      $id=base64_decode($id);
      $student_id=\app\models\StudentInfo::find()->where(['user_id'=>$id])->one();
      $user_table=User::find()->where(['id'=>$id])->one();
      if(count($student_id) == 0){
        echo "<div class='alert alert-danger'>Step 2 need to fill first</div>";
        echo "<a class='btn btn-success' href='".\yii\helpers\Url::to(['official','id'=>base64_encode($id)])."'>Back</a>";
        die;
      }
      $student_info=StudentParentsInfo::find()->where(['stu_id'=>$student_id->stu_id])->one();
      if(count($student_info)>0){
        $model=StudentParentsInfo::find()->where(['stu_id'=>$student_id->stu_id])->one();
      }else{
      $model=new StudentParentsInfo();
      }
      if ($model->load(Yii::$app->request->post())) {

        $data=Yii::$app->request->post('StudentParentsInfo');
        $parentContact=$data['contact_no'];
        $model->stu_id=$student_id->stu_id;
        $stu_id=$student_id->stu_id;
        $full_name=$user_table->first_name .' '. $user_table->last_name;
        $userName=$user_table->username;

        $password=Yii::$app->common->getBranchDetail()->password;
        if($model->save()){
          /*sms send*/
          $settingQuery=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
                                    $mesgControl =\app\models\MessageControl::find()->where(['branch_id'=>Yii::$app->common->getBranch(),'message_id'=>'admission'])->One();
                                    $schoolName=strtolower($settingQuery->school_name);
                                    $msg=$mesgControl->message.'<br> '.$full_name. ' Login is '.$userName.' and password is '.$password.',(http://33344450.com/'.$schoolName.')';
                                    if($settingQuery->status == 1 && count($student_info) == 0 ){
                                    Yii::$app->common->SendSms($parentContact,$msg,$stu_id);
                                    }
          /*sms send end*/
         Yii::$app->session->setFlash('success', "Parent Details successfully Saved");
            return $this->redirect(['parent', 'id' => base64_encode($id)]); 
      }else{
       // print_r($model->getErrors());die;
        }
      }
      return $this->render('parent',['model'=>$model]);
    }
    public function actionEducation($id){
      $id=base64_decode($id);
      $student_id=\app\models\StudentInfo::find()->where(['user_id'=>$id])->one();
      if(count($student_id) == 0){
        echo "<div class='alert alert-danger'>Step 2 need to fill first</div>";
        echo "<a class='btn btn-success' href='".\yii\helpers\Url::to(['official','id'=>base64_encode($id)])."'>Back</a>";
        die;
      }
      $student_info=StudentEducationalHistoryInfo::find()->where(['stu_id'=>$student_id->stu_id])->one();
      if(count($student_info)>0){
        $model=StudentEducationalHistoryInfo::find()->where(['stu_id'=>$student_id->stu_id])->one();
      }else{
        $model=new StudentEducationalHistoryInfo();
      }
      if ($model->load(Yii::$app->request->post())) {
        $model->stu_id=$student_id->stu_id;
        if($model->save()){
         Yii::$app->session->setFlash('success', "Educational details successfully Saved");
            return $this->redirect(['education', 'id' => base64_encode($id)]); 
      }else{
        print_r($model->getErrors());die;
        }
      }
      return $this->render('education',['model'=>$model]);
    }

    public function actionFee($id){
      $id=base64_decode($id);
       $model= new FeePlan();

      // $student_id=\app\models\StudentInfo::find()->where(['user_id'=>$id])->one();
      $student_info=\app\models\StudentInfo::find()->where(['user_id'=>$id])->one();
      $old_fee_check=FeePlan::find()->where(['stu_id'=>$student_info->stu_id])->one();
      if(count($old_fee_check)>0){
        $this->redirect(['fee-edit', 'id' => base64_encode($id)]); 
      }
      if(count($student_info) == 0){
        echo "<div class='alert alert-danger'>Step 2 need to fill first</div>";
        echo "<a class='btn btn-success' href='".\yii\helpers\Url::to(['official','id'=>base64_encode($id)])."'>Back</a>";
        die;
      }
      $student_parents_info=StudentParentsInfo::find()->where(['stu_id'=>$student_info->stu_id])->one();
      if(count($student_parents_info) == 0){
        echo "<div class='alert alert-danger'>Step 3 need to fill first</div>";
        echo "<a class='btn btn-success' href='".\yii\helpers\Url::to(['parent','id'=>base64_encode($id)])."'>Back</a>";
        die;
      }
      $parent_cnic=$student_parents_info->cnic;
      $class_id=$student_info->class_id;
      $group_id=$student_info->group_id;
      $getFeeDetails = \app\models\FeeGroup::find()
     ->where([
      'fk_branch_id'  =>Yii::$app->common->getBranch(),
      'fk_class_id'   => $class_id,
      'fk_group_id'   => ($group_id)?$group_id:null,
      ])->all();
      $cnic_count=StudentParentsInfo::find()->where(['cnic'=>$parent_cnic])->count();
      if ($model->load(Yii::$app->request->post())) {
        // echo '<pre>';print_r($_POST);die;
        $headDiscountType=yii::$app->request->post('head_hidden_discount_type');
        $feePlanArray=yii::$app->request->post('FeePlan');
        $feeHeadIdArray=$feePlanArray['fee_head_id'];
              $headDiscount=$feePlanArray['dicount'];
              if(empty($headDiscount)){
                $headDiscountArray=0;
              }else{
              $headDiscountArray=$headDiscount;
              }
              foreach ($feeHeadIdArray as $key => $feeplankeys) {
                // echo $key;
               $FeePlanmodels= new FeePlan();
               $FeePlanmodels->stu_id=$model->stu_id;
               $FeePlanmodels->fee_head_id=$feeplankeys;
               if(empty($headDiscountArray[$key])){
              continue;
               }else{
                $FeePlanmodels->discount=$headDiscountArray[$key];
               }

              // echo $key;
               $FeePlanmodels->fk_fee_discounts_type_id=$headDiscountType[$feeplankeys];
                
               $FeePlanmodels->branch_id=yii::$app->common->getBranch();
               $FeePlanmodels->stu_id=$student_info->stu_id;
               if($FeePlanmodels->save()){
                Yii::$app->session->setFlash('success', "Fee details successfully Saved");
                $this->redirect(['fee', 'id' => base64_encode($id)]); 
              }else{
                print_r($FeePlanmodels->getErrors());die;
              }
             } //end of foreach 

      }else{
     return $this->render('fee.php',[
      'getFeeDetails' =>$getFeeDetails,
      'parent_cnic' =>$parent_cnic,
      'cnic_count' =>$cnic_count,
      'model' =>$model,
      'student_info' =>$student_info,
     ]);
    }
    }
    public function actionFeeEdit($id){
       $model= new FeePlan();
       $id=base64_decode($id);
       $student_info=\app\models\StudentInfo::find()->where(['user_id'=>$id])->one();
       $fee_old =FeePlan::find()->where(['stu_id'=>$student_info->stu_id,'status'=>1])->all(); 
        if (Yii::$app->request->post() ) {
          $data=Yii::$app->request->post('FeePlan');
          $discount=$data['discount'];
        foreach ($discount as $key => $value) {
          $feeUpdate=FeePlan::find()->where(['id'=>$key])->one();
          $feeUpdate->discount=$value;
          if($feeUpdate->save()){
            Yii::$app->session->setFlash('success', "Fee successfully updated");
            $this->redirect(['fee', 'id' => base64_encode($id)]);
          }else{
            print_r($feeUpdate->getErrors());die;
          }
        }
        }else{
          return $this->render('fee-edit', [ 
            'model' => $model, 
            'fee_old' => $fee_old, 
        ]);
        }

        
    }

    public function actionAll(){
      $customers = User::find()
    ->joinWith('student')
    ->where(['user.status' => user::STATUS_ACTIVE])
    ->all();
      $dataProvider = new ActiveDataProvider([ 
        'query'=>$customers,
            /*'query' => User::find()->where(['status'=>'active','fk_branch_id'=>Yii::$app->common->getBranch()])->orderBy(['username'=>SORT_ASC]), */
            'pagination' => [
                     'pageSize' => 10,
                    ],
        ]); 
        return $this->render('all-students', [ 
            'dataProvider' => $dataProvider, 
        ]); 
    }

    //send sms to student parent
      public function actionSendSmsParent(){
       $studentId= Yii::$app->request->post('studentId');
       $msg=Yii::$app->request->post('textarea');
      // $stringMessage = str_replace(' ', '%20', $msg);
       $getparentcontact=StudentParentsInfo::find()->select('contact_no')->where(['stu_id'=>$studentId])->one();
       $parentContacts=$getparentcontact->contact_no;
       $smsActive=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
       if($smsActive->status == 1){
        Yii::$app->common->SendSms($parentContacts,$msg,$studentId); 
     }   
     } 
    //end of send sms to student parent

     /*send outsider message*/
     public function actionOutsiderText(){
       $studentId= Yii::$app->request->post('studentId');
       $msg=Yii::$app->request->post('textarea');
       $smsStatus=Yii::$app->request->post('smsRadio');
       $contact=\app\models\StudentOutside::find()->where(['id'=>$studentId])->one();
       $parentContacts=$contact->parent_contact;
       $studentContacts=$contact->contact_no;
       $smsActive=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
       if($smsActive->status == 1){ 
        if($smsStatus == 'parent'){
        Yii::$app->common->SendSmsSimple($parentContacts,$msg);
        }else{
        Yii::$app->common->SendSmsSimple($studentContacts,$msg);
          
        }
     }   
     } //end of function
     /*end of send outsider message*/

    /**
     * Lists all StudentInfo models.
     * @return mixed
     */
    public function actionIndexOld()
    {
     if(Yii::$app->user->isGuest){
       return $this->goHome();
     }else{
      $model=new StudentInfo();
      $searchModel = new StudentInfoSearch();
      $searchModel->is_active=1;
      $sectionId = Yii::$app->request->get('sid');
      if($sectionId){
        $searchModel->group_id = $sectionId;
      }
      $searchModel->fk_branch_id = Yii::$app->common->getBranch();
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

      return $this->render('index', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'model'=>$model,
        ]);
    }
  }


  public function actionGetSearch(){
    if(Yii::$app->user->isGuest){
      return $this->goHome();
    }else{
      if(Yii::$app->request->isAjax){
        if(Yii::$app->request->isPjax){
          $data= Yii::$app->request->get();
        }else{
          $data= Yii::$app->request->post();
        }
        $stuModel = new StudentInfo();
        $input_id    = $data['getVal'];
        $getdropVal  = $data['getinput'];
       $class_val   = $data['classval'];
        $status      = $data['status'];
        if($status == 1){
          $word = 'active';
        }else{
          $word = 'inactive';
        } 
        if($getdropVal == 'contact'){
              $StudentQuery = StudentParentsInfo::find()
              ->select(['student_parents_info.*','student_info.fk_branch_id'])
              ->innerJoin('student_info','student_info.stu_id = student_parents_info.stu_id')
              ->where(['student_parents_info.contact_no'=>$input_id,'student_info.fk_branch_id'=>yii::$app->common->getBranch(),'student_info.is_active'=>1]);
                  }else if($getdropVal == 'reg'){
                   $where = "username like '%$input_id%'  and fk_role_id = 3 and fk_branch_id=".Yii::$app->common->getBranch()." and status='".$word."'";
                   $StudentQuery = User::find()
                   ->where($where)
                  //  ->orderBy(['id'=>SORT_DESC]);
                   ->orderBy(['username'=>SORT_ASC]);
                 }
                 else if($getdropVal == 'name'){
                   $where = "first_name like '%$input_id%' and fk_branch_id='".Yii::$app->common->getBranch()."' and fk_role_id = 3  and status='".$word."'";
                   $StudentQuery = User::find()
                   ->where($where)
                   ->orderBy(['username'=>SORT_ASC]);
                 }
                 else if($getdropVal == 'class'){
                  $where = "class_id = '$class_val' and fk_branch_id='".Yii::$app->common->getBranch()."' and is_active=$status";
                  $StudentQuery = StudentInfo::find()
                  ->where($where)
                  ->orderBy(['user_id'=>SORT_ASC]);
                }else if($getdropVal == 'alumni'){
                  $where = "class_id = '$class_val' and branch_id='".Yii::$app->common->getBranch()."'";
                  $StudentQuery =StudentLeaveInfo::find()
                  ->where($where);
                }
                else if($getdropVal == 'overall'){
                  $where = "fk_branch_id='".Yii::$app->common->getBranch()."' and is_active=$status";
                  $StudentQuery = StudentInfo::find()
                  ->where($where)
                  ->orderBy(['user_id'=>SORT_ASC]);
                }
                $dataprovider = new ActiveDataProvider([
                  'query' => $StudentQuery,
                  'pagination' => [
                  'pageSize' => 5000,
                  ],
                           ]);
                $details = $this->renderAjax('getsearch',[
                  'dataprovider'=>$dataprovider,
                  'model'       => $stuModel,
                  'input_id'    => $input_id,
                  'getdropVal'  => $getdropVal,
                  'class_val'   => $class_val,
                  'status'=>$status,
                  ]);
                return json_encode(['status'=>1 ,'details'=>$details]);
              }
            }
          }
          public function actionLeaveInfo(){
            // $stu_id=yii::$app->request->post('stu_id');
            $enroll_class=yii::$app->request->post('enroll_class');
            $stu_id=yii::$app->request->post('pass_stu_id');
            $remarks=yii::$app->request->post('remarks');
            $nextSchool=yii::$app->request->post('nextSchool');
            $reason=yii::$app->request->post('reason');
            $getstuid=studentInfo::find()->where(['stu_id'=>$stu_id])->one();
            $clasId=$getstuid->class_id;
            $grpd=$getstuid->group_id;
            $sctnid=$getstuid->section_id;
            $newmodel= new StudentLeaveInfo();
            $newmodel->stu_id=$stu_id;
            $newmodel->enrollment_class=$enroll_class;
            $newmodel->remarks=$remarks;  
            $newmodel->branch_id=yii::$app->common->getBranch();  
            $newmodel->next_school=$nextSchool;
            $newmodel->reason_for_leavingschool=$reason;
            $newmodel->created_date=date("Y-m-d",strtotime(yii::$app->request->post('date')));
            $newmodel->class_id=$clasId;
            $newmodel->group_id=$grpd;
            $newmodel->section_id=$sctnid;
            $newmodel->save(false);
             // print_r($newmodel->getErrors());die;
           
            $studentModel = studentInfo::findOne($stu_id);
            $studentModel->is_active = 0;
            $studentModel->school_leave=1;
            // $studentModel->save();
            $studentModel->save(false);
              //print_r($studentModel->getErrors());die;
           
            $userid=$getstuid->user_id;
            $user = User::findOne($userid);
            $user->status = 'inactive';
            $user->save(false);
              //print_r($user->getErrors());die;
            Yii::$app->session->setFlash('success', 'Success.');
          }
          public function actionDelete($id){
          $studentModel= StudentInfo::findOne($id);
          $studentModel->delete();
          $studentParentsModel=StudentParentsInfo::findOne(['stu_id'=>$id]);
          $studentParentsModel->delete();
          $StudentLeaveInfoModel = StudentLeaveInfo::findOne(['stu_id'=>$id]);
          $userid=$studentModel->user_id;
          $userModel = User::findOne($userid);
          $userModel->delete();
          Yii::$app->session->setFlash('success', 'Student has been successfully deleted.. this student will not be undo.');
          $this->redirect(['index']);
          }
     public function actionNames(){
       $id=yii::$app->request->post('stu_id');
       Yii::$app->response->redirect(['student/leaving-pdf','id' => $id]);
     }
     public function actionLeavingPdf(){
      $id=yii::$app->request->get('id');
      $chalan_html = $this->renderPartial('leaving-pdf', ['id'=>$id]);
      $this->layout = 'pdf';
      $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
      $mpdf->AddPage();
      $mpdf->WriteHTML($chalan_html);
      $mpdf->Output('Student-challan.pdf', 'D');
    }
    public function actionGetSearchPdf(){
     $class_val= Yii::$app->request->get('classval');
     $thisinputname= Yii::$app->request->get('inputhid');
     $parntclass= Yii::$app->request->get('parntclass');
     $inputclass= Yii::$app->request->get('inputclass');
     $grpclass= Yii::$app->request->get('grpclass');
     $sectinclass= Yii::$app->request->get('sectinclass');
     $dobclass= Yii::$app->request->get('dobclass');
     $regclass= Yii::$app->request->get('regclass');
     $adrsclass= Yii::$app->request->get('adrsclass');
     $classcntct= Yii::$app->request->get('classcntct');
     $StudentQueryPdf = StudentInfo::find()
     ->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'is_active'=>1])
     ->andWhere(['like','class_id',$class_val])
     ->all();
     $classname=RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'class_id'=>$class_val])->one();
     if(!empty($classname->title)){$clsname=$classname->title;}else{};
                  $viewx=$this->renderAjax('class-pdf',['classname'=>$classname,'dataprovider'=>$StudentQueryPdf,'thisinputname'=>$thisinputname,'parntclass'=>$parntclass,'inputclass'=>$inputclass,'grpclass'=>$grpclass,'sectinclass'=>$sectinclass,'dobclass'=>$dobclass,'regclass'=>$regclass,'adrsclass'=>$adrsclass,'classcntct'=>$classcntct]);
                 $this->layout = 'pdf';
                 $mpdf = new mPDF();
                 $mpdf->WriteHTML($viewx);
                 $mpdf->Output('class-pdf-'.date("d-m-Y").'.pdf', 'D');
               }
    public function actionGetAlumniPdf(){
     $class_val= Yii::$app->request->get('classval');
     $classname=StudentLeaveInfo::find()->where(['branch_id'=>Yii::$app->common->getBranch(),'class_id'=>$class_val])->all();
     $viewx=$this->renderAjax('alumni-pdf',['classname'=>$classname,'class_val'=>$class_val]);
     $this->layout = 'pdf';
     $mpdf = new mPDF();
     $mpdf->WriteHTML($viewx);
     $mpdf->Output('alumni-pdf-'.date("d-m-Y").'.pdf', 'D');
   }

    public function actionGetSearchnamePdf(){
     $input_id= Yii::$app->request->get('getVal');
     $getdropVal= Yii::$app->request->get('getinput');
     $thisinputname= Yii::$app->request->get('inputhid');
     $parntclass= Yii::$app->request->get('parntclass');
     $inputclass= Yii::$app->request->get('inputclass');
     $grpclass= Yii::$app->request->get('grpclass');
     $sectinclass= Yii::$app->request->get('sectinclass');
     $dobclass= Yii::$app->request->get('dobclass');
     $regclass= Yii::$app->request->get('regclass');
     $adrsclass= Yii::$app->request->get('adrsclass');
     $classcntct= Yii::$app->request->get('classcntct');
     if($getdropVal == 'reg'){
      $StudentQueryPdfreg = User::find()
      ->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active','fk_role_id'=>3])
      ->andWhere(['like','username',$input_id])
      ->orderBy(['id'=>SORT_DESC])->all();
      $viewxxreg=$this->renderAjax('class-pdf-reg',['dataproviders'=>$StudentQueryPdfreg,'thisinputname'=>$thisinputname,'parntclass'=>$parntclass,'inputclass'=>$inputclass,'grpclass'=>$grpclass,'sectinclass'=>$sectinclass,'dobclass'=>$dobclass,'regclass'=>$regclass,'adrsclass'=>$adrsclass,'classcntct'=>$classcntct]);
      $this->layout = 'pdf';
      $mpdf = new mPDF();
      $mpdf->WriteHTML($viewxxreg);
      $mpdf->Output('class-pdf-'.date("d-m-Y").'.pdf', 'D');
    }else{
      $StudentQueryPdfName = User::find()
      ->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active','fk_role_id'=>3])
      ->andWhere(['like','first_name',$input_id])
      ->orderBy(['id'=>SORT_DESC])->all();
      $viewxx=$this->renderAjax('class-pdf-name',['dataprovider'=>$StudentQueryPdfName,'thisinputname'=>$thisinputname,'parntclass'=>$parntclass,'inputclass'=>$inputclass,'grpclass'=>$grpclass,'sectinclass'=>$sectinclass,'dobclass'=>$dobclass,'regclass'=>$regclass,'adrsclass'=>$adrsclass,'classcntct'=>$classcntct]);
      $this->layout = 'pdf';
      $mpdf = new mPDF();
      $mpdf->WriteHTML($viewxx);
      $mpdf->Output('name-pdf-'.date("d-m-Y").'.pdf', 'D');
    }
  }
     /*   end of get student acording to sesssion and class*/

    public function actionGetStudents($id)
    {
      if(Yii::$app->user->isGuest){
        return $this->goHome();
      }else{
        $searchModel = new StudentInfoSearch();

        $searchModel->fk_branch_id = Yii::$app->common->getBranch();
        $searchModel->section_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('getStudents', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        ]);
     }
   }

    // start of calendar
            public function actionSaveStuId(){
              $ids=Yii::$app->request->post('emp_is');
              $dat=Yii::$app->request->post('d');
              $dats= date('Y-m-d',strtotime($dat));
              $query=StudentAttendance::find()->where(['fk_stu_id'=>$ids])
              ->andWhere(['between', 'date', $dats.' 00:00:00', $dats.' 23:59:59'])
              ->one();
              $query1=StudentAttendance::find()->where(['fk_stu_id'=>$ids])
              ->andWhere(['between', 'date', $dats.' 00:00:00', $dats.' 23:59:59']);
              $provider = new ActiveDataProvider([
                'query' => $query1,
                ]);
              if($query){
               $getVal= $this->renderAjax('getdetails', ['passvalue' => $provider]);
               return json_encode(['type'=>$query->leave_type,'remarks'=>$query->remarks,'newprovide'=>$getVal]);
             }else{
              return 'false';
            }

    }//end of empId
    public function actionCalendar(){
      $model = new Exam();
      $model2 = new StudentAttendance();
      return $this->render('Studentcalendar', [
        'attendanceModel' => $model2,
        'model' => $model,
        ]);
    }
    public function actionStuCalendar(){
      $model = new Exam();
      $model2 = new StudentAttendance();
      return $this->render('calendar-firstpage', [
        'attendanceModel' => $model2,
        'model' => $model,
        ]);
    }
    public function actionGetStuCalendar(){
            if(Yii::$app->user->isGuest){
              return $this->goHome();
            }else{
              if(Yii::$app->request->isAjax){
                $stuModel = new StudentInfo();
                $model = new StudentAttendance();
                $data= Yii::$app->request->post();
                $class_id = $data['class_id'];
                $group_id = $data['group_id'];
                $section_id = $data['section_id'];
                /*query*/
                $StudentQuery = StudentInfo::find()
                ->select(['class_id','group_id','section_id'])
                ->where([
                  'fk_branch_id'  =>Yii::$app->common->getBranch(),
                  'class_id'   => $class_id,
                  'group_id'   => ($group_id)?$group_id:null,
                  'section_id' => $section_id,
                  ])->groupBy(['class_id','group_id','section_id']);
                $dataprovider = $StudentQuery->all();
                if(count($dataprovider)>0){
                $details = $this->renderAjax('get-stu-data',[ //get-stu-data(old)
                  'dataprovider'=>$dataprovider,
                  'model'=>$stuModel,
                  'model'=>$model,
                  'class_id'=>$class_id,
                  'group_id'=>$group_id,
                  'section_id'=>$section_id
                  ]);
              }else{
                $details="<div class='col-md-6'><div class='Alert alert-warning'><strong><center> No Student found in this section !</center></strong></div> </div>";
              }

              return json_encode(['status'=>1 ,'details'=>$details]);
            }
          }
        }

   public function actionParentCnic(){
    $cnic=yii::$app->request->post('cnic');
    $branch=yii::$app->request->post('branch');
    $getParentCnic=yii::$app->db->createCommand("
      select st.stu_id as `student id`,concat(u.first_name,' ',u.middle_name,' ' ,u.last_name) as `student_name`, st.class_id as `class_id`,rc.title as `class_name`,st.group_id as `group id`,rg.title as `group_name`,st.section_id as `section_id`,rs.title as `section_name` from student_info st inner join student_parents_info spi on spi.stu_id=st.stu_id inner join ref_class rc on rc.class_id = st.class_id left join ref_group rg on rg.group_id=st.group_id left join ref_section rs on rs.section_id=st.section_id inner join user u on u.id=st.user_id where spi.cnic='".$cnic."' and st.fk_branch_id='".$branch."' and st.is_active='1'
      ")->queryAll();
      if(count($getParentCnic)>0){
        $renderViews=$this->renderAjax('parent-cnic',['parntcnic'=>$getParentCnic,'pcnic'=>$cnic]);
      }else{
        $renderViews = 'Sibling Not Found';
      }
      return json_encode(['viewtabl'=>$renderViews]);
     }

  public function actionSaveLeave(){
   $post_date= date('Y-m-d',strtotime($_POST['getDate']));
    $post_stu=$_POST['student'];
   $postName=$_POST['nameStu'];
   $exists=StudentAttendance::find()->where(['fk_stu_id'=>$post_stu])
   ->andWhere(['between', 'date', $post_date.' 00:00:00', $post_date.' 23:59:59'])
   ->one();
   $getparentcontact=StudentParentsInfo::find()->select('contact_no')->where(['stu_id'=>$post_stu])->one();
   $smssettings=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
   $sendParentContact=$getparentcontact->contact_no;
   if(count($exists)>0){
    $model = StudentAttendance::findOne($exists->id);
  }else{
    $model = new StudentAttendance();
  }
  $stud_id=$model->fk_stu_id=$_POST['student'];
  $model->leave_type=$_POST['select'];
  $model->remarks=$_POST['remark'];
  $model->date=$_POST['getDate'];
  $model->class_id=$_POST['pasclasid'];
  $model->group_id=$_POST['pasgroup_id'];
  $model->section_id=$_POST['passection_id'];
  $msg='Respectfull Sir..! Your Child '.$postName.' '.$_POST['select'].' today';
  if($model->save()){
    $get_leave_type=$model->leave_type;
    if($get_leave_type == 'present'){}else{
      if($smssettings->status == 1){
              Yii::$app->common->SendSms($sendParentContact,$msg,$post_stu);
           }
         }
    if($get_leave_type == 'absent'){
                echo '<span class="label label-danger">A</span>';
            }else if($get_leave_type == 'leave'){
                echo '<span class="label label-warning">L</span>';
            }else if($get_leave_type == 'late'){
               echo '<span class="label label-info">LT</span>';
            }else if($get_leave_type == 'Latewithexcuse'){
              echo '<span class="label label-info">LE</span>';   
            }else if($get_leave_type == 'shortleave'){
             echo '<span  class="label btn-vk">SL</span>';
            }else if($get_leave_type == 'present'){
                echo '<span style="color:green">P</span>';
            }
            }else{
              print_r($model->getErrors());
            }
            }

    // end of calendar

    /**
     * Displays a single StudentInfo model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
      $model2=StudentEducationalHistoryInfo::findOne(['stu_id'=>$id]);

      if(Yii::$app->user->isGuest){
        return $this->goHome();
      }else {
        return $this->render('view', [
          'model' => $this->findModel($id),
          'model2'=>$model2
          ]);
      }
    }

    /**
     * Creates a new StudentInfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionGetClass(){
      $id=Yii::$app->request->post('id');
      $class=RefClass::find()->where(['fk_session_id'=>$id,'fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all();
      echo "<option selected='selected'>Select Class</option>";
      foreach($class as $class)
      {
        echo "<option value='".$class->class_id."'>".$class->title."</option>";
      }
    }//end of class
    //========= get route ==============//
    public function actionGetRoute(){
      $id=Yii::$app->request->post('id');
      $route=Route::find()->where(['fk_zone_id'=>$id])->all();
      echo "<option>Select Route</option>";
      foreach ($route as $route) {
        echo "<option value='".$route->id."'>".$route->title."</option>";
      }

    }   // ===============end of route and start of stop======//

    public function actionGetStop(){
      $id=Yii::$app->request->post('id');
      $stop=Stop::find()->where(['fk_route_id'=>$id])->all();
      echo "<option value=''>Select Stop</option>";
      foreach ($stop as $stop) {
        echo "<option value='".$stop->id."'>".$stop->title."</option>";
      }

    }   // ===============end of route======//

    // country
    public function actionCountry(){
      $id=Yii::$app->request->post('id');
      $provinces=RefProvince::find()->where(['country_id'=>$id])->all();
      echo "<option selected='selected'>Select Provinces</option>";
      foreach($provinces as $province)
      {
        echo "<option value='".$province->province_id."'>".$province->province_name."</option>";
      }
    }//end of country

    public function actionProvince(){
      $id=Yii::$app->request->post('id');
      $District=RefDistrict::find()->where(['province_id'=>$id])->all();
      echo "<option selected='selected'>Select District</option>";
      foreach($District as $district)
      {
        echo "<option value='".$district->district_id."'>".$district->District_Name."</option>";
        } }//end of Province

        public function actionDistrict(){

          $id=Yii::$app->request->post('id');
          $city=RefCities::find()->where(['district_id'=>$id])->all();

          echo "<option selected='selected'>Select City</option>";
          foreach($city as $city)
          {

            echo "<option value='".$city->city_id."'>".$city->city_name."</option>";

          }


    }//end of District

    //======== country 2 for admission============//
    public function actionCountry2(){

      $id=Yii::$app->request->post('id');
      $provinces=RefProvince::find()->where(['country_id'=>$id])->all();

      echo "<option selected='selected'>Select Provinces</option>";
      foreach($provinces as $province)
      {

        echo "<option value='".$province->province_id."'>".$province->province_name."</option>";

      }


    }//end of country

    public function actionProvince2(){
      $id=Yii::$app->request->post('id');
      $District=RefDistrict::find()->where(['province_id'=>$id])->all();
      echo "<option selected='selected'>Select District</option>";
      foreach($District as $district)
      {
        echo "<option value='".$district->district_id."'>".$district->District_Name."</option>";
        } }//end of Province

        public function actionDistrict2(){

          $id=Yii::$app->request->post('id');
          $city=RefCities::find()->where(['district_id'=>$id])->all();

          echo "<option selected='selected'>Select City</option>";
          foreach($city as $city)
          {

            echo "<option value='".$city->city_id."'>".$city->city_name."</option>";

          }


    }//end of District

    //======== end of country 2 for admission============//

    public function actionGroup(){
      $id=Yii::$app->request->post('id');
      $group=RefGroup::find()->where(['fk_class_id'=>$id])->all();
      $options = "<option selected='selected'>Select Group</option>";
      foreach($group as $group)
      {
        $options .= "<option value='".$group->group_id."'>".$group->title."</option>";
      }
      return $options;
    }//end of group

    public function actionSection(){
      $id=Yii::$app->request->post('id');
      $section=RefSection::find()->where(['fk_group_id'=>$id])->all();
      echo "<option selected='selected'>Select Section</option>";
      foreach($section as $section)
      {
        echo "<option value='".$section->section_id."'>".$section->title."</option>";
        }}//end of Section


        /*  =====================  get bed of room ===== */
        public function actionGetHostelFloor(){
          $id=Yii::$app->request->post('id');
          $floor=HostelFloor::find()->where(['fk_hostel_info_id'=>$id])->all();
          echo "<option selected='selected'>Select Floor</option>";
          foreach($floor as $floor)
          {
            echo "<option value='".$floor->id."'>".$floor->title."</option>";
          } }

          public function actionGetFloorRoom(){
            $id=Yii::$app->request->post('id');
            $floorRoom=HostelRoom::find()->where(['fk_FLOOR_id'=>$id])->all();
            echo "<option selected='selected'>Select Room</option>";
            foreach($floorRoom as $floorRoom)
            {
              echo "<option value='".$floorRoom->id."'>".$floorRoom->title."</option>";
            }
          }

          public function actionGetBed(){
            $id=Yii::$app->request->post('id');
            $bed=HostelBed::find()->where(['fk_room_id'=>$id])->all();
            echo "<option selected='selected' value=''>Select Bed</option>";
            foreach($bed as $bed)
            {
              echo "<option value='".$bed->id."'>".$bed->title."</option>";
            } }

            /*  =====================  end of get bed of room ===== */
            /*start of admission*/
            /*start of admission*/
           
            public function actionAdmission(){
             if (Yii::$app->user->isGuest) {
               return $this->goHome();
             }
             $feePlanArray=yii::$app->request->post('FeePlan');
             $headDiscount=yii::$app->request->post('head_hidden_discount_amount');
             $headDiscountType=yii::$app->request->post('head_hidden_discount_type');
            // echo '<pre>';print_r($feePlanArray);
            // echo '<pre>';print_r($headDiscountType);
            // die;
             //$headDiscountType=yii::$app->request->post('head_hidden_discount_type');
               ///echo '<pre>';print_r($headDiscountType);die;
                 $p_c=yii::$app->request->post('StudentParentsInfo');
                 $settings = Yii::$app->common->getBranchSettings();
                 $prnt_cnic=$p_c['cnic'];
                 $model     = new StudentInfo();
                 $FeePlan   = new FeePlan();
                 $model->scenario = 'admission';
                 $model2    = new StudentParentsInfo();
                 $userModel = new User();
                 $StudentEducationalHistoryInfo= new StudentEducationalHistoryInfo();
                 $smssettings=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
                 $model->gender_type=1;
                 $model2->gender_type=1;
                 $model->parent_status=1;
        //$model->fk_stop_id=0;
             $model->is_hostel_avail=0;
                $cnic_count=0;
             $row=[];
             $head_wise=[];
             $studentInfoData   = Yii::$app->request->post('StudentInfo');
             $parentsData       = Yii::$app->request->post('StudentParentsInfo');
             $studentFinance    = Yii::$app->request->post('StudentDisount');
             $studentHeadDescAmt    = Yii::$app->request->post('head_hidden_discount_amount');
             $studentHeadDescTyp    = Yii::$app->request->post('head_hidden_discount_type');
             $studentHeadAmt    = Yii::$app->request->post('head_hidden_amount');
            // $stu_total_fee     = $studentInfoData['student_fee_total_amount'];
           // $stu_fee_plan      = $studentInfoData['fk_fee_plan_type'];
           $stu_fee_plan      = 1;
            //$stu_fee_plan      = $feeDetailArray['fk_fee_plan_type'];
             $userRegstr        = Yii::$app->request->post('User');
             $userRegester      = $userRegstr['username'];
             $first_name      = $userRegstr['first_name'];
             $full_name      = $userRegstr['first_name'] .' '. $userRegstr['last_name'];
            // $frst_name=str_replace(' ', '%20', $first_name);
             $stringName = str_replace(' ', '', $userRegester);
             $schoolName=Yii::$app->common->getBranchDetail()->name;
             $stringSchoolName = str_replace(' ', '', $schoolName);
             $branch_std_counter = User::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'fk_role_id'=>3])->count();
             /*$branch_std_counter = User::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'fk_role_id'=>3])->orderBy(['id'=>SORT_DESC])->one();*/
             $parentContact= $parentsData['contact_no'];
             $count = count($parentsData['first_name']);
             /*load data into User model.*/
             if($userModel->load(Yii::$app->request->post())){
          $file =UploadedFile::getInstance($userModel, 'Image');
          if($file){
            $userModel->Image=$file;
          }
          /*generate passoword*/
           $password=Yii::$app->common->getBranchDetail()->password;
          $random_password= Yii::$app->getSecurity()->generateRandomString($length = 7);
            $random_password=$password;
            $userModel->setPassword($random_password);
            $userModel->generateAuthKey();
            $userModel->fk_role_id= 3;
            $userModel->status='active';
            $userModel->username=$stringName;
            $userModel->fk_branch_id= Yii::$app->common->getBranch();
            if($userModel->save()){
              if(!empty($file)){
                $file->saveAs(\Yii::$app->basePath . '/web/uploads/'.$file);
              }
              /*load data into student model.*/
              if($model->load(Yii::$app->request->post())) {
                      // echo '<pre>';print_r($_POST);die;
                $model->fk_branch_id    = Yii::$app->common->getBranch();
                $stu_id=$model->user_id         = $userModel->id;
                $model->is_active       = 1; 
                $model->fk_fee_plan_type=1;
                $model->fee_generation_date  = date('Y-m-d');
                $model->monthly_fee_gen_date = date('Y-m-d');
                $model->fk_ref_province_id2 = $_POST['StudentInfo']['fk_ref_province_id3'];

                  /*check sibling discount is applicable or not starts*/
                  if(!empty($prnt_cnic)){
                      $cnic_count = StudentParentsInfo::find()
                          ->select(['student_parents_info.stu_id','si.fk_branch_id','student_parents_info.stu_parent_id'])
                          ->innerJoin('student_info si','si.stu_id = student_parents_info.stu_id')
                          ->where(['si.fk_branch_id' => Yii::$app->common->getBranch(),'student_parents_info.cnic'=>$prnt_cnic])
                          ->count();
                      /*if sibling is more than provided in settings*/
                      if(($cnic_count+1) >= $settings->sibling_no_childs){
                           $model->avail_sibling_discount=1;
                      }
                  }
                  /*check sibling discount is applicable or not ends*/
                if($model->save()){
                  /* save education history */
                  if ($StudentEducationalHistoryInfo->load(Yii::$app->request->post())){
                    $StudentEducationalHistoryInfo->stu_id=$model->stu_id;
                    if($StudentEducationalHistoryInfo->save()){}else{print_r($StudentEducationalHistoryInfo->getErrors());die;}
                  }

                  /* end of save education history*/
                  /*load data into student parent model.*/
                  if ($model2->load(Yii::$app->request->post())){

                    $model2->stu_id=$model->stu_id;
                                  if($model2->save()){
                                    /*fee details save*/
                                          if ($FeePlan->load(Yii::$app->request->post())){
                                            $feeHeadIdArray=$feePlanArray['fee_head_id'];
                                            $headDiscount=$feePlanArray['dicount'];
                                            if(empty($headDiscount)){
                                              $headDiscountArray=0;
                                            }else{
                                            $headDiscountArray=$headDiscount;
                                            }
                                            foreach ($feeHeadIdArray as $key => $feeplankeys) {
                                             $FeePlanmodels= new FeePlan();
                                             $FeePlanmodels->stu_id=$model->stu_id;
                                             $FeePlanmodels->fee_head_id=$feeplankeys;
                                             if(empty($headDiscountArray[$key])){
                                            continue;
                                             }else{
                                              $FeePlanmodels->discount=$headDiscountArray[$key];
                                             }
                                            // echo $key;
                                             $FeePlanmodels->fk_fee_discounts_type_id=$headDiscountType[$feeplankeys];
                                              
                                             $FeePlanmodels->branch_id=yii::$app->common->getBranch();
                                             if($FeePlanmodels->save()){
                                            }else{
                                              print_r($FeePlanmodels->getErrors());die;
                                            }
                                           } //end of foreach 
                                           }
                                          /*end of fee details save*/
                                    $settingQuery=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
                                    $mesgControl =\app\models\MessageControl::find()->where(['branch_id'=>Yii::$app->common->getBranch(),'message_id'=>'admission'])->One();
                                    $schoolName=strtolower($settingQuery->school_name);
                                    $msg=$mesgControl->message.'<br> '.$full_name. ' Login is '.$stringName.' and password is '.$password.',(http://33344450.com/'.$schoolName.')';
                                    if($smssettings->status == 1){
                                    Yii::$app->common->SendSms($parentContact,$msg,$stu_id);
                                    } 
                                    /*end of sms */
                                    
                                  }else{
                                    //echo "<pre>";print_r($model2->getErrors());die;
                                    return $this->render('admission', [
                                      'model'        => $model,
                                      'FeePlan'        => $FeePlan,
                                      'model2'       => $model2,
                                      'userModel'    => $userModel,
                                      'StudentEducationalHistoryInfo' => $StudentEducationalHistoryInfo,
                                      'branch_std_counter'=>$branch_std_counter

                                      ]);
                                }//end of model2 else
                              }
                            Yii::$app->session->setFlash('success', 'Student has been save successfully.');
                            $this->redirect(['student/profile', 'id' => $model->stu_id,'form_id'=>1]);
                            
                          }
                          else{
                            //echo "<pre>";
                            //print_r($model->getErrors());die;
                            return $this->render('admission', [
                              'model'        => $model,
                              'FeePlan'        => $FeePlan,
                              'model2'       => $model2,
                              'userModel'    => $userModel,
                              'StudentEducationalHistoryInfo' => $StudentEducationalHistoryInfo,
                              'branch_std_counter'=>$branch_std_counter

                              ]);
                          }
                        }
                        else{
                          //echo "<pre>";print_r($model->getErrors());  exit;
                          return $this->render('admission', [
                            'model'        => $model,
                            'FeePlan'        => $FeePlan,
                            'model2'       => $model2,
                            'userModel'    => $userModel,
                            'StudentEducationalHistoryInfo' => $StudentEducationalHistoryInfo,

                            ]);
                        }
                      }
                      else {
                        /*print_r($userModel->getErrors());die;*/
                        //echo "<pre>";print_r($userModel->getErrors());  exit;
                        return $this->render('admission', [
                          'model' => $model,
                          'FeePlan' => $FeePlan,
                          'model2' => $model2,
                          'userModel' => $userModel,
                          'StudentEducationalHistoryInfo' => $StudentEducationalHistoryInfo,
                          'branch_std_counter'=>$branch_std_counter

                          ]);
                      }
                    }
                    else{
           // print_r($userModel->getErrors());die;
                      return $this->render('admission', [
                        'model' => $model,
                        'FeePlan' => $FeePlan,
                        'model2' => $model2,
                        'userModel' => $userModel,
                        'StudentEducationalHistoryInfo' => $StudentEducationalHistoryInfo,
                        'branch_std_counter'=>$branch_std_counter

                        ]);
                    }
                  }
            /*end of admission*/
    /**
     * Updates an existing StudentInfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
      if (Yii::$app->user->isGuest) {
        return $this->goHome();
      }
        //echo '<pre>';print_r($_POST);die;
      $model      = $this->findModel($id);
//      $model->avail_sibling_discount=1;
      $model2     = StudentParentsInfo::find()->where(['stu_id'=>$id])->one();
      $userModel  = User::find()->where(['id'=>$model->user_id])->one();

      $studentEducationQuery=StudentEducationalHistoryInfo::find()->where(['stu_id'=>$id])->one();
      if(count($studentEducationQuery)>0){
       $StudentEducationalHistoryInfo = StudentEducationalHistoryInfo::findOne($studentEducationQuery->edu_history_id);
         }else{
       $StudentEducationalHistoryInfo = new StudentEducationalHistoryInfo();
              }
     
    $old_image=$userModel->Image;
    $branch_std_counter = User::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'fk_role_id'=>3])->count();

    /*load data into student model. model*/
    if ($model->load(Yii::$app->request->post())) {
           
      if($model->save()) {
        $p_c = yii::$app->request->post('StudentParentsInfo');
        $parentsData = Yii::$app->request->post('StudentParentsInfo');
        $count = count($parentsData['first_name']);
        if ($userModel->load(Yii::$app->request->post())) {
          if (!empty($_FILES['User']['name']['Image'])) {
            $file = UploadedFile::getInstance($userModel, 'Image');
            $pth= Yii::$app->basePath . '/web/uploads/'.$old_image;
            $file->saveAs(\Yii::$app->basePath . '/web/uploads/' . $file,false);
            $userModel->Image = $file;
            if(!empty($old_image)){
              if($old_image != $file)
               unlink($pth);
            }
          } else {
            $userModel->Image = $old_image;
          }
          if ($userModel->save()) {
            /*laod data to student parent model*/
            if ($model2->load(Yii::$app->request->post())) {
            if ($StudentEducationalHistoryInfo->load(Yii::$app->request->post())) {
                if($StudentEducationalHistoryInfo->save()){

                }else{
                  return $this->render('update', [
                                  'model' => $model,
                                  'model2' => $model2,
                                  'userModel' => $userModel,
                                  'branch_std_counter'=>$branch_std_counter,
                                  'StudentEducationalHistoryInfo'=>$StudentEducationalHistoryInfo,
                                  ]);
                  //print_r($StudentEducationalHistoryInfo->getErrors());die;
                }
               }

                            // $p_cx=$prnt_cnic;
              $stu_ids = $model->stu_id;
              $userModel->fk_branch_id = Yii::$app->common->getBranch();
                  
                                      if ($model2->save()) {
                                      } else {
                                        echo "<pre>";
                                        print_r($model2->getErrors());
                                        exit;
                                      }
                                      return $this->redirect(['profile', 'id' => $model->stu_id]);
                                    }
                                  } else {
                        //print_r($userModel->getErrors());die;
                                    return $this->render('update', [
                                  'model' => $model,
                                  'model2' => $model2,
                                  'userModel' => $userModel,
                                  'branch_std_counter'=>$branch_std_counter,
                                  'StudentEducationalHistoryInfo'=>$StudentEducationalHistoryInfo,
                                  ]);
                                    return $this->render('update', [
                                      'model' => $model,
                                      'model2' => $model2,
                                      'userModel' => $userModel,
                                      'branch_std_counter' => $branch_std_counter,
                                      'StudentEducationalHistoryInfo' => $StudentEducationalHistoryInfo,
                                      ]);
                                  }
                                }
                              }
                              else {
                                return $this->render('update', [
                                  'model' => $model,
                                  'model2' => $model2,
                                  'userModel' => $userModel,
                                  'branch_std_counter'=>$branch_std_counter,
                                  'StudentEducationalHistoryInfo'=>$StudentEducationalHistoryInfo,
                                  ]);
                              }
                            }
                            else {
                              return $this->render('update', [
                                'model' => $model,
                                'model2' => $model2,
                                'userModel' => $userModel,
                                'branch_std_counter'=>$branch_std_counter,
                                'StudentEducationalHistoryInfo'=>$StudentEducationalHistoryInfo,

                                ]);
                            }
                          } //end of update
     public function actionDownload()
    {
      return $this->render('download');
    }
    public function actionDownloadForm($id)
    {
      if (Yii::$app->user->isGuest) {
        return $this->goHome();
      }
      $id=base64_decode($id);
      $student_info=StudentInfo::find()->where(['user_id'=>$id])->one();
        //echo '<pre>';print_r($_POST);die;
      $model      = $this->findModel($student_info->stu_id);
      $parents_details= StudentParentsInfo::find()->where(['stu_id'=>$student_info->stu_id])->one();
      $profession_details= \app\models\Profession::find()->where(['id'=>$parents_details->profession])->one();
      $TransportAllocation= \app\models\TransportAllocation::find()->where(['stu_id'=>$model->user_id])->one();
      $StudentEducationalHistoryInfo= \app\models\StudentEducationalHistoryInfo::find()->where(['stu_id'=>$model->stu_id])->one();
      $formView= $this->renderAjax('download-form',['model'=>$model,'parents_details'=>$parents_details,'profession_details'=>$profession_details,'TransportAllocation'=>$TransportAllocation,'StudentEducationalHistoryInfo'=>$StudentEducationalHistoryInfo]);
    //   echo $formView;die;
      //$this->layout = 'pdf';
      $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
      $stylesheet = file_get_contents('css/schoolForm.css');
      $mpdf->WriteHTML($stylesheet,1);
      $mpdf->WriteHTML($formView);
      $mpdf->Output('download-form-'.date("d-m-Y").'.pdf', 'I');

    }//end of download form


    /**
     * Deletes an existing StudentInfo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    
    public function actionInactive($id){
        //echo $id;
      $model= User::findOne($id);
        //$model2= StudentInfo::findOne($id);
        //print_r($model);
      $model->status = 'inactive';
       // $model2->is_active = '0';
      $model->save();
      if (!Yii::$app->request->isAjax) {
        return $this->redirect(['index']);
      }
    }
    protected function findModel($id)
    {
      if (($model = StudentInfo::findOne($id)) !== null) {
        return $model;
      } else {
        throw new NotFoundHttpException('The requested page does not exist.');
      }
    }

    public function actionGenerateDmcPdf(){
      if(Yii::$app->user->isGuest){
        return $this->redirect(['site/login']);
      }else{
        if(Yii::$app->request->post()){
          $data = Yii::$app->request->post();
          $student_id = $data['student_id'];
          $exam_type = $data['exam_type'];
          $stuModel = StudentInfo::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'stu_id'=>$student_id])->one();

                /*
                 * $subjects_data =  Yii::$app->db->createCommand('select  sb.id as subject_id,sb.title as `subject`,sum(ex.total_marks) as `total marks`, sum(ex.passing_marks) as `passing marks`,sum(sm.marks_obtained) as `marks obtained`from exam ex
                    inner join exam_type et on et.id=ex.fk_exam_type 
                    inner join ref_class c on c.class_id=ex.fk_class_id 
                    left join ref_group g on g.fk_class_id=ex.fk_class_id 
                    left join ref_section s on s.class_id=ex.fk_class_id 
                    inner join subjects sb on sb.id=ex.fk_subject_id 
                    left join student_marks sm on sm.fk_exam_id=ex.id 
                    inner join student_info st on st.stu_id=sm.fk_student_id 
                    inner join user u on u.id=st.user_id 
                    where et.id=5 and st.stu_id='.$stuModel->stu_id.'
                    GROUP by st.stu_id,et.type ,sb.title,sb.id')
                    ->queryAll();
                */
                    $subjects_data = Exam::find()
                    ->select([
                      'sb.id subject_id',
                      'sb.title subject',
                      'sum(exam.total_marks) total_marks',
                      'sum(exam.passing_marks) passing_marks',
                      'sum(sm.marks_obtained) marks_obtained'
                      ])
                    ->innerJoin('exam_type et','et.id=exam.fk_exam_type')
                    ->innerJoin('ref_class c','c.class_id=exam.fk_class_id')
                    ->leftJoin('ref_group g','g.fk_class_id=exam.fk_class_id ')
                    ->leftJoin('ref_section s','s.class_id=exam.fk_class_id ')
                    ->innerJoin('subjects sb','sb.id=exam.fk_subject_id')
                    ->leftJoin('student_marks sm','sm.fk_exam_id=exam.id ')
                    ->innerJoin('student_info st','st.stu_id=sm.fk_student_id')
                    ->innerJoin('user u','u.id=st.user_id')
                    ->where(['et.id'=>$exam_type, 'st.stu_id'=>$stuModel->stu_id])
                    ->groupBy(['st.stu_id','et.type','sb.title','sb.id'])->asArray()->all();



                    if(count($subjects_data)> 0){
                      return $this->render('generate-std-pdf', [
                        'model' => $stuModel,
                        'data'=>$data,
                        'subjects_data'=>$subjects_data
                        ]);

                    /*
                    $this->layout = 'pdf';
                    $mpdf=new mPDF('','A4');
                    $mpdf->WriteHTML($html);
                    $mpdf->Output();*/
                  }else{
                    Yii::$app->session->setFlash('warning', 'Student Subjects marks Not found.');
                    return $this->redirect(['student/']);

                  }

                }
                else{
                  return $this->redirect(['site/login']);
                }
              }
            }
            /*validate username*/
            public function actionValidateUsrname(){
              if(Yii::$app->user->isGuest){
                $this->redirect(['site/login']);
              }else{
                if(Yii::$app->request->isAjax){
                  $userModel = User::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'username'=>Yii::$app->request->post('username')])->count();             
                  return json_encode(['status'=>1,'detail'=>$userModel]);
                }
              }
            }

            /*promote student module.*/
            public function actionAction(){
              if(Yii::$app->user->isGuest){
                return $this->redirect(['site/login']);
              }else{
                $model = new StudentInfo();
            return $this->render('action', [
              'model' => $model,
              ]);
          }
        }
        public function actionDemote(){
              if(Yii::$app->user->isGuest){
                return $this->redirect(['site/login']);
              }else{
                $model = new StudentInfo();
            return $this->render('demote', [
              'model' => $model,
              ]);
          }
        }
        public function actionDemoteStu(){
          if(Yii::$app->user->isGuest){
            return $this->redirect(['site/login']);
          }else{
            if(Yii::$app->request->isAjax){
              $model = new StudentInfo();
              $data  =  Yii::$app->request->post();
              if(Yii::$app->request->post()){
                if($data['section_id']){
                  $class_id = $data['class_id'];
                  $group_id = $data['group_id'];
                  $section_id = $data['section_id'];
                  /*query*/
                  $query = StudentInfo::find()->where([
                    'fk_branch_id'  => Yii::$app->common->getBranch(),
                    'class_id'      => $class_id,
                    'group_id'      => ($group_id)?$group_id:null,
                    'section_id'    => $section_id,
                    'is_active'     => 1
                    ]);
                  $searchModel = new \app\models\search\StudentInfoSearch();

                        //$searchModel->patient_id = $post_data['pat_id'];
                        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                  $dataProvider= new ActiveDataProvider([
                    'query'=>$query,
                    'sort' => [
                    'defaultOrder' => [
                    'stu_id' => SORT_DESC
                    ]
                    ],
                    'pagination' => [
                    'pageSize' => 500,
                    'params' => [
                    'class_id'      => $class_id,
                    'group_id'      => ($group_id)?$group_id:null,
                    'section_id'    => $section_id,
                    ],
                    ]
                    ]);
                        // print_r(Yii::$app->request->queryParams);die;

                  $details =  $this->renderAjax('demote-student-list', [
                    'searchModel'   => $searchModel,
                    'dataProvider'  => $dataProvider,
                    'model'         =>$model,
                    ]);

                  return json_encode(['status'=>1 ,'details'=>$details]);
                }
                else{
                  return json_encode(['status'=>1,'details'=>'<div class="alert alert-warning">
                    <strong>Note!</strong>Record Not Found.</div>']);
                }
              }
              else{
                /*geting data on pagination.*/
                $post_data  = Yii::$app->request->get();
                $class_id   = $post_data['class_id'];
                $group_id   = $post_data['group_id'];
                $section_id = $post_data['section_id'];
                /*query*/
                $query = StudentInfo::find()->where([
                  'fk_branch_id'  => Yii::$app->common->getBranch(),
                  'class_id'      => $class_id,
                  'group_id'      => ($group_id)?$group_id:null,
                  'section_id'    => $section_id,
                  ]);
                $countQuery = clone $query;

                $pages = new \yii\data\Pagination(['totalCount' => $countQuery->count()]);
                $dataProvider = new ActiveDataProvider([
                  'query' => $query,
                  'sort' => [
                  'defaultOrder' => [
                  'stu_id' => SORT_DESC
                  ]
                  ]
                  ]);

                return $this->renderAjax('branch-students-list',
                  [
                  'dataProvider'  => $dataProvider,
                  'pages'         => $pages,
                  'model'         => $model,
                  ]);
              }
            }
          }
        }

        public function actionSaveDemotedStudent(){
          if(Yii::$app->user->isGuest){
            return $this->redirect(['site/login']);
          }else{
            if(Yii::$app->request->isAjax){
              $data = Yii::$app->request->post();
              $c_cid   =   $data['c_cid'];
              $c_gid   =   $data['c_gid'];
              $c_sid   =   $data['c_sid'];
              $selected_students  = $data['selected_students'];
              $new_cid = $data['new_cid'];
              $new_gid = $data['new_gid'];
              $new_sid = $data['new_sid'];

              /*promote individual studen loop*/
              foreach($selected_students as $key=>$student_id){
                $studentInfoModel = StudentInfo::findOne($student_id);
                $studentInfoModel->class_id = $new_cid;
                $studentInfoModel->group_id = ($new_gid)?$new_gid:null;
                $studentInfoModel->section_id = $new_sid;
                $studentInfoModel->save(false);

                $FeeStructure = \app\models\FeeGroup::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'fk_class_id'=>$studentInfoModel->class_id,'fk_group_id'=>($studentInfoModel->group_id)?$studentInfoModel->group_id:null])->all();

              }
              Yii::$app->session->setFlash('success','Students Demoted Successfully');
              return json_encode(['status'=>1,'returnUrl'=>\yii\helpers\Url::to(['student/demote','id'=>base64_encode('demote')],true)]);
            }
          }
        }
            public function actionPromoteStudents(){
              if(Yii::$app->user->isGuest){
                return $this->redirect(['site/login']);
              }else{
                $model = new StudentInfo();
            return $this->render('promote-students', [
              'model' => $model,
              ]);
          }
        }

        /*branch student list for promotion*/
        public function actionBranchStudentList(){
          if(Yii::$app->user->isGuest){
            return $this->redirect(['site/login']);
          }else{
            if(Yii::$app->request->isAjax){
              $model = new StudentInfo();
              $data  =  Yii::$app->request->post();
              if(Yii::$app->request->post()){
                if($data['section_id']){
                  $class_id = $data['class_id'];
                  $group_id = $data['group_id'];
                  $section_id = $data['section_id'];
                  /*query*/
                  $query = StudentInfo::find()->where([
                    'fk_branch_id'  => Yii::$app->common->getBranch(),
                    'class_id'      => $class_id,
                    'group_id'      => ($group_id)?$group_id:null,
                    'section_id'    => $section_id,
                    'is_active'     => 1
                    ]);
                  $searchModel = new \app\models\search\StudentInfoSearch();

                        //$searchModel->patient_id = $post_data['pat_id'];
                        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                  $dataProvider= new ActiveDataProvider([
                    'query'=>$query,
                    'sort' => [
                    'defaultOrder' => [
                    'stu_id' => SORT_DESC
                    ]
                    ],
                    'pagination' => [
                    'pageSize' => 500,
                    'params' => [
                    'class_id'      => $class_id,
                    'group_id'      => ($group_id)?$group_id:null,
                    'section_id'    => $section_id,
                    ],
                    ]
                    ]);
                        // print_r(Yii::$app->request->queryParams);die;

                  $details =  $this->renderAjax('branch-students-list', [
                    'searchModel'   => $searchModel,
                    'dataProvider'  => $dataProvider,
                    'model'         =>$model,
                    ]);

                  return json_encode(['status'=>1 ,'details'=>$details]);
                }
                else{
                  return json_encode(['status'=>1,'details'=>'<div class="alert alert-warning">
                    <strong>Note!</strong>Record Not Found.</div>']);
                }
              }
              else{
                /*geting data on pagination.*/
                $post_data  = Yii::$app->request->get();
                $class_id   = $post_data['class_id'];
                $group_id   = $post_data['group_id'];
                $section_id = $post_data['section_id'];
                /*query*/
                $query = StudentInfo::find()->where([
                  'fk_branch_id'  => Yii::$app->common->getBranch(),
                  'class_id'      => $class_id,
                  'group_id'      => ($group_id)?$group_id:null,
                  'section_id'    => $section_id,
                  ]);
                $countQuery = clone $query;

                $pages = new \yii\data\Pagination(['totalCount' => $countQuery->count()]);
                $dataProvider = new ActiveDataProvider([
                  'query' => $query,
                  'sort' => [
                  'defaultOrder' => [
                  'stu_id' => SORT_DESC
                  ]
                  ]
                  ]);

                return $this->renderAjax('branch-students-list',
                  [
                  'dataProvider'  => $dataProvider,
                  'pages'         => $pages,
                  'model'         => $model,
                  ]);
              }
            }
          }
        }
        /*Get Group*/
        public function actionGetGroup(){
          $type = '';
          if(Yii::$app->request->post('class_id')){
            $groups = Yii::$app->common->getGroup(Yii::$app->request->post('class_id'));
            if(count($groups)>0){
              $options = "<option selected='selected'>Select Group</option>";
              foreach($groups as $group)
              {
                $options .= "<option value='".$group['id']."'>".$group['name']."</option>";
              }
              $type='group';
            }else{
              $options = "<option selected='selected'>Select Section</option>";

              $type = 'section';
              $sections = Yii::$app->common->getSection(Yii::$app->request->post('class_id'),Null);
              foreach ($sections as $section){
                $options .= "<option value='".$section['id']."'>".$section['name']."</option>";
              }
            }

          }
          return Json_encode(['type'=>$type,'html'=>$options]);

        }
    //end of group
        /*get section */
        public function actionGetSection(){

          $options = "<option selected='selected'>Select Section</option>";
          if(Yii::$app->request->post('class_id')){
            $class_id = Yii::$app->request->post('class_id');
            $group_id = Yii::$app->request->post('group_id');
            $sections = Yii::$app->common->getSection($class_id,$group_id);
            foreach($sections as $section)
            {
              $options .= "<option value='".$section['id']."'>".$section['name']."</option>";
            }
          }
          return $options;
        }
        //end of Section

        public function actionProfile(){
          if(Yii::$app->user->isGuest){
            return $this->redirect(['site/login']);
          }else{
            $model = new StudentInfo();
            /*
            *   Process for non-ajax request
            */
            if(yii::$app->request->get('id')){

              $student_id  = yii::$app->request->get('id');
              $studentInfo = Yii::$app->common->getStudent($student_id);
              $year=date('Y');
              $total_time_line = [];
              if(Yii::$app->request->get('old_class')){

              }else{
                /*student timeline  query*/
                $Stdtimeline = \app\models\StuRegLogAssociation::find()
                ->select([
                  'rc.title class_name',
                  'rg.title group_name',
                  'rs.title section_name',
                  'stu_reg_log_association.current_class_id old_class',
                  'stu_reg_log_association.current_group_id old_group',
                  'stu_reg_log_association.current_section_id old_section',
                  'stu_reg_log_association.promoted_date',
                  ])
                ->innerJoin('ref_class rc','rc.class_id=stu_reg_log_association.current_class_id')
                ->leftJoin('ref_group rg','rg.group_id=stu_reg_log_association.current_group_id')
                ->leftJoin('ref_section rs','rs.section_id=stu_reg_log_association.current_section_id')
                ->where(['stu_reg_log_association.fk_stu_id'=>$student_id])
                        // ->orderBy(['stu_reg_log_association.id'=>SORT_DESC])
                ->asArray()
                ->all();
                $existing_class[] =  [
                'class_name' =>$studentInfo->class->title ,
                'group_name' =>($studentInfo->group_id)?$studentInfo->group->title:'',
                'section_name' =>$studentInfo->section->title ,
                'old_class' =>$studentInfo->class_id,
                'old_group' =>($studentInfo->group_id)?$studentInfo->group_id:null,
                'old_section' =>$studentInfo->section_id,
                'promoted_date' =>date('Y-m-d'),
                ];
                if(count($Stdtimeline)>0){
                  $last_pointer   = count($Stdtimeline)-1;
                  $start_date     = $Stdtimeline[$last_pointer]['promoted_date'];
                  $total_time_line  = array_merge($Stdtimeline,$existing_class);
                }else{
                  $start_date       = date('Y-m-d',strtotime($studentInfo->registration_date));
                  $total_time_line  = $existing_class;
                }
                /*get exams.*/
                $query = Exam::find()
                ->select(['exam.fk_exam_type id','et.type title'])
                ->innerJoin('exam_type et','et.id=exam.fk_exam_type')
                ->innerJoin('student_marks sm','sm.fk_exam_id=exam.id')
                ->where([
                  'exam.fk_branch_id'=>Yii::$app->common->getBranch(),
                  'exam.fk_class_id'=>$studentInfo->class_id,
                  'exam.fk_group_id'=>($studentInfo->group_id)?$studentInfo->group_id:null,
                  'exam.fk_section_id'=>$studentInfo->section_id,
                  'sm.fk_student_id'=>$studentInfo->stu_id
                  ])->andWhere(['like','start_date',$year])
                ->groupBy('fk_exam_type')
                ->asArray()->all();
                /*$query = Exam::find()
                ->select(['exam.fk_exam_type id','et.type title'])
                ->innerJoin('exam_type et','et.id=exam.fk_exam_type')
                ->where([
                  'exam.fk_branch_id'=>Yii::$app->common->getBranch(),
                  'exam.fk_class_id'=>$studentInfo->class_id,
                  'exam.fk_group_id'=>($studentInfo->group_id)?$studentInfo->group_id:null,
                  'exam.fk_section_id'=>$studentInfo->section_id
                  ])->andWhere(['like','start_date',$year])*/
                //->groupBy('fk_exam_type')
                //->asArray()->all();


                $exam_array = \yii\helpers\ArrayHelper::map($query, 'id', 'title');

                /*student Attendance*/

                $attendance_query = StudentAttendance::find()->select(['count(*) as total','leave_type'])
                ->where(['fk_stu_id'=>$student_id])
                ->andWhere(['between','date(date)', $start_date, date('Y-m-d')])
                ->groupBy('leave_type')->asArray()->all();



                /*FEE DATA COLLECTION*/
                $std_plan_type      = $studentInfo->fk_fee_plan_type;
                $class_id           = $studentInfo->class_id;
                $group_id           = $studentInfo->group_id;
                $section_id         = $studentInfo->section_id;
                $stop_id            = $studentInfo->fk_stop_id;
                $is_hostel_avail    = $studentInfo->is_hostel_avail;
                $sum_total=0;
                $total_payment_received = 0;
                $total_payment_arrears = 0;

                $transport_fare=0;
                $hostel_fare=0;
                if(!empty($stop_id)){
                  $stopModel = Stop::findOne($stop_id);
                  $transport_fare=$stopModel->fare;
                }
                if($is_hostel_avail){
                  $hostelDetail = HostelDetail::find()
                  ->select('h.amount amount')
                  ->innerJoin('hostel h','h.id=hostel_detail.fk_hostel_id')
                  ->where(['hostel_detail.fk_branch_id'=>Yii::$app->common->getBranch(),'hostel_detail.fk_student_id'=>$studentInfo->stu_id])->asArray()->one();
                  $hostel_fare = $hostelDetail['amount'];
                }
                $total_months_amount = $sum_total*12;
                if($total_payment_arrears >0){
                  $arrears = $total_payment_arrears - $total_payment_received;
                }else{
                  $arrears = 0;
                }

                $total_Year_amount = $total_months_amount - $total_payment_received;
                $pi_array_fee = [
                ['name'=>"Total Amount",'data'=>$total_Year_amount],
                ['name'=>"Total Received",'data'=>$total_payment_received],
                ['name'=>"Total Arrears",'data'=>$arrears]
                ];

                // fee details query
                $studentParentInfo =  \app\models\StudentParentsInfo::find()->where(['stu_id'=>yii::$app->request->get('id')])->one(); 
                if(count($studentParentInfo)>0 && !empty($studentParentInfo->cnic)){
                    $parent_cnic = $studentParentInfo->cnic;
                    $cnic_count =  \app\models\StudentParentsInfo::find()->where(['cnic'=>$studentParentInfo->cnic])->count();
                }
                $getFee = \app\models\FeeGroup::find()
                ->where([
                 'fk_branch_id'  =>Yii::$app->common->getBranch(),
                 'fk_class_id'   => $studentInfo->class_id,
                 'fk_group_id'   => ($group_id)?$group_id:null,
                 ])->all();
               $stuFeeRcv =  \app\models\FeeSubmission::find()
                        ->select('sum(head_recv_amount) total_amount_receive,sum(transport_amount) transport_amount_rcv,sum(hostel_amount) hostel_amount_rcv')
                        ->where(['branch_id'=>Yii::$app->common->getBranch(),'stu_id'=>$studentInfo->stu_id])->asArray()->one();
              $stuTransprtHstlArrears =  \app\models\FeeSubmission::find()
                        ->select('transport_arrears,hostel_arrears')
                        ->where(['branch_id'=>Yii::$app->common->getBranch(),'stu_id'=>$studentInfo->stu_id,'fee_status'=>1])->asArray()->one();
              $stuarrears = \app\models\FeeArears::find()->where(['stu_id'=> $studentInfo->stu_id,'status'=>1])->one();
              //print_r($stuarrears);die;
              $fee_arrears_rcv=\app\models\FeeArrearsRcv::find()->where(['stu_id'=>$studentInfo->stu_id])->sum('amount');
                return $this->render('profile', [
                  'model'             => $model,
                  'studentInfo'       => $studentInfo,
                  'transport_fare'    => $transport_fare,
                  'hostel_fare'       => $hostel_fare,
                  'attendance_array'  => $attendance_query,
                  'total_time_line'   => $total_time_line,
                  'start_date'        => $start_date,
                  'end_date'          => date('Y-m-d'),
                  'pi_array_fee'      => $pi_array_fee,
                  'exam_array'      => $exam_array,
                  'getFee'      => $getFee,
                  'cnic_count'=>$cnic_count,
                  'stuFeeRcv'=>$stuFeeRcv,
                  'stuarrears'=>$stuarrears,
                  'fee_arrears_rcv'=>$fee_arrears_rcv,
                  'stuTransprtHstlArrears'=>$stuTransprtHstlArrears,
                  ]);

              }

            }

          }
        }


        /*student profile exam*/
        public function actionProfileExam(){
          if(Yii::$app->user->isGuest){
            return $this->redirect('site/login');
          }else{
            if(Yii::$app->request->post()){
              $data       =yii::$app->request->post();
              $examId      = $data['examId'];
              $class      = $data['classid'];
              $sectionid  = $data['sectionid'];
              $stdid      = $data['stdid'];
              $examdivid      = $data['examdivid'];


              $subjects_data = Exam::find()
              ->select([
                'st.stu_id',
                'concat(u.first_name," ",u.last_name) student_name',
                'c.class_id',
                'c.title',
                'g.group_id',
                'g.title',
                's.section_id',
                's.title',
                'sb.title subject',
                'sum(exam.total_marks) total_marks',
                'sum(exam.passing_marks) passing_marks',
                'round(sum(sm.marks_obtained),2) marks_obtained'
                ])
              ->innerJoin('exam_type et','et.id=exam.fk_exam_type')
              ->innerJoin('ref_class c','c.class_id=exam.fk_class_id')
              ->innerJoin('subjects sb','sb.id=exam.fk_subject_id')
              ->leftJoin('student_marks sm','sm.fk_exam_id=exam.id')
              ->leftJoin('student_info st',' st.stu_id=sm.fk_student_id')
              ->leftJoin('ref_group g','g.group_id=exam.fk_group_id')
              ->leftJoin('ref_section s','s.class_id=exam.fk_class_id')
              ->innerJoin('user u','u.id=st.user_id')
              ->where(['exam.fk_branch_id'=>Yii::$app->common->getBranch(),'c.class_id'=>$class,'g.group_id'=>($data['groupid'])?$data['groupid']:null,'s.section_id'=>$sectionid,'st.stu_id'=>$stdid,'et.id'=>$examId ])
              ->groupBy(['st.stu_id','c.class_id','c.title','g.group_id','g.title','s.section_id','s.title','sb.title'])->asArray()->all();

              $pichart_arr = [];
              foreach($subjects_data as $kay=>$sub_data){
                $pichart_arr[] = [$sub_data['subject'],$sub_data['marks_obtained']];
              }

              $html =  $this->renderAjax('profile-exam', [
                'subjects_data'     => $subjects_data
                ]);

              return json_encode(['status'=>1,'details'=>$html,'examdivid'=>$examdivid,'piExamArr'=>$pichart_arr],JSON_NUMERIC_CHECK);

            }
          }
        } //end of function
        public function actionProfileExam1(){
          $data= Yii::$app->request->post();
         // echo '<pre>';print_r($data);die;
          $student = Yii::$app->common->getStudent($data['stdid']);
          $branch_details = Yii::$app->common->getBranchDetail();
          $status = 0;
          $subjects_data = Exam::find()
                ->select([
                    'st.stu_id',
                    'st.is_active',
                    'concat(u.first_name," ",u.last_name) student_name',
                    'c.class_id',
                    'c.title',
                    'g.group_id',
                    'g.title',
                    's.section_id',
                    's.title',
                    'sm.remarks',
                    'sb.title subject',
                    'sum(exam.total_marks) total_marks',
                    'sum(exam.passing_marks) passing_marks',
                    'round(sum(sm.marks_obtained),2) marks_obtained'
                ])
                ->innerJoin('exam_type et','et.id=exam.fk_exam_type')
                ->innerJoin('ref_class c','c.class_id=exam.fk_class_id')
                ->innerJoin('subjects sb','sb.id=exam.fk_subject_id')
                ->leftJoin('student_marks sm','sm.fk_exam_id=exam.id')
                ->leftJoin('student_info st',' st.stu_id=sm.fk_student_id')
                ->leftJoin('ref_group g','g.group_id=exam.fk_group_id')
                ->leftJoin('ref_section s','s.class_id=exam.fk_class_id')
                ->innerJoin('user u','u.id=st.user_id')
                ->where(['exam.fk_branch_id'=>Yii::$app->common->getBranch(),'c.class_id'=>$data['classid'],'g.group_id'=>($data['groupid'])?$data['groupid']:null,'s.section_id'=>$data['sectionid'],'st.stu_id'=>$data['stdid'],'et.id'=>$data['examId'],'st.is_active'=>1 ])
                ->groupBy(['st.stu_id','c.class_id','c.title','g.group_id','g.title','s.section_id','s.title','sb.title'])->asArray()->all();
                /*graph Query*/
            $class_data = Exam::find()
                ->select([
                    'c.title class','g.title group_name',
                    's.title section_name',
                    'sb.title subject',
                    'sum(exam.total_marks) total_marks',
                    'sum(exam.passing_marks) passing_marks',
                    'sum(sm.marks_obtained) marks_obtained',
                    '(sum(sm.marks_obtained) /  sum(exam.total_marks)) * 100 percentage_marks_obtain'
                ])
                ->innerJoin('exam_type et','et.id=exam.fk_exam_type')
                ->innerJoin('ref_class c','c.class_id=exam.fk_class_id')
                ->innerJoin('subjects sb','sb.id=exam.fk_subject_id')
                ->leftJoin('student_marks sm','sm.fk_exam_id=exam.id')
                ->leftJoin('student_info st',' st.stu_id=sm.fk_student_id')
                ->leftJoin('ref_group g','g.group_id=exam.fk_group_id')
                ->leftJoin('ref_section s','s.class_id=exam.fk_class_id')
                ->innerJoin('user u','u.id=st.user_id')
                ->where([
                    'exam.fk_branch_id'=>Yii::$app->common->getBranch(),
                    'c.class_id'=>$data['classid'],
                    'g.group_id'=>($data['groupid'])?$data['groupid']:null,
                    's.section_id'=>$data['sectionid'],
                    'et.id'=>$data['examId'],
                    'st.is_active'=>1
                ])
                ->groupBy(['c.title','g.title','s.title','sb.title'])->asArray()->all();
                 $examtype = ExamType::findOne($data['examId']);
                 $pichart_arr = [];
              foreach($subjects_data as $kay=>$sub_data){
                $pichart_arr[] = [$sub_data['subject'],$sub_data['marks_obtained']];
              }
                 if(count($subjects_data)>0){

                $details_html = $this->renderAjax('_ajax/student-dmc',[
                    'student'=>$student,
                    'query' =>$subjects_data,
                    'exam_details'=>$examtype,
                    'branch_details'=>$branch_details,
                    'piExamArr'=>$pichart_arr
                    //'position'=>$data['stdPosition'],
                ]);
                // echo $details_html;die;
                $status =1;
                return json_encode(['status'=>1,'details'=>$details_html],JSON_NUMERIC_CHECK);

            }else{
                $details_html = "<strong>Records not found.</strong>";
            }
        } // end of function
        public function actionProfileExam111(){
          $data= Yii::$app->request->post();
           $examid    = $data['examId'];
              $class_id      = $data['classid'];
              $group_id      = $data['groupid'];
              $section_id  = $data['sectionid'];
              $stu_id      = $data['stdid'];
              $examdivid      = $data['examdivid'];
              $branch_details = Yii::$app->common->getBranchDetail();
         // echo '<pre>';print_r($data);die;
              $student = Yii::$app->common->getStudent($data['stdid']);
               $examtype = ExamType::findOne($data['examId']);
          $students= StudentInfo::find()
                    ->select(['student_info.stu_id','student_info.is_active','u.username roll_no','u.id user_id'])
                    ->innerJoin('user u','u.id=student_info.user_id')
                    ->where(['student_info.fk_branch_id'=>Yii::$app->common->getBranch(),'student_info.class_id'=>$class_id,'student_info.group_id'=>($group_id)?$group_id:null,'student_info.section_id'=>$section_id,'student_info.stu_id'=>$stu_id,'student_info.is_active'=>1])
                    ->asArray()
                    ->all();
                /*selected student resutl.*/
                $exams_students = Exam::find()
                    ->select([
                        'st.stu_id','st.is_active','concat(u.first_name," ",u.middle_name," ",u.last_name) student_name'
                    ])
                    ->innerJoin('exam_type et','et.id=exam.fk_exam_type')
                    ->innerJoin('ref_class c','c.class_id=exam.fk_class_id')
                    ->innerJoin('subjects sb','sb.id=exam.fk_subject_id')
                    ->leftJoin('student_marks sm','sm.fk_exam_id=exam.id')
                    ->leftJoin('student_info st',' st.stu_id=sm.fk_student_id')
                    ->leftJoin('ref_group g','g.group_id=exam.fk_group_id')
                    ->leftJoin('ref_section s','s.class_id=exam.fk_class_id')
                    ->innerJoin('user u','u.id=st.user_id')
                    ->where(['et.id'=>$examid,'exam.fk_branch_id'=>Yii::$app->common->getBranch(),'c.class_id'=>$class_id,'g.group_id'=>($group_id)?$group_id:null,'s.section_id'=>$section_id,'st.is_active'=>1])
                    ->groupBy(['st.stu_id'])
                    ->asArray()
                    ->all();
                /*get all students marks and position*/
                if(count($students)){
                    $studentexam_arr=[];
                    $examsubjects_arr=[];
                    foreach ($students as  $skey=>$stu_id){
                        $subjects_data = Exam::find()
                            ->select([
                                'st.stu_id',
                                'st.is_active',
                                'concat(u.first_name," ",u.last_name) student_name',
                                'c.class_id',
                                'c.title',
                                'g.group_id',
                                'g.title',
                                's.section_id',
                                's.title',
                                'sb.title subject',
                                'sum(exam.total_marks) total_marks',
                                'sum(exam.passing_marks) passing_marks',
                                'round(sum(sm.marks_obtained),2) marks_obtained'
                            ])
                            ->innerJoin('exam_type et','et.id=exam.fk_exam_type')
                            ->innerJoin('ref_class c','c.class_id=exam.fk_class_id')
                            ->innerJoin('subjects sb','sb.id=exam.fk_subject_id')
                            ->leftJoin('student_marks sm','sm.fk_exam_id=exam.id')
                            ->leftJoin('student_info st',' st.stu_id=sm.fk_student_id')
                            ->leftJoin('ref_group g','g.group_id=exam.fk_group_id')
                            ->leftJoin('ref_section s','s.class_id=exam.fk_class_id')
                            ->innerJoin('user u','u.id=st.user_id')
                            ->where(['et.id'=>$examid,'exam.fk_branch_id'=>Yii::$app->common->getBranch(),'c.class_id'=>$class_id,'g.group_id'=>($group_id)?$group_id:null,'s.section_id'=>$section_id,'st.stu_id'=>$stu_id['stu_id'],'st.is_active'=>1 ])
                            ->groupBy(['st.stu_id','c.class_id','c.title','g.group_id','g.title','s.section_id','s.title','sb.title'])->asArray()->all();
                        if(count($subjects_data)>0){
                            $sumTotalMarks = 0;
                            $studentexam_arr[$stu_id['stu_id']]['student_id']=$stu_id['roll_no'];
                            $studentexam_arr[$stu_id['stu_id']]['name']=$stu_id['user_id'];
                            $std = $stu_id['stu_id'];
                            foreach ($subjects_data as $indata){
                                if($std == $stu_id['stu_id']){
                                    $sumTotalMarks  =  $sumTotalMarks + $indata['marks_obtained'];
                                    $studentToralMarks [$stu_id['stu_id']] = $sumTotalMarks;
                                }
                                $studentexam_arr[$stu_id['stu_id']][] = $indata['marks_obtained'];
                                if($skey==0){
                                    $examsubjects_arr['heads'][] = $indata['subject'];
                                    $examsubjects_arr['total_marks'][] = $indata['total_marks'];
                                }

                                /*sum condition*/
                                if($std != $stu_id['stu_id']){
                                    $sumTotalMarks  = 0;
                                }
                            }
                        }
                    }
                    /*maintain student id's and sort desc.*/
                    natcasesort($studentToralMarks);
                    $sortArr = array_reverse($studentToralMarks, true);
                    $position  = [];
                    $counter= 1;
                    $stdMarks = 0;
                    /*custom sort*/
                    foreach($sortArr as $key=>$totalStdObtainMarks){
                        if($stdMarks ==0){
                            $stdMarks = $totalStdObtainMarks;
                        }
                        if($stdMarks == $totalStdObtainMarks){
                            //echo $stdMarks.'----'.$totalStdObtainMarks.'----' .$counter."<br/>";
                            $position[] = ['position'=>$counter,'student_id'=>$key,'marks'=>$totalStdObtainMarks];
                        }else{
                            $counter = $counter+1;
                            $position[] = ['position'=>$counter,'student_id'=>$key,'marks'=>$totalStdObtainMarks];
                            //echo $stdMarks.'----'.$totalStdObtainMarks.'----' .$counter."-No pos - <br/>";
                        }
                        $stdMarks = $totalStdObtainMarks;
                    }

                }
                if(count($exams_students)>0){
                    $status = 1;
                }
                $details_html = $this->renderAjax('_ajax/student-dmc',[
                    'students'=>$students,
                    'student'=>$student,
                    'query' =>$subjects_data,
                    'exam_details'=>$examtype,
                    'branch_details'=>$branch_details,
                    'positions'      => $position
                ]);
                 return json_encode(['status'=>1,'details'=>$details_html],JSON_NUMERIC_CHECK);
        }

        /*save promoted studen*/
        public function actionSavePromotedStudent(){
          if(Yii::$app->user->isGuest){
            return $this->redirect(['site/login']);
          }else{
            if(Yii::$app->request->isAjax){
              $data = Yii::$app->request->post();
              $c_cid   =   $data['c_cid'];
              $c_gid   =   $data['c_gid'];
              $c_sid   =   $data['c_sid'];
              $selected_students  = $data['selected_students'];
              $new_cid = $data['new_cid'];
              $new_gid = $data['new_gid'];
              $new_sid = $data['new_sid'];

              /*promote individual studen loop*/
              foreach($selected_students as $key=>$student_id){
                $StdRegLogAssoc   = new StuRegLogAssociation();
                $studentInfoModel = StudentInfo::findOne($student_id);
                $studentInfoModel->class_id = $new_cid;
                $studentInfoModel->group_id = ($new_gid)?$new_gid:null;
                $studentInfoModel->section_id = $new_sid;
                $studentInfoModel->save(false);

                $FeeStructure = \app\models\FeeGroup::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'fk_class_id'=>$studentInfoModel->class_id,'fk_group_id'=>($studentInfoModel->group_id)?$studentInfoModel->group_id:null])->all();
                /*new entery to std reg log*/
                $StdRegLogAssoc->fk_stu_id      =   $student_id;
                $StdRegLogAssoc->fk_class_id    =  $new_cid;
                $StdRegLogAssoc->fk_group_id    =  ($new_gid)?$new_gid:null;
                $StdRegLogAssoc->fk_section_id  =   $new_sid;
                $StdRegLogAssoc->fk_stu_id      =   $student_id;
                $StdRegLogAssoc->current_class_id   = $c_cid;
                $StdRegLogAssoc->current_group_id   = ($c_gid)?$c_gid:null;
                $StdRegLogAssoc->current_section_id = $c_sid;
                if(!$StdRegLogAssoc->save()){
                  echo "<pre>";
                  print_r($StdRegLogAssoc->getErrors());
                  exit;
                }


              }
              Yii::$app->session->setFlash('success','Students Promoted Successfully');
              return json_encode(['status'=>1,'returnUrl'=>\yii\helpers\Url::to(['student/promote-students','id'=>base64_encode('promote')],true)]);
            }
          }
        }

        /*get profile stats*/
        public function actionGetProfileStats(){
          if(Yii::$app->user->isGuest){
            return $this->redirect(['site/login']);
          }else{
            if(Yii::$app->request->isAjax){
              $data= Yii::$app->request->post();
              if($data){
                /*student Attendance*/
                $attendance_query = StudentAttendance::find()->select(['count(*) as total','leave_type'])
                ->where(['fk_stu_id'=>$data['student_id']])
                ->andWhere(['between','date(date)', $data['start_date'], $data['end_date']])
                ->groupBy('leave_type')
                ->asArray()
                ->all();

                /*student attedance array*/
                $attenance_data=[];
                foreach ($attendance_query as $key=>$attendance_details){
                  $attenance_data['leave_type'][]= $attendance_details['leave_type'];
                  $attenance_data['total'][]= $attendance_details['total'];
                }
                $array =$attenance_data;

                return json_encode([
                  'attenance_data'=>$array,
                  'start_date'=>$data['start_date'],
                  'end_date'=>$data['end_date']
                  ]);
              }
            }
          }
        }


        /*check bed assigned*/
        public function actionCheckBedAssigned(){
          if(Yii::$app->request->post()){
            $bed_id = Yii::$app->request->post('bed_id');
            $hostelAvailCount = HostelDetail::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'fk_bed_id'=>$bed_id])->count();
            if($hostelAvailCount > 0){
              return json_encode(['status'=>1]);
            }else{
              return json_encode(['status'=>0]);
            }
          }
        }
    /*
    reports student
    */
    /*get exam type options*/
    public function actionGetExamOptions(){
      if(Yii::$app->request->isAjax){
        $data = Yii::$app->request->post();
        $student_detail = Yii::$app->common->getStudent($data['student_id']);
        $class = $student_detail->class_id;
        $group = $student_detail->group_id;
        $section_id = $student_detail->section_id;

        $exams = Exam::find()
        ->select(['et.id','et.type'])
        ->innerJoin('exam_type et','et.id=exam.fk_exam_type')
        ->where(['fk_class_id'=>$class,'fk_group_id'=>($group)?$group:null,'fk_section_id'=>$section_id])
        ->asArray()->all();
        $options = "<option value=''>Select Exam..</option>";
        foreach ($exams as $examtype){
          $options .=  "<option value =".$examtype['id'].">".$examtype['type']."</option>";
        }

        return json_encode(['status'=>1,'options'=>$options,'counter'=>count($exams)]);
      }
    }


    ///////////////// bed condition

    public function actionCheckBed(){
      $bed=yii::$app->request->post('bedid');
      $room=yii::$app->request->post('roomid');
      $checkbed=HostelDetail::find()->where(['fk_room_id'=>$room,'fk_bed_id'=>$bed])->one();
      //echo count($checkbed);
      if($checkbed){
        return json_encode(['assign'=>'This bed is already assign']);
      }else{
       return json_encode(['assign'=>'']);
     }
   }

    //////////////// end of bed condition

  //student parent profile.
   public function actionParentProfile(){
    return  $this->render('parent-profile');
  }

/*public function actionInactiveStudent() 
          { 
          $query1=StudentInfo::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'is_active'=>0,'school_leave'=>0]);
            $dataprovider = new ActiveDataProvider([
              'query' => $query1,
              'pagination' => [
              'pageSize' => 500,
              ],
              ]);
            return $this->render('inactive', [ 
              'dataProvider' => $dataprovider, 
              ]); 
          }*/
          public function actionActivestu($id){
            $StudentLeaveInfo = StudentLeaveInfo::find()->where(['stu_id'=>$id])->one();
            $studentTable=StudentInfo::find()->where(['stu_id'=>$StudentLeaveInfo->stu_id])->one();
            $userTable=User::find()->where(['id'=>$studentTable->user_id])->one();
            $studentTable->is_active=1;
            $studentTable->school_leave=0;
            if($studentTable->save()){
              $userTable->status='active';
              $userTable->save();
              if($StudentLeaveInfo->delete()){
              Yii::$app->session->setFlash('success', "Success");
              $this->redirect(['index']);
              }else{
              Yii::$app->session->setFlash('success', "Some error occured");
              }
            }else{}
          }

          public function actionActiveStatus($id){
     // echo $id;die;
            $model= StudentInfo::findOne($id);
            $getstuid=studentInfo::find()->where(['stu_id'=>$id])->one();
            $model->is_active = '1';
            $model->save();


            $userid=$getstuid->user_id;
            $user = User::findOne($userid);
            $user->status = 'active';
            $user->save();
      /*if($user->save()){
            return $this->redirect(['inactive-stu']);

          }*/


          if (!Yii::$app->request->isAjax) {
            Yii::$app->session->setFlash('success', "Successfully Activated");
            return $this->redirect(['inactive-student']);
          }

        }

        public function actionShuffleStudents(){
          if(Yii::$app->user->isGuest){
            return $this->redirect(['site/login']);
          }else{
            $model = new StudentInfo();
            return $this->render('shuffle-students', [
              'model' => $model,
              ]);
          }
        }

        /*slc*/
         public function actionAlumni(){
          if(Yii::$app->user->isGuest){
            return $this->redirect(['site/login']);
          }else{
            $model = new StudentInfo();
            /*
            *   Process for non-ajax request
            */
            return $this->render('alumni', [
              'model' => $model,
              ]);
          }
        }

        public function actionShiftAlumni(){
          if(Yii::$app->user->isGuest){
            return $this->redirect(['site/login']);
          }else{
            if(Yii::$app->request->isAjax){
              $model = new StudentInfo();
              $data  =  Yii::$app->request->post();
              if(Yii::$app->request->post()){
                if($data['section_id']){
                  $class_id = $data['class_id'];
                  $group_id = $data['group_id'];
                  $section_id = $data['section_id'];
                  /*query*/
                  $section=RefSection::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'class_id'=>$class_id,'status'=>'active'])->all();

                  $query = StudentInfo::find()->where([
                    'fk_branch_id'  => Yii::$app->common->getBranch(),
                    'class_id'      => $class_id,
                    'group_id'      => ($group_id)?$group_id:null,
                    'section_id'    => $section_id,
                    'is_active'     => 1
                    ]);
                  $searchModel = new \app\models\search\StudentInfoSearch();

                        //$searchModel->patient_id = $post_data['pat_id'];
                        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                  $dataProvider= new ActiveDataProvider([
                    'query'=>$query,
                    'sort' => [
                    'defaultOrder' => [
                    'stu_id' => SORT_DESC
                    ]
                    ],
                    'pagination' => [
                    'pageSize' => 500,
                    'params' => [
                    'class_id'      => $class_id,
                    'group_id'      => ($group_id)?$group_id:null,
                    'section_id'    => $section_id,
                    ],
                    ]
                    ]);
                        // print_r(Yii::$app->request->queryParams);die;

                  $details =  $this->renderAjax('shift-alumni', [
                    'searchModel'   => $searchModel,
                    'dataProvider'  => $dataProvider,
                    'model'         =>$model,
                    'section' =>$section,
                    ]);

                  return json_encode(['status'=>1 ,'details'=>$details]);
                }
                else{
                  return json_encode(['status'=>1,'details'=>'<div class="alert alert-warning">
                    <strong>Note!</strong>Record Not Found.</div>']);
                }
              }
              else{
                /*geting data on pagination.*/
                $post_data  = Yii::$app->request->get();
                $class_id   = $post_data['class_id'];
                $group_id   = $post_data['group_id'];
                $section_id = $post_data['section_id'];
                /*query*/
                $query = StudentInfo::find()->where([
                  'fk_branch_id'  => Yii::$app->common->getBranch(),
                  'class_id'      => $class_id,
                  'group_id'      => ($group_id)?$group_id:null,
                  'section_id'    => $section_id,
                  ]);
                $countQuery = clone $query;
                $pages = new \yii\data\Pagination(['totalCount' => $countQuery->count()]);
                $dataProvider = new ActiveDataProvider([
                  'query' => $query,
                  'sort' => [
                  'defaultOrder' => [
                  'stu_id' => SORT_DESC
                  ]
                  ]
                  ]);
                return $this->renderAjax('branch-students-list-shuffle',
                  [
                  'dataProvider'  => $dataProvider,
                  'pages'         => $pages,
                  'model'         => $model,
                  'section' =>$section,
                  ]);
              }
            }
          }
        } //end of function

        public function actionSaveAlumni(){
          if(Yii::$app->user->isGuest){
            return $this->redirect(['site/login']);
          }else{
            if(Yii::$app->request->isAjax){
              $data = Yii::$app->request->post();
             // echo '<pre>';print_r($data);die;
              $c_cid   =   $data['c_cid'];
              $c_gid   =   $data['c_gid'];
                //$c_sid   =   $data['c_sid'];
              $selected_students  = $data['selected_students'];
               // $new_cid = $data['new_cid'];
                //$new_gid = $data['new_gid'];
               //$new_sid = $data['new_sid'];
              //$update_fee_arears_rec     = "UPDATE  user  SET status = 'inactive' WHERE branch_id = ".Yii::$app->common->getBranch()." and stu_id =".$data['stu_id'];

             //\Yii::$app->db->createCommand($update_fee_arears_rec)->execute();
              /*promote individual studen loop*/
              foreach($selected_students as $key=>$student_id){
                $studentInfo=StudentInfo::find()->where(['stu_id'=>$student_id])->one();
                $userId=$studentInfo->user_id;
                $StdRegLogAssoc   = new StuRegLogAssociation();
                $leaveInfo=new StudentLeaveInfo();
                $leaveInfo->stu_id=$student_id;
                $leaveInfo->created_date=date('Y-m-d');
                $leaveInfo->class_id = $c_cid;
                $leaveInfo->group_id = ($c_gid)?$c_gid:null;
                //$leaveInfo->section_id = $new_sid;
                $leaveInfo->branch_id = yii::$app->common->getBranch();
                $leaveInfo->remarks = 'ww';
                $leaveInfo->next_school = 'N/A';
                $leaveInfo->reason_for_leavingschool = 'parents wish';
                if($leaveInfo->save()){}else{print_r($leaveInfo->getErrors());die;}
                $leaveInfo->class_id=date('Y-m-d');
                $studentInfoModel = StudentInfo::findOne($student_id);
                $studentInfoModel->is_active = 0;
                $studentInfoModel->save(false);
                $userModel = User::findOne($userId);
                $userModel->status='inactive';
                if($userModel->save(false)){}else{print_r($userModel->getErrors());die;}
                    }
                    Yii::$app->session->setFlash('success','Students shifted successfully');
                    return json_encode(['status'=>1,'returnUrl'=>\yii\helpers\Url::to(['student/alumni'],true)]);
                  }
                }
              }
        /*end of slc*/

        /*branch student list for promotion*/
        public function actionBranchStudentListShuffle(){
          if(Yii::$app->user->isGuest){
            return $this->redirect(['site/login']);
          }else{
            if(Yii::$app->request->isAjax){
              $model = new StudentInfo();
              $data  =  Yii::$app->request->post();
              if(Yii::$app->request->post()){
                if($data['section_id']){
                  $class_id = $data['class_id'];
                  $group_id = $data['group_id'];
                  $section_id = $data['section_id'];
                  /*query*/
                  $section=RefSection::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'class_id'=>$class_id,'fk_group_id'=>($group_id)?$group_id:null,'status'=>'active'])->all();
                  $query = StudentInfo::find()->where([
                    'fk_branch_id'  => Yii::$app->common->getBranch(),
                    'class_id'      => $class_id,
                    'group_id'      => ($group_id)?$group_id:null,
                    'section_id'    => $section_id,
                    'is_active'     => 1
                    ]);
                  $searchModel = new \app\models\search\StudentInfoSearch();

                        //$searchModel->patient_id = $post_data['pat_id'];
                        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                  $dataProvider= new ActiveDataProvider([
                    'query'=>$query,
                    'sort' => [
                    'defaultOrder' => [
                    'stu_id' => SORT_DESC
                    ]
                    ],
                    'pagination' => [
                    'pageSize' => 500,
                    'params' => [
                    'class_id'      => $class_id,
                    'group_id'      => ($group_id)?$group_id:null,
                    'section_id'    => $section_id,
                    ],
                    ]
                    ]);
                        // print_r(Yii::$app->request->queryParams);die;

                  $details =  $this->renderAjax('branch-students-list-shuffle', [
                    'searchModel'   => $searchModel,
                    'dataProvider'  => $dataProvider,
                    'model'         =>$model,
                    'section' =>$section,
                    ]);

                  return json_encode(['status'=>1 ,'details'=>$details]);
                }
                else{
                  return json_encode(['status'=>1,'details'=>'<div class="alert alert-warning">
                    <strong>Note!</strong>Record Not Found.</div>']);
                }
              }
              else{
                /*geting data on pagination.*/
                $post_data  = Yii::$app->request->get();
                $class_id   = $post_data['class_id'];
                $group_id   = $post_data['group_id'];
                $section_id = $post_data['section_id'];
                /*query*/
                $query = StudentInfo::find()->where([
                  'fk_branch_id'  => Yii::$app->common->getBranch(),
                  'class_id'      => $class_id,
                  'group_id'      => ($group_id)?$group_id:null,
                  'section_id'    => $section_id,
                  ]);
                $countQuery = clone $query;
                $pages = new \yii\data\Pagination(['totalCount' => $countQuery->count()]);
                $dataProvider = new ActiveDataProvider([
                  'query' => $query,
                  'sort' => [
                  'defaultOrder' => [
                  'stu_id' => SORT_DESC
                  ]
                  ]
                  ]);
                return $this->renderAjax('branch-students-list-shuffle',
                  [
                  'dataProvider'  => $dataProvider,
                  'pages'         => $pages,
                  'model'         => $model,
                  'section' =>$section,
                  ]);
              }
            }
          }
        }


        /*save shuffle studen*/
        public function actionSaveShuffleStudent(){
          if(Yii::$app->user->isGuest){
            return $this->redirect(['site/login']);
          }else{
            if(Yii::$app->request->isAjax){
              $data = Yii::$app->request->post();
              $c_cid   =   $data['c_cid'];
              $c_gid   =   $data['c_gid'];
                //$c_sid   =   $data['c_sid'];
              $selected_students  = $data['selected_students'];
                //$new_cid = $data['new_cid'];
                //$new_gid = $data['new_gid'];
              $new_sid = $data['new_sid'];

              /*promote individual studen loop*/
              foreach($selected_students as $key=>$student_id){
                $StdRegLogAssoc   = new StuRegLogAssociation();
                $studentInfoModel = StudentInfo::findOne($student_id);
                    //$studentInfoModel->class_id = $new_cid;
                    //$studentInfoModel->group_id = ($new_gid)?$new_gid:null;
                $studentInfoModel->section_id = $new_sid;
                $studentInfoModel->save(false);
                    }
                    Yii::$app->session->setFlash('success','Section Successfully Changed');
                    return json_encode(['status'=>1,'returnUrl'=>\yii\helpers\Url::to(['student/shuffle-students','id'=>base64_encode('change')],true)]);
                  }
                }
              }

              public function actionStudentCalendarEvent(){
               $id=yii::$app->request->post('id');
               $cal= $this->renderAjax('calendrevent-student',['id'=>$id]);
               return json_encode(['cal'=>$cal]);

             }
             /*end of student profile*/


             /*------------------class wise student-------------*/
             public function actionClassWiseStudents(){
               $class_id=yii::$app->request->post('id');
               $getStu=StudentInfo::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'class_id'=>$class_id,'is_active'=>1])->all();
               $option="<option>Select Students</option>";
               foreach ($getStu as $getStudents) {
                $option.="<option value=".$getStudents->user_id.">".Yii::$app->common->getName($getStudents->user_id).' s/d/o '.Yii::$app->common->getParentName($getStudents->stu_id)."</option>";

              }
              return json_encode(['studata'=>$option]);

            }
             public function actionClassActiveInactiveStudents(){
               $class_id=yii::$app->request->post('id');
               $studentAlumniCheck=yii::$app->request->post('studentAlumniCheck');
               if($studentAlumniCheck == 1){
               $getStu=StudentInfo::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'class_id'=>$class_id,'is_active'=>1])->all();
               }else{
               $getStu=StudentInfo::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'class_id'=>$class_id,'is_active'=>0])->all();
               }
               $option="<option>Select Students</option>";
               foreach ($getStu as $getStudents) {
                $option.="<option value=".$getStudents->user_id.">".Yii::$app->common->getName($getStudents->user_id).' s/d/o '.Yii::$app->common->getParentName($getStudents->stu_id)."</option>";

              }
              return json_encode(['studata'=>$option]);

            }
            public function actionClassStudentsActiveInactive(){
               $class_id=yii::$app->request->post('id');
               $studentAlumniCheck=yii::$app->request->post('studentAlumniCheck');
               if($studentAlumniCheck == 0){
                $getStu=StudentInfo::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'class_id'=>$class_id,'is_active'=>0])->all();
               }else{
               $getStu=StudentInfo::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'class_id'=>$class_id,'is_active'=>1])->all();
               }
               $option="<option>Select Students</option>";
               foreach ($getStu as $getStudents) {
                $option.="<option value=".$getStudents->user_id.">".Yii::$app->common->getName($getStudents->user_id).' s/d/o '.Yii::$app->common->getParentName($getStudents->stu_id)."</option>";
              }
              return json_encode(['studata'=>$option]);
              }
            /*------------------end of class wise student------*/
            /*===========section wise students*/
            public function actionSectionWiseStudents(){
              // echo '<pre>';print_r($_POST);die;
               $postArray=yii::$app->request->post();
               $class_id=$postArray['class_id'];
               $group_id=$postArray['group_id'];
               $section_id=$postArray['section_id'];
               $getStu=StudentInfo::find()->where([
                'fk_branch_id'=>Yii::$app->common->getBranch(),
                'class_id'=>$class_id,
                'group_id'   => ($group_id)?$group_id:null,
                'section_id'=>$section_id,
                'is_active'=>1
              ])->all();

               $option="<option>Select Students</option>";
               foreach ($getStu as $getStudents) {
                $option.="<option value=".$getStudents->stu_id.">".Yii::$app->common->getName($getStudents->user_id).' s/d/o '.Yii::$app->common->getParentName($getStudents->stu_id)."</option>";
              }
              //return json_encode(['studata'=>$option]);
               return json_encode(['status'=>1 ,'details'=>$option]);

            }
            /*======get inactive studetns*/
            public function actionGetInactiveStudents(){
              // echo '<pre>';print_r($_POST);die;
               $postArray=yii::$app->request->post();
               $class_id=$postArray['class_id'];
               $group_id=$postArray['group_id'];
               $section_id=$postArray['section_id'];
               $getStu=StudentInfo::find()->where([
                'fk_branch_id'=>Yii::$app->common->getBranch(),
                'class_id'=>$class_id,
                'group_id'   => ($group_id)?$group_id:null,
                'section_id'=>$section_id,
                'is_active'=>0
              ])->all();

               $option="<option>Select Students</option>";
               foreach ($getStu as $getStudents) {
                $option.="<option value=".$getStudents->stu_id.">".Yii::$app->common->getName($getStudents->user_id).' s/d/o '.Yii::$app->common->getParentName($getStudents->stu_id)."</option>";
              }
              //return json_encode(['studata'=>$option]);
               return json_encode(['status'=>1 ,'details'=>$option]);

            }
            public function actionGetActiveStudents(){
               $postArray=yii::$app->request->post();
               $class_id=$postArray['class_id'];
               $group_id=$postArray['group_id'];
               $section_id=$postArray['section_id'];
               $getStu=StudentInfo::find()->where([
                'fk_branch_id'=>Yii::$app->common->getBranch(),
                'class_id'=>$class_id,
                'group_id'   => ($group_id)?$group_id:null,
                'section_id'=>$section_id,
                'is_active'=>1
              ])->all();
               $option="<option>Select Students</option>";
               foreach ($getStu as $getStudents) {
                $option.="<option value=".$getStudents->stu_id.">".Yii::$app->common->getName($getStudents->user_id).' s/d/o '.Yii::$app->common->getParentName($getStudents->stu_id)."</option>";
              }
               return json_encode(['status'=>1 ,'details'=>$option]);
            }
            /*----------------- student Attendance------*/
            public function actionAttendance(){
             $model = new Exam();
             $model2 = new StudentAttendance();
             return $this->render('attendance', [
              'attendanceModel' => $model2,
              'model' => $model
              ]);
           }

           /*get all students on basis of section and group.*/
           public function actionGetStu(){
            if(Yii::$app->user->isGuest){
              return $this->goHome();
            }else{
              if(Yii::$app->request->isAjax){
                $stuModel = new StudentInfo();
                $model = new StudentAttendance();
                $data= Yii::$app->request->post();
                $class_id = $data['class_id'];
                $group_id = $data['group_id'];
                $section_id = $data['section_id'];
                /*query*/
                $StudentQuery = StudentInfo::find()
                ->select(['class_id','group_id','section_id'])
                ->where([
                  'fk_branch_id'  =>Yii::$app->common->getBranch(),
                  'class_id'   => $class_id,
                  'group_id'   => ($group_id)?$group_id:null,
                  'section_id' => $section_id,
                  ])->groupBy(['class_id','group_id','section_id']);
                $dataprovider = $StudentQuery->all();
                if(count($dataprovider)>0){
                $details = $this->renderAjax('get-stu-attendance',[ //get-stu-data(old)
                  'dataprovider'=>$dataprovider,
                  'model'=>$stuModel,
                  'model'=>$model,
                  'class_id'=>$class_id,
                  'group_id'=>$group_id,
                  'section_id'=>$section_id
                  ]);
              }else{
                $details="<div class='col-md-6'><div class='Alert alert-warning'><strong><center> No Student found in this section !</center></strong></div> </div>";
              }

              return json_encode(['status'=>1 ,'details'=>$details]);
            }
          }
        }
        //get class students for attendance
        public function actionGetClassStuAttendance(){
            if(Yii::$app->user->isGuest){
              return $this->goHome();
            }else{
              if(Yii::$app->request->isAjax){
                $stuModel = new StudentInfo();
                $model = new StudentAttendance();
                $data= Yii::$app->request->post();
                $class_id = $data['class_id'];
                /*query*/
                $StudentQuery = StudentInfo::find()
                ->select(['class_id'])
                ->where([
                  'fk_branch_id'  =>Yii::$app->common->getBranch(),
                  'class_id'   => $class_id,
                  ])->groupBy(['class_id']);
                $dataprovider = $StudentQuery->all();
                if(count($dataprovider)>0){
                $details = $this->renderAjax('get-stu-attendance-class',[ //get-stu-data(old)
                  'dataprovider'=>$dataprovider,
                  'model'=>$stuModel,
                  'model'=>$model,
                  'class_id'=>$class_id,
                  ]);
              }else{
                $details="<div class='col-md-6'><div class='Alert alert-warning'><strong><center> No Student found in this section !</center></strong></div> </div>";
              }

              return json_encode(['status'=>1 ,'details'=>$details]);
            }
          }
        }
       // get Class gruoup students for attendance
        public function actionGetGroupStuAttendance(){
            if(Yii::$app->user->isGuest){
              return $this->goHome();
            }else{
              if(Yii::$app->request->isAjax){
                $stuModel = new StudentInfo();
                $model = new StudentAttendance();
                $data= Yii::$app->request->post();
                $class_id = $data['class_id'];
                $group_id = $data['group_id'];
                /*query*/
                $StudentQuery = StudentInfo::find()
                ->select(['class_id','group_id','section_id'])
                ->where([
                  'fk_branch_id'  =>Yii::$app->common->getBranch(),
                  'class_id'   => $class_id,
                  'group_id'   => ($group_id)?$group_id:null,
                  ])->groupBy(['class_id','group_id','section_id']);
                $dataprovider = $StudentQuery->all();
                if(count($dataprovider)>0){
                $details = $this->renderAjax('get-stu-attendance-group',[ //get-stu-data(old)
                  'dataprovider'=>$dataprovider,
                  'model'=>$stuModel,
                  'model'=>$model,
                  'class_id'=>$class_id,
                  'group_id'=>$group_id,
                  ]);
              }else{
                $details="<div class='col-md-6'><div class='Alert alert-warning'><strong><center> No Student found in this section !</center></strong></div> </div>";
              }

              return json_encode(['status'=>1 ,'details'=>$details]);
            }
          }
        }


        public function actionSaveAttendance(){
          ini_set('max_execution_time', 300);
          $attendanceModel = new StudentAttendance();
          $array=yii::$app->request->post('StudentAttendance');
          $fk_stu_id=$array['fk_stu_id'];
          $class_id=$array['class_id'];
          if(!empty($array['group_id'])){

          $group_id=$array['group_id'];
          }else{
            $group_id=NULL;
          }if(!empty($array['section_id'])){

          $section_id=$array['section_id'];
          }else{
            $section_id=NULL;
          }
          
          $leave_type=$array['leave_type'];
          $remarks=$array['remarks'];
          $date=$array['date'];
          $smssettings=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
          foreach( $fk_stu_id as $key => $fk_stu_id ) {
           $getparentcontact=StudentParentsInfo::find()->select('contact_no')->where(['stu_id'=>$fk_stu_id])->one();
           $sendParentContact=$getparentcontact->contact_no;
           $StudentInfo=StudentInfo::find()->where(['stu_id'=>$fk_stu_id])->one();
           $nameCnvrt= Yii::$app->common->getName($StudentInfo->user_id);
           $model= new StudentAttendance;
           $model->fk_branch_id=yii::$app->common->getBranch();
           $model->fk_stu_id=$fk_stu_id;
           $model->class_id=$class_id[$key];
           $model->group_id=$group_id[$key];
           $model->section_id=$section_id[$key];
           $model->leave_type=$leave_type[$key];
           $model->remarks=$remarks[$key];
           $model->date=$date;
           $model->time=date('H:i:s');
           if($leave_type[$key] == 'leave'){
            $msg='Dear Parent/Gardian, This is to inform you that '.$nameCnvrt.' is on leave today';
            }else if($leave_type[$key] == 'shortleave'){
            $msg='Dear Parent/Gardian, This is to inform you that '.$nameCnvrt.' is on Short Leave today';
            }else{
            $msg='Dear Parent/Gardian, This is to inform you that '.$nameCnvrt.' is '.$leave_type[$key].' today';
            }
           if($leave_type[$key] == 'present'){}else{
            if($smssettings->status == 1){
            $s=Yii::$app->common->SendSms($sendParentContact,$msg,$fk_stu_id);
           }
         }
           if($model->save()){
            Yii::$app->session->setFlash('success', "Student Attendance Successfully saved");
            $this->redirect(['attendance-list']);
          }else{
           //print_r($model->getErrors());die;
         }
       }
     }

     public function actionAttendanceList()
     {
       $searchModel = new StudentAttendanceSearch();
       $searchModel->leave_type = 'absents';
       $searchModel->date = date('Y-m-d');
       $todayAttendance=StudentAttendanceSearch::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'date(date)'=>date('Y-m-d')])->all();
       $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
       return $this->render('attendance-list', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'todayAttendance' => $todayAttendance,
        ]);
     }

     public function actionUpdateAttendance($id) 
     {  
      $model=StudentAttendance::find()->where(['id'=>$id])->one();
      $getparentcontact=StudentParentsInfo::find()->select('contact_no')->where(['stu_id'=>$model->fk_stu_id])->one();
      $StudentInfo=StudentInfo::find()->where(['stu_id'=>$model->fk_stu_id])->one();
      $smssettings=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
      $nameCnvrt= Yii::$app->common->getName($StudentInfo->user_id);
      $sendParentContact=$getparentcontact->contact_no;
      if ($model->load(Yii::$app->request->post())) { 
        $array=yii::$app->request->post('StudentAttendance');
        $leave_type=$array['leave_type'];
        $msg='Respectfull Sir..!Your Child '.$nameCnvrt.' '.$leave_type.' today';
        if($smssettings->status == 1){
        $s=Yii::$app->common->SendSms($sendParentContact,$msg,$model->fk_stu_id);
        }
        if($model->save()){
          Yii::$app->session->setFlash('success', "Student Attendance Successfully Updated");
          return $this->redirect(['attendance-list']); 
        }else{
          print_r($model->getErrors());die;
        }
      } else { 
        return $this->render('update-attendace', [ 
          'model' => $model, 
          ]); 
      } 
    }

    public function actionAttendanceDisplay(){
      return $this->render('attendance-display');
    } 

    /*------------------end student Attendance------*/
    public function actionEmptyFormPdf(){
        $emptyForm=$this->renderAjax('empty-form-pdf');
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($emptyForm);
        $indexx=$mpdf->Output('Student admission form'.date("d-m-Y").'.pdf', 'D');     
    }

   public function actionGetFee(){
     $data= Yii::$app->request->post();
     $class_id = $data['classId'];
     $group_id = $data['groupId'];
     $parent_cnic = $data['parent_cnic'];
     $transport_fare=0;
      $hostel_fee=0;
      $stuparent_info = 0;
      if(!empty($parent_cnic)){
        $stuparent_info = StudentParentsInfo::find()
        ->select(['student_parents_info.stu_id','si.fk_branch_id','student_parents_info.stu_parent_id'])
        ->innerJoin('student_info si','si.stu_id = student_parents_info.stu_id')
        ->where(['si.fk_branch_id' => Yii::$app->common->getBranch(),'student_parents_info.cnic'=>$parent_cnic,'si.is_active'=>1])
        ->count();
        
      }  
  // end of calcution of month
     $getFeeDetails = \app\models\FeeGroup::find()
     ->where([
      'fk_branch_id'  =>Yii::$app->common->getBranch(),
      'fk_class_id'   => $class_id,
      'fk_group_id'   => ($group_id)?$group_id:null,
      ])->all();
    if(count($getFeeDetails) > 0){
    $viewFeedetails=$this->renderAjax('fee-details',[
      'getFeeDetails' =>$getFeeDetails,
      'cnic_count'    => $stuparent_info,
      'parent_cnic'     => $parent_cnic]);
    }else{
      $viewFeedetails='<div class="row col-md-6 alert-warning">No Fee details found</div>';
    }
    return json_encode(['viewFeedetails'=>$viewFeedetails]);

   }

   public function actionStuAtt(){
    $model = new Exam();
    $model2 = new StudentAttendance();
    $class_array = \yii\helpers\ArrayHelper::map(\app\models\RefClass::find()->where(['status'=>'active','fk_branch_id'=>Yii::$app->common->getBranch()])->all(), 'class_id', 'title');
      return $this->render('stuatt', [
        'attendanceModel' => $model2,
        'model' => $model,
        'class_array' => $class_array,
        ]);
   }

   public function actionAttcal(){
    $data=yii::$app->request->post();
    $class_id=$data['class_id'];
    $group_id=$data['group_id'];
    $section_id=$data['section_id'];
    $stuQuery = \app\models\User::find()
            ->select(['student_info.stu_id',"concat(user.first_name, ' ' ,  user.last_name) as name"])
            ->innerJoin('student_info','student_info.user_id = user.id')
            ->where(['student_info.fk_branch_id'=>Yii::$app->common->getBranch(),'student_info.is_active'=>1,'class_id'=>$class_id,'group_id'=>($group_id)?$group_id:null,'section_id'=>$section_id])->asArray()->all();
        //$stuArray = \yii\helpers\ArrayHelper::map($stuQuery,'stu_id','name');
        $stuList=[];
        foreach ($stuQuery as $key => $arrayStu) {
          $stuList[]=['id'=>$arrayStu['stu_id'],'title'=>$arrayStu['name']];
        //echo '<pre>';print_r($attndnce);
         } 
        $attndnce=StudentAttendance::find()->select(['id','fk_stu_id','leave_type','date'])->asArray()->all();
        /*$attndnce=StudentAttendance::find()->select(['id','fk_stu_id','leave_type','date'])->where(['month(date)'=>date('m'),'year(date)'=>date('Y')])->asArray()->all();*/
        //echo '<pre>';print_r($attndnce);
       // die;
        $stu_attendance=[];
        foreach ($attndnce as $key => $stuAtt) {
          if($stuAtt['leave_type']=='absent'){
             $color='red';
          }else if($stuAtt['leave_type']=='leave'){
             $color='#ff8000';
          }else if($stuAtt['leave_type']=='late'){
             $color='#0080ff';
          }else if($stuAtt['leave_type']=='present'){
             $color='green';
          }
          if($stuAtt['leave_type'] == 'absent'){
            $attendance='A';
          }else if($stuAtt['leave_type'] == 'leave'){
            $attendance='L';
          }else if($stuAtt['leave_type'] == 'late'){
            $attendance='LT';
          }else if($stuAtt['leave_type'] == 'present'){
            $attendance='P';
          }
          $stu_attendance[]=['id'=>$stuAtt['id'],'resourceId'=>$stuAtt['fk_stu_id'],'start'=>$stuAtt['date'],'title'=>$attendance,'color'=>$color];

        }
        //secho '<pre>';print_r($stu_attendance); die;
        if(count($stuList)>0){
      $showStuAttView= $this->renderAjax('att-cal-stu',['stuList'=>json_encode($stuList),'stu_attendance'=>json_encode($stu_attendance)]);
        }else{
        $showStuAttView='<div class="alert alert-warning">No details found</div>';
        }
        return json_encode(['status'=>1,'details'=>$showStuAttView]);

        } //end of action
        /*section sms to selected students*/
        public function actionSelectedSms(){
          $model=new StudentInfo();
          return $this->render('_ajax/selected-sms',['model'=>$model]);
          }
        public function actionSectionStudents(){
        $data=Yii::$app->request->post();
        $class_id=$data['class_id'];
        $radioValue=$data['radioValue'];
        $group_id=$data['group_id'];
        $section_id=$data['section_id'];
        if($radioValue == 1){  
        $studentDetails=StudentInfo::find()->where(['class_id'=>$class_id,'group_id'=>($group_id)?$group_id:null,'section_id'=>$section_id,'is_active'=>1])->orderBy(['roll_no'=>SORT_ASC])->all();
        }else{
          $studentDetails=StudentInfo::find()->where(['class_id'=>$class_id,'group_id'=>($group_id)?$group_id:null,'section_id'=>$section_id,'is_active'=>0])->orderBy(['roll_no'=>SORT_ASC])->all();
        }
        if(count($studentDetails)>0){
       $view= $this->renderAjax('_ajax/section-students',[
        'studentDetails'=>$studentDetails,
        'class_id'=>$class_id,
        'group_id'=>$group_id,
        'section_id'=>$section_id,
      ]);
      }else{
        $view='<div class="alert alert-warning">No details found..!</div>';
        }
        return json_encode(['status'=>1,'details'=>$view]);
      }
      public function actionSectionSendSms(){
        ini_set('max_execution_time', 300);
      $stuId=Yii::$app->request->post('checbox'); //checkbox is on
      $smsActive=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
      foreach ($stuId as $key => $studentId) {
       $studetnDetails=Yii::$app->common->getStudent($key);
       $parentDetails=Yii::$app->common->getParent($key);
       $parentContacts= $parentDetails->contact_no;
       $sms='<span style="text-decoration:underline">About '.Yii::$app->common->getName($studetnDetails->user_id) .':</span>\n'. Yii::$app->request->post('sms');
       if($smsActive->status == 1){
        $send=Yii::$app->common->SendSms($parentContacts,$sms,$key); 
        if($send){
            Yii::$app->session->setFlash('success', 'Sms Successfully send..');
        }
          }else{
            Yii::$app->session->setFlash('success', 'Sms services is not active.');
          } 
       }
       Yii::$app->session->setFlash('success', 'Sms Successfully send..');
       $this->redirect(['selected-sms']);
      }
        /*section sms to selected students ends */

      public function actionIndex(){
      $model=new StudentInfo();
      $model->scenario = 'change';
      if(Yii::$app->request->post())
      {
      $status=Yii::$app->request->post('activeInactive');
      $data=Yii::$app->request->post('StudentInfo');
      $post_dropdown=$data['emergency_contact_no']; // value come from drop down
      $post_input=$data['contact_no']; // value come from input

      if($post_dropdown == 'address'){
         $userDetails = StudentInfo::find()
      ->select(['student_info.*','sp.*','user.username'])
      ->innerJoin('student_parents_info sp','sp.stu_id = student_info.stu_id')
      ->innerJoin('user','user.id = student_info.user_id')
      ->where(['student_info.is_active'=>$status,'student_info.fk_branch_id'=>Yii::$app->common->getBranch()])
      ->andWhere(['like','student_info.location1',$post_input])
      ->asArray()
      ->all();
      }else{

      if($post_dropdown == 'first'){
      $where = "user.first_name";
      }else if($post_dropdown == 'last'){
         $where = "user.last_name";
      }else if($post_dropdown == 'reg'){
         $where = "user.username";
      }else if($post_dropdown == 'cnic'){
         $where = "sp.cnic";
      }else if($post_dropdown == 'contact'){
         $where = "sp.contact_no";
      }else if($post_dropdown == 'parentName'){
         $where = "sp.first_name";
      }
      $userDetails = StudentInfo::find()
      ->select(['student_info.*','sp.*','user.username'])
      ->innerJoin('student_parents_info sp','sp.stu_id = student_info.stu_id')
      ->innerJoin('user','user.id = student_info.user_id')
      ->where(['student_info.is_active'=>$status,'student_info.fk_branch_id'=>Yii::$app->common->getBranch()])
      ->andWhere([$where=>$post_input])
      ->asArray()
      ->all();
      }
      return $this->render('search',['userDetails'=>$userDetails,'status'=>$status,'data'=>$post_input,'model'=>$model]);
      }
      else{ 
      return $this->render('search',['model'=>$model]);
      }
  }
  public function actionSearchPdf(){
      $data=Yii::$app->request->get('search');
      $status=Yii::$app->request->get('activeInactive');
      $userDetails = StudentInfo::find()
      ->select(['student_info.*','sp.*','user.username'])
      ->innerJoin('student_parents_info sp','sp.stu_id = student_info.stu_id')
      ->innerJoin('user','user.id = student_info.user_id')
      ->where(['student_info.is_active'=>$status,'student_info.fk_branch_id'=>Yii::$app->common->getBranch()])
      ->andWhere(['=','user.username',$data])
      ->orWhere(['=','user.first_name',$data])
      ->orWhere(['=','sp.first_name',$data])
      ->orWhere(['=','sp.contact_no',$data])
      ->orWhere(['=','sp.cnic',$data])
      ->orWhere(['=','student_info.contact_no',$data])
      ->orWhere(['like','student_info.location1',$data])
      ->asArray()->all();
      $view= $this->renderAjax('search-pdf',['userDetails'=>$userDetails,'status'=>$status,'data'=>$data]);
      $this->layout = 'pdf';
      $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
      $mpdf->AddPage();
      $mpdf->WriteHTML($view);
      $mpdf->Output('search.pdf', 'D');
  }

   public function actionCal(){
    $model = new Exam();
    $model2 = new StudentAttendance();
    $class_array = \yii\helpers\ArrayHelper::map(\app\models\RefClass::find()->where(['status'=>'active','fk_branch_id'=>Yii::$app->common->getBranch()])->all(), 'class_id', 'title');
      return $this->render('attendance-calendar', [
        'attendanceModel' => $model2,
        'model' => $model,
        'class_array' => $class_array,
        ]);
   }
   public function actionCalShow(){
    if(!isset($_GET['date'])){
    $data= Yii::$app->request->post();
    $start= $data['date'];
    $class_id = $data['class_id'];
    $group_id = $data['group_id'];
    $section_id = $data['section_id'];
    $stuQuery = \app\models\User::find()
            ->select(['student_info.stu_id','student_info.roll_no',"concat(user.first_name, ' ' ,  user.last_name) as name"])
            ->innerJoin('student_info','student_info.user_id = user.id')
            ->where(['student_info.fk_branch_id'=>Yii::$app->common->getBranch(),'student_info.is_active'=>1,'class_id'=>$class_id,'group_id'=>($group_id)?$group_id:null,'section_id'=>$section_id])->orderBy(['roll_no'=>SORT_ASC])->asArray()->all();
  $view=$this->renderAjax('_ajax/date-report',['stuQuery'=>$stuQuery,'start'=>$start,'class_id'=>$class_id,'group_id'=>$group_id,'section_id'=>$section_id]);
  return json_encode(['view'=>$view]);
   }else{
    $data= Yii::$app->request->get();
    $start= $data['date'];
    $class_id = $data['c_id'];
    $group_id = $data['g_id'];
    $section_id = $data['s_id'];
    $stuQuery = \app\models\User::find()
            ->select(['student_info.stu_id','student_info.roll_no',"concat(user.first_name, ' ' ,  user.last_name) as name"])
            ->innerJoin('student_info','student_info.user_id = user.id')
            ->where(['student_info.fk_branch_id'=>Yii::$app->common->getBranch(),'student_info.is_active'=>1,'class_id'=>$class_id,'group_id'=>($group_id)?$group_id:null,'section_id'=>$section_id])->orderBy(['roll_no'=>SORT_ASC])->asArray()->all();
  $view=$this->renderAjax('_ajax/date-report',['stuQuery'=>$stuQuery,'start'=>$start,'class_id'=>$class_id,'group_id'=>$group_id,'section_id'=>$section_id]);
  $this->layout = 'pdf';
    $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
    $mpdf->WriteHTML($view);
    $mpdf->Output('student-attendance-'.date("d-m-Y").'.pdf', 'D');  
   }
   }
   public function actionChange()
   {
    $userId=Yii::$app->request->post('userId');
    if(Yii::$app->request->post()){
     $data=Yii::$app->request->post('StudentInfo');
     $password=$data['password'];
     $user=User::find()->where(['id'=>$userId])->one();
     $s= $user->password_hash = Yii::$app->security->generatePasswordHash($password);
     if($user->save()){
        Yii::$app->session->setFlash('success', "Password Successfully Changed");
        $this->redirect('index');
    }else{
        echo '<div class="alert alert-danger">Some error has occur.. please contact to the support or try again</div>';
        $this->redirect('index');
    }
   }
}
 
}// end of class
