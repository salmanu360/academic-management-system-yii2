<?php

namespace app\controllers;

use app\models\StudentAttendance;
use app\models\Subjects;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\StudentInfo;
use app\models\User;
use app\models\search\StudentInfoSearch;
use app\models\search\HomeTaskSearch;
use app\models\Exam;
use yii\data\ActiveDataProvider;
use app\models\RefClass;
use app\models\RefGroup;
use app\models\RefSection;
use app\models\HomeTask;
use mPDF;
use app\models\Colors;
class GeneralController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

     /*===================================================================
      start of get class and group subjects, use for subject time table
     /*==================================================================*/

    public function actionGetClassDetails(){
    
        $id=Yii::$app->request->post('id');
        $group=RefGroup::find()->where(['fk_class_id'=>$id,'fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all();
        

        $groupSubjects=Subjects::find()->where(['fk_class_id'=>$id,'fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all();

        if(!empty($group)){
           $options = "<option value=''>Select Group</option>";
        foreach($group as $group)
        {
            $options .= "<option value='".$group->group_id."'>".$group->title."</option>";
        }
        return json_encode(['groupdata'=>$options]);
        }else{
           $optionsSectn = "<option value=''>Select Section</option>";
        foreach($groupSubjects as $sectionxx)
        {
            $optionsSectn .= "<option value='".$sectionxx->id."'>".$sectionxx->title."</option>";
        }
        return json_encode(['getSubjectsdata'=>$optionsSectn]);

        }
    }

    public function actionGroupDetails(){
    
        $id=Yii::$app->request->post('id');
        $classid=Yii::$app->request->post('classid');
        
        $section=RefSection::find()->where(['class_id'=>$classid,'fk_group_id'=>$id,'fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all();
           $optionsSectn = "<option value=''>Select Section</option>";
        foreach($section as $sectionxx)
        {
            $optionsSectn .= "<option value='".$sectionxx->section_id."'>".$sectionxx->title."</option>";
        }
        return json_encode(['sectiondetails'=>$optionsSectn]);

       
    }

    /*get subjects of group and section*/
   public function actionGetSubjects(){
        $classid=Yii::$app->request->post('classid');
        $id=Yii::$app->request->post('id');
        $groupId=Yii::$app->request->post('groupid');
        $groupSubjects=Subjects::find()->where([
          'fk_class_id'=>$classid,
          'fk_group_id'=>($id)?$id:null,
          'fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all();
        if(!empty($groupSubjects)){
           $options = "<option value=''>Select Subject</option>";
        foreach($groupSubjects as $subject)
        {
            $options .= "<option value='".$subject->id."'>".$subject->title."</option>";
        }
        return json_encode(['getSubjectsdata'=>$options]);
        }
    
   }

   /*===========home task =======*/
   public function actionTask() 
    { 
      $searchModel = new HomeTaskSearch();
      $searchModel->fk_branch_id = Yii::$app->common->getBranch();
      if(yii::$app->user->identity->fk_role_id !=1){
      $searchModel->user_id = Yii::$app->user->id;
      }
      $searchModel->date=date('Y-m-d');
      if(count(Yii::$app->request->get())== 0 ){
            $searchModel->id = 0;
        }else{
            if(empty(Yii::$app->request->get('HomeTaskSearch')['class_id'])){
                $searchModel->id = 0;
            }
        }
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
      $query=HomeTask::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch()]); 
      /* $dataProvider = new ActiveDataProvider([
                'query' => $query,
                
            ]);*/
        return $this->render('task-index', [ 
            'dataProvider' => $dataProvider, 
            'searchModel' => $searchModel,
        ]); 
    } 

    /*for parent portal*/
    public function actionParentTask() 
    { 
      $searchModel = new HomeTaskSearch();
      $searchModel->fk_branch_id = Yii::$app->common->getBranch();
      $student_details=\app\models\StudentInfo::find()->where(['user_id'=>Yii::$app->user->id,'is_active'=>1])->one();
      $searchModel->class_id = $student_details->class_id;
      $searchModel->date=date('Y-m-d');
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
      $query=HomeTask::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch()]); 
        return $this->render('task-parent', [ 
            'dataProvider' => $dataProvider, 
            'searchModel' => $searchModel,
        ]); 
    }
    /*for parent portal ends*/
   public function actionTaskForm() 
    { 
        $model = new HomeTask(); 
        if ($model->load(Yii::$app->request->post())) {
             $model->save();
             Yii::$app->session->setFlash('success', 'Task successfully created.');
            return $this->redirect(['task']);
        } else { 
            return $this->render('home_task', [ 
                'model' => $model, 
            ]); 
        } 
    }
    /*admin task*/ 
    public function actionAdminTask() 
    { 
        $model = new HomeTask(); 
        if ($model->load(Yii::$app->request->post())) {
            return $this->redirect(['admin-task','c_id'=>$model->class_id,'g_id'=>$model->group_id]);
        } else { 
            return $this->render('admin_task', [ 
                'model' => $model, 
            ]); 
        } 
    } 
    public function actionSaveAdminTask(){
      $data = Yii::$app->request->post('HomeTask');
      $subject_id=$data['subject_id'];  
      $class_id=$data['class_id'];
      $group_id=$data['group_id'];
      $class_work=$data['class_work'];
      $home_task=$data['home_task'];
      $teacher_id=$data['teacher_id'];
      $remarks=$data['remarks'];
      $date=$data['date'];
      $fk_branch_id=$data['fk_branch_id'];
      $user_id=$data['user_id'];
      foreach ($subject_id as $key => $subjectValue) {
        $homeTaskDetails=\app\models\HomeTask::find()->where(['subject_id'=>$subjectValue,'date'=>$date,'class_id'=>$class_id,'group_id'=>($group_id)?$group_id:null])->one();
        if(count($homeTaskDetails)>0){
          $model=\app\models\HomeTask::find()->where(['subject_id'=>$subjectValue,'date'=>$date,'class_id'=>$class_id,'group_id'=>($group_id)?$group_id:null])->one();
        }else{
        $model= new HomeTask(); 
          
        }
        $model->subject_id=$subjectValue;
        $model->class_id=$class_id;
        $model->group_id=$group_id;
        $model->date=$date;
        $model->fk_branch_id=$fk_branch_id;
        $model->user_id=$user_id;
        $model->class_work=$class_work[$key];
        $model->home_task=$home_task[$key];
        $model->teacher_id=$teacher_id[$key];
        $model->remarks=$remarks[$key];
        if($model->save()){
          Yii::$app->session->setFlash('success', 'Task successfully created..!');
           $this->redirect(['task']);
          }else{
            Yii::$app->session->setFlash('success', 'Oops..! Some issue occur');
          }
      }
      //echo '<pre>';print_r($_POST);
    }
    /*admin task ends*/ 
    public function actionUpdate($id) 
    { 
        $model = $this->findModel($id); 

        if ($model->load(Yii::$app->request->post()) && $model->save()) { 
          Yii::$app->session->setFlash('success', 'Task successfully Updated.');
            return $this->redirect(['task']); 
        } else { 
            return $this->render('home_task', [ 
                'model' => $model, 
            ]); 
        } 
    } 
    public function actionDelete($id) 
    { 
        $this->findModel($id)->delete(); 
        Yii::$app->session->setFlash('success', 'Task successfully Deleted.');
        return $this->redirect(['task']); 
    }
    public function actionSendTask(){
      $smsActive=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
      $data=Yii::$app->request->post('HomeTaskSearch');
      $class_id=$data['class_id'];
      if(isset($data['group_id'])){
      $group_id=$data['group_id'];
      }else{
        $group_id=null;
      }
      $homeTaskDetails=\app\models\HomeTask::find()->where(['date'=>date('Y-m-d'),'class_id'=>$class_id,'group_id'=>($group_id)?$group_id:null])->all();

      $where= [
                'student_info.class_id'=>$class_id,
                'student_info.group_id'=>($group_id)?$group_id:null,
                'student_info.is_active'=>1,

            ];
            $query= StudentInfo::find()->where($where)->all();
      /*$contact_no[]=923469475085;
      $varArray=[];
        $variable='';*/
      foreach ($query as $key => $details) {
       $details->studentParentsInfos->contact_no;
        $homeTask=\app\models\HomeTask::find()->where(['date'=>date('Y-m-d'),'class_id'=>$details->class_id,'group_id'=>($details->group_id)?$details->group_id:null])->all();
        $taskcomobine='';
        foreach ($homeTask as $key => $homeTaskvalue) {
          //$taskcomobine.=$homeTaskvalue->home_task .',\n';
         $taskcomobine.=$homeTaskvalue->subject->title .': '.$homeTaskvalue->home_task .',\n';
         $contactArray[$details->studentParentsInfos->contact_no]=$taskcomobine;
        }
      }
      $getClassName=\app\models\RefClass::find()->select('title')->where(['class_id'=>$data['class_id']])->one();
       $className=$getClassName->title;
      foreach ($contactArray as $key => $value) {
        $displayClassName='Home Task of '.$className.'\n'.$value;
        $send=Yii::$app->common->SendSmsSimple($key,$displayClassName);
      }
      Yii::$app->session->setFlash('success', 'Successfully send.');
      $this->redirect(['task']);      

    } //end of action
   /*===========home task ends===*/
     protected function findModel($id) 
    { 
        if (($model = HomeTask::findOne($id)) !== null) { 
            return $model; 
        } else { 
            throw new NotFoundHttpException('The requested page does not exist.'); 
        } 
    } 
    public function actionColors()
  { 
      $update=Colors::find()->where(['id'=>1])->one();
      if($update){
      $model=Colors::find()->where(['id'=>$update->id])->one();
      }else{
      $model=new Colors();
      }
   if ($model->load(Yii::$app->request->post())) {
       $model->save();
      return $this->render('colors',['model'=>$model]);
      }
    return $this->render('colors',['model'=>$model]);
  }
  public function actionSupport(){
    $model=new \app\models\TodoList();
    if(Yii::$app->request->post()){
      $data=Yii::$app->request->post();
      print_r($data);die;
    }else{

    return $this->render('support',['model'=>$model]);
    }
  }
  public function actionAll(){
      $model=new User();
      return $this->render('all',['model'=>$model]); 
    }
  public function actionAllStudents(){
    $data=Yii::$app->request->post('User');
    $username=$data['username'];
    $where = "username LIKE '".$username."%'";
      $query=User::find()->where(['status'=>'active','fk_role_id'=>3,'fk_branch_id'=>Yii::$app->common->getBranch()])->andWhere($where);
      $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                     'pageSize' => 100,
                    ],
                ]);
       return  $view= $this->render('all-students', [ 
            'dataProvider' => $dataProvider, 
            'username' => $username, 
        ]); 
    }

    public function actionTodayFine(){
      $todayFine=\app\models\FineDetail::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'created_date'=>date('Y-m-d')])->all();
      return $this->renderPartial('today-fine',['fine'=>$todayFine]);
    }
    

} // end of class
