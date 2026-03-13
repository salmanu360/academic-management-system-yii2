<?php
namespace app\controllers;
ini_set('max_execution_time', 300);
use app\models\ExamType;
use app\models\RefClass;
use app\models\StudentInfo;
use app\models\EmployeeInfo;
use app\models\StudentMarks;
use Yii;
use app\models\Exam; 
use app\models\ExamQuiz;  
use app\models\ExamQuizType;  
use app\models\search\ExamsSearch;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use mPDF;
class ExamsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
        ];
    }
    public function actionPdf(){
            $this->layout = 'pdf';
           $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
           $mpdf->WriteHTML('test');
           $mpdf->Output('roll-no-slips-'.date('Y-m-d H:i:s').'.pdf', 'D'); 
    }

    public function actiontests(){
        return $this->render('test');
    }
    /*search exam details*/
    public function actionExamDetails()
    {
        $model = new Exam();
        return $this->render('exam-details', [
            'model' => $model,
        ]);
    }
    public function actionRollNo()
    {
        $model = new Exam();
        return $this->render('roll-no', [
            'model' => $model,
        ]);
    }
    public function actionRollNoStudents()
    {
        $data=Yii::$app->request->post();
        $this->redirect(['roll-no-slip-pdf',
         'data' => $data,
     ]);
    }
    public function actionRollNoSlipPdf(){
        $data=Yii::$app->request->get('data');
        //echo '<pre>';print_r($data);die;
         $exam_id=$data['id'];
        $year=$data['year'];
        $class_id=$data['class_id'];
        $group_id=$data['group_id'];
        $section_id=$data['section_id'];
        $getStudents=StudentInfo::find()->where([
                  'fk_branch_id'  =>Yii::$app->common->getBranch(),
                  'class_id'   => $class_id,
                  'group_id'   => ($group_id)?$group_id:null,
                  'section_id' => $section_id,
                  'is_active'  =>1,
                  ])->orderBy(['roll_no'=>SORT_ASC])->all();
        $exam_type=ExamType::find()->where(['id'=>$exam_id])->one();
        
        if($exam_id == 'Select Exam'){
            $roll_no_view='<div class="alert alert-danger">No records found..!</div>';
        }else{
            $roll_no_view= $this->renderAjax('_ajax/pdfs/roll-no-print', [ //roll-no-students (old)
            'getStudents' => $getStudents,
            'class_id' => $class_id,
            'group_id' => $group_id,
            'section_id' => $section_id,
            'exam_type' => $exam_type,
           ]);
           // echo $roll_no_view;die;
            $this->layout = 'pdf';
           $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
           $mpdf->WriteHTML($roll_no_view);
           $mpdf->Output('roll-no-slips-'.date('Y-m-d H:i:s').'.pdf', 'D'); 
        }
        return json_encode(['roll_no_view'=>$roll_no_view]);
    }
    public function actionRollNoPdf()
    {
      
        $exam_data = Yii::$app->request->post('StudentInfo');
         //echo '<pre>';print_r($exam_data);die;
        $student_id = $exam_data['stu_id'];
        $active = $exam_data['is_active'];
         foreach ($active as $key => $activeValue) {
            //echo $activeValue;
          // echo "<option value=".$activeValue.">".$key."</option>";
         }
        //echo $active;
    }
    public function actionIndex()
    {    
        $searchModel = new ExamsSearch();
        $searchModel->fk_branch_id = Yii::$app->common->getBranch();
        if(count(Yii::$app->request->get())== 0 ){
            $searchModel->id = 0;
        }else{
            if(empty(Yii::$app->request->get('ExamsSearch')['fk_exam_type']) && empty(Yii::$app->request->get('ExamsSearch')['fk_class_id'])){
                $searchModel->id = 0;
            }
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        $model= $this->findModel($id);
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Exam : ".$model->fkExamType->type,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }
    public function actionCreateExam(){
        $request = Yii::$app->request;
        $model = new Exam();
        if ($model->load($request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create-exam', [
                'model' => $model,
            ]);
        }
    }
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Exam();  

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Create new Exam",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Create new Exam",
                    'content'=>'<span class="text-success">Create Exam success</span>',
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Create new Exam",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
            
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Update Exam #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Exam #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Update Exam #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            return $this->redirect(['index']);
        }
    }
    public function actionBulkDelete()
    {        
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            return $this->redirect(['index']);
        }
    }
    
    /*get all subjects.*/
    public function actionAllSubjects(){
        if(Yii::$app->user->isGuest){
            return $this->goHome();
        }else{
            if(Yii::$app->request->isAjax){
                $examModel = new Exam();
                $examTypeModel = new ExamType();
                $data= Yii::$app->request->post();
                 $class_id = $data['class_id'];
                $group_id = $data['group_id'];
            $getSection=\app\models\RefSection::find()->where(['class_id'=>$class_id])->one();
                 //$group_id = null;
               $section_id = $getSection->section_id;
               // $section_id = $data['section_id'];

            $query = RefClass::find()->select([
                'ref_class.title as `class_name`',
                'ref_class.class_id as `class_id`',
                'g.group_id as `group_id`',
                'g.title as `group_name`',
                's.section_id as `section_id`',
                's.title as `section_name`',
                'sb.id as `subject_id`',
                'sb.title as `subject_name`',
                'sd.id as `sub_div_id`',
                'sd.title as `sub_div_title`'
            ])
                ->leftJoin('ref_group g', 'g.fk_class_id = ref_class.class_id')
                ->leftJoin('ref_section s', 's.class_id = ref_class.class_id')
                ->innerJoin('subjects sb', 'sb.fk_class_id = ref_class.class_id')
                ->leftJoin('subject_division sd', 'sd.fk_subject_id = sb.id')
                ->where([
                    'ref_class.class_id'=>$class_id,
                    'g.group_id'=>($group_id)?$group_id:null,
                    'sb.fk_group_id'=>($group_id)?$group_id:null,
                    's.section_id'=>$section_id
                ]);
               $model= $query->createCommand()->queryAll();
               //echo '<pre>';print_r($model);die;
                $details = $this->renderAjax('_ajax/get-subjects-data',['dataprovider'=>$model,'model'=>$examModel,'modelExamType'=>$examTypeModel]);
                return json_encode(['status'=>1 ,'details'=>$details]);
            }
        }
    }

    public function actionAllSubjectsBackup(){
        if(Yii::$app->user->isGuest){
            return $this->goHome();
        }else{
            if(Yii::$app->request->isAjax){
                $examModel = new Exam();
                $examTypeModel = new ExamType();
                $data= Yii::$app->request->post();
                 $class_id = $data['class_id'];
                 $group_id = $data['group_id'];
                $section_id = $data['section_id'];

            $query = RefClass::find()->select([
                'ref_class.title as `class_name`',
                'ref_class.class_id as `class_id`',
                'g.group_id as `group_id`',
                'g.title as `group_name`',
                's.section_id as `section_id`',
                's.title as `section_name`',
                'sb.id as `subject_id`',
                'sb.title as `subject_name`',
                'sd.id as `sub_div_id`',
                'sd.title as `sub_div_title`'
            ])
                ->leftJoin('ref_group g', 'g.fk_class_id = ref_class.class_id')
                ->leftJoin('ref_section s', 's.class_id = ref_class.class_id')
                ->innerJoin('subjects sb', 'sb.fk_class_id = ref_class.class_id')
                ->leftJoin('subject_division sd', 'sd.fk_subject_id = sb.id')
                ->where([
                    'ref_class.class_id'=>$class_id,
                    'g.group_id'=>($group_id)?$group_id:null,
                    's.section_id'=>$section_id
                ]);
               $model= $query->createCommand()->queryAll();
               //echo '<pre>';print_r($model);die;
                $details = $this->renderAjax('_ajax/get-subjects-data',['dataprovider'=>$model,'model'=>$examModel,'modelExamType'=>$examTypeModel]);
                return json_encode(['status'=>1 ,'details'=>$details]);
            }
        }
    }
    /*save exams*/
    public function actionSaveExams(){
        if(Yii::$app->request->isAjax){
            $exam_data = Yii::$app->request->post('Exam');
            $exam_type = Yii::$app->request->post('Exam')['fk_exam_type'];
            $exam_date = Yii::$app->request->post('ExamType')['exam_date']; 
            $exam_pass_percent = Yii::$app->request->post('ExamType')['passing_percentage']; 
            // $skip_in_schedule = Yii::$app->request->post('ExamType')['skip_in_schedule']; 

            $modalExamType = new ExamType();
            $row=[];
            $count = count($exam_data['fk_class_id']);
            if(!empty($exam_type)){
                $modalExamType->fk_branch_id=Yii::$app->common->getBranch();
                $modalExamType->type = $exam_type;
                $modalExamType->exam_date = $exam_date;
                $modalExamType->passing_percentage = $exam_pass_percent;
                $modalExamType->save();
                if(!$modalExamType->save()){echo "<pre>";print_r($modalExamType->getErrors());exit;}
                $new_examtype_id = $modalExamType->id;
            }
            for($i=0; $i<$count;$i++){
                if($exam_data['do_not_create'][$i] == 0){
                    $row[]=[
                        Yii::$app->common->getBranch(),
                        $exam_data['fk_class_id'][$i],
                        ($exam_data['fk_group_id'][$i])?$exam_data['fk_group_id'][$i]:null,
                        //$exam_data['fk_section_id'][$i],
                        $exam_data['fk_subject_id'][$i],
                        ($exam_data['fk_subject_division_id'][$i])?$exam_data['fk_subject_division_id'][$i]:null,
                        $exam_data['total_marks'][$i],
                        $exam_data['passing_marks'][$i],
                        date('Y-m-d H:i:s',strtotime($exam_data['start_date'][$i])),
                       // date('Y-m-d H:i:s',strtotime($exam_data['end_date'][$i])),
                        $new_examtype_id,
                        $exam_data['do_not_create'][$i],
                        $exam_data['skip_in_schedule'][$i],
                        date('Y-m-d H:i:s')
                    ];
                }
            }
            Yii::$app->db->createCommand()->batchInsert('exam', ['fk_branch_id','fk_class_id', 'fk_group_id','fk_subject_id','fk_subject_division_id','total_marks','passing_marks','start_date','fk_exam_type','do_not_create','skip_in_schedule','created_date'],$row)->execute();
          
          /*Yii::$app->db->createCommand()->batchInsert('exam', ['fk_branch_id','fk_class_id', 'fk_group_id','fk_subject_id','fk_subject_division_id','total_marks','passing_marks','start_date','end_date','fk_exam_type','do_not_create','skip_in_schedule','created_date'],$row)->execute();*/
            return json_encode(['status'=>1,'redirect_url'=>Url::to(['/exams/exam-details'],true)]);
        }
    }

    /*Award List Index page.*/
    public function actionAwardList(){
        $request = Yii::$app->request;
        $model = new Exam();
        /*
           *   Process for non-ajax request
           */
        return $this->render('award-list', [
            'model' => $model,
        ]);
    }
    /*get all exams.*/
    public function actionGetExams(){
        if(Yii::$app->user->isGuest){
            return $this->goHome();
        }else{
            if(Yii::$app->request->isAjax){
                $examModel = new Exam();
                $data= Yii::$app->request->post();
               $class_id = $data['class_id'];
                $group_id = $data['group_id'];
                //$section_id = $data['section_id'];
                $year = $data['year'];
                /*query*/
                //$examQuery=ExamType::find()->where(['like','exam_date',$year])->all();
                $examQuery = Exam::find()
                    ->select(['exam.fk_class_id','exam.fk_group_id','exam.fk_section_id','exam.fk_exam_type'])
                    ->leftJoin('exam_type et','et.id=exam.fk_exam_type')
                    ->where([
                        'exam.fk_branch_id'  =>Yii::$app->common->getBranch(),
                        'exam.fk_class_id'   => $class_id,
                        'exam.fk_group_id'   => ($group_id)?$group_id:null,
                        //'exam.fk_section_id' => $section_id,
                        'year(et.exam_date)'  =>$year,

                    ])->groupBy(['exam.fk_class_id','exam.fk_group_id'
                    //,'exam.fk_section_id'
                    ,'exam.fk_exam_type']);
                $dataprovider = new ActiveDataProvider([
                   'query'=>$examQuery,
                ]);
                $details = $this->renderAjax('_ajax/get-exam-data',[
                    'dataprovider'=>$dataprovider,
                    //'examQuery'=>$examQuery,
                    'model'=>$examModel,
                    'class_id'=>$class_id,
                    'group_id'=>$group_id,
                    //'section_id'=>$section_id
                ]);

                return json_encode(['status'=>1 ,'details'=>$details]);
            }
        }
    }
    /*get subjects from fk_exams_Type*/
    public function actionGetExamsSubjects(){
        if(Yii::$app->user->isGuest){
            return $this->goHome();
        }else {
            if (Yii::$app->request->isAjax) {
                $data = Yii::$app->request->post();
                //print_r($data);exit;
                $exam_model= Exam::find()->where([
                    'fk_branch_id'  =>Yii::$app->common->getBranch(),
                    'fk_class_id'   =>$data['class_id'],
                    'fk_group_id'   =>(isset($data['group_id']))?$data['group_id']:null,
                    'fk_section_id' =>$data['section_id'],
                    'fk_exam_type'  =>$data['exam_type_id'],
                ]);
                $dataprovider = new ActiveDataProvider([
                    'query'=>$exam_model,
                    /*'pagination' => [
                        'pageSize' => 2,
                    ],*/
                ]);
                $details = $this->renderAjax('_ajax/get-exam-subjects',['dataprovider'=>$dataprovider]);

                return json_encode(['status'=>1 ,'details'=>$details]);

            }
        }
    }
    public function actionGetAwardList(){
        if(Yii::$app->user->isGuest){
            return $this->goHome();
        }
        else
        {
            if (Yii::$app->request->isAjax) {
                $modelExam = $this->findModel(Yii::$app->request->post('exam_id'));
               $section_id=Yii::$app->request->post('section_id');
                $model = new StudentMarks();
                $where=[];
                if($modelExam->fk_subject_division_id){
                    $where= [
                        'sd.id'=>$modelExam->fk_subject_division_id,
                       // 'se.section_id'=>$modelExam->fk_section_id,
                        'student_info.section_id'=>$section_id,
                        'sb.id'=>$modelExam->fk_subject_id,
                        'student_info.group_id'=>($modelExam->fk_group_id)?$modelExam->fk_group_id:null,
                        'student_info.fk_branch_id'=>Yii::$app->common->getBranch(),
                    ];
                }
                else{
                    $where= [
                        'sb.id'=>$modelExam->fk_subject_id,
                        'sd.id'=>null,
                        //'se.section_id'=>$modelExam->fk_section_id,
                        'student_info.section_id'=>$section_id,
                        'student_info.fk_branch_id'=>Yii::$app->common->getBranch(),
                        'student_info.group_id'=>($modelExam->fk_group_id)?$modelExam->fk_group_id:null,
                        'student_info.is_active'=>1,
                    ];
                }
                $query= StudentInfo::find()
                    ->select(['student_info.stu_id'])
                    ->innerJoin('user u', 'u.id=student_info.user_id')
                    ->innerJoin('ref_section se', 'se.section_id=student_info.section_id')
                    ->innerJoin('subjects sb', 'sb.fk_class_id=student_info.class_id')
                    ->leftJoin('subject_division sd', 'sd.fk_subject_id=sb.id')
                    ->where($where)
                    ->orderBy(['student_info.roll_no'=>SORT_ASC]);
                $exam_model= $query->createCommand()->queryAll();
                $examDetails = $this->renderAjax('_ajax/award-list-form',[
                    'dataprovider'  =>$exam_model,
                    'modelExam'     =>$modelExam,
                    'model'         =>$model,
                    'section_id'         =>$section_id
                ]);
                return json_encode(['status'=>1,'details'=>$examDetails]);
            }
        }
    }
    
    public function actionSaveAwardList(){
        if(Yii::$app->request->isAjax){
            $award_list_data = Yii::$app->request->post('StudentMarks');
            $exam_id = $award_list_data['fk_exam_id'];
            $row=[];
            $count = count($award_list_data['fk_student_id']);
            for($i=0; $i<$count;$i++){
                $student_id=$award_list_data['fk_student_id'][$i];
                $find = StudentMarks::find()->where(['fk_exam_id'=>$exam_id,'fk_student_id'=>$student_id])->one();
                if(count($find)>0){
                     $find->marks_obtained = $award_list_data['marks_obtained'][$i];
                    $find->remarks = $award_list_data['remarks'][$i];
                    $find->save();
                }else{
                    $row[]=[
                        $exam_id,
                        $student_id,
                        $award_list_data['marks_obtained'][$i],
                        $award_list_data['remarks'][$i],
                    ];
                }
            }
            if(count($row)>0){
                Yii::$app->db->createCommand()->batchInsert('student_marks', ['fk_exam_id','fk_student_id','marks_obtained','remarks'],$row)->execute();
            }
            return json_encode(['status'=>1,'redirect_url'=>Url::to(['/exams/award-list'],true)]);
        }
    }
    /*get exams list old editable*/
    /*public function actionGetExamsList(){
        if(Yii::$app->user->isGuest){
            return $this->goHome();
        }
        else {
            if (Yii::$app->request->isAjax) {
               $data    = Yii::$app->request->post();
               $group   =  (isset($data['group_id']))?$data['group_id']:null;
               $class_id=$data['class_id'];
               $group_id=$data['group_id'];
               $exam_id=$data['exam_id'];
                $searchModel = new ExamsSearch();
                $searchModel->fk_branch_id  = Yii::$app->common->getBranch();
                $searchModel->fk_class_id   = $data['class_id'];
                $searchModel->fk_group_id   = (empty($group))?null:$group;
                // $searchModel->fk_section_id = $data['section_id'];
                $searchModel->fk_exam_type  = $data['exam_id'];
                $searchModel->do_not_create  = 0;
                $searchModel->skip_in_schedule=0;
                $exam_check = Exam::find()->where([
                    'fk_class_id'   => $data['class_id'],
                    'fk_group_id'   => (empty($group))?null:$group,
                    // 'fk_section_id' => $data['section_id'],
                    'fk_exam_type'  => $data['exam_id']
                ])->count();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                // sort asc script
                $dataProvider->sort->enableMultiSort = true;
                $dataProvider->sort->defaultOrder = [
                    'start_date' => SORT_ASC,
                    //'end_date' => SORT_ASC,
                ];
                // end of sort asc script
                if($exam_check>0){
                    $html =  $this->renderAjax('index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'class_id' => $class_id,
                        'group_id' => $group_id,
                        // 'section_id' => $section_id,
                        'exam_id' => $exam_id,
                    ]);
                }else{
                    $html = '<div class="col-md-12"><div class="alert alert-warning">No Records Found.</div></div>';
                }
                return json_encode(['views'=>$html]);
            }
        }
    }*///end of function
    public function actionGetExamsList(){
        if(Yii::$app->user->isGuest){
            return $this->goHome();
        }
        else {
            if (Yii::$app->request->isAjax) {
               $data    = Yii::$app->request->post();
               $model=new Exam();
               $model2=new ExamType();
               $group   =  (isset($data['group_id']))?$data['group_id']:null;
               $class_id=$data['class_id'];
               $group_id=$data['group_id'];
               $exam_id=$data['exam_id'];
               $examData = Exam::find()->where([
                    'fk_class_id'   => $data['class_id'],
                    'fk_group_id'   => (empty($group))?null:$group,
                    'fk_exam_type'  => $data['exam_id'],
                    'fk_branch_id'  => Yii::$app->common->getBranch()
                ])->all();
               $examType=ExamType::findOne(['id'=>$exam_id]);
                if(count($examData)>0){
                    $html =  $this->renderAjax('timetable', [
                        'class_id' => $class_id,
                        'group_id' => $group_id,
                        'examData' => $examData,
                        'exam_id' => $exam_id,
                        'model' => $model,
                        'examType' => $examType,
                        'model2' => $model2,
                    ]);
                }else{
                    $html = '<div class="col-md-12"><div class="alert alert-warning">No Records Found.</div></div>';
                }
                return json_encode(['views'=>$html]);
            }
        }
    }
    public function actionUpdateExam(){
        $data=Yii::$app->request->post('Exam');
        $exam_type=Yii::$app->request->post('ExamType');
        // examtypeTable
        $examId=$exam_type['id'];
        $examType=ExamType::findOne(['id'=>$examId]);
        $examType->type=$exam_type['type'];
        $examType->exam_date=$exam_type['exam_date'];
        $examType->passing_percentage=$exam_type['passing_percentage'];
        $examType->save();
        // examtypeTable end
        $examId=$data['fk_exam_type'];
        $total_marks=$data['total_marks'];
        $passing_marks=$data['passing_marks'];
        $start_date=$data['start_date'];
        foreach ($total_marks as $key => $total) {
            $model = Exam::find()->where(['id'=>$key])->one();
            $model->total_marks=$total;
            $model->passing_marks=$passing_marks[$key];
            $model->start_date=date('Y-m-d H:i:s',strtotime($start_date[$key]));
            if(!$model->save()){print_r($model->getErrors());}
            Yii::$app->session->setFlash('success', "Successfully Updated.");
            $this->redirect(['exam-details']);
        }
        
    }
    public function actionDownloadExamSchedule(){
           $data = Yii::$app->request->get();
           $model=new Exam();
          $class_id=$data['cid'];
           $group_id=$data['gid'];
           $exam_id=$data['eid'];
           $examData = Exam::find()->where([
                    'fk_class_id'   => $class_id,
                    'fk_group_id'   => (empty($group_id))?null:$group_id,
                    'fk_exam_type'  => $exam_id,
                    'fk_branch_id'  => Yii::$app->common->getBranch()
                ])->orderBy(['start_date'=>SORT_ASC])->all();
            $examType=ExamType::findOne(['id'=>$exam_id]);
                $html =  $this->renderAjax('download-timetable', [
                        'class_id' => $class_id,
                        'group_id' => $group_id,
                        // 'section_id' => $section_id,
                        'exam_id' => $exam_id,
                        'examData' => $examData,
                        'exam_id' => $exam_id,
                        'examType' => $examType,
                        'model'=>$model
                    ]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($html);
        $indexx=$mpdf->Output('exam-schedule'.date("d-m-Y").'.pdf', 'D'); 
    }

        public function actionDatesheetSend(){
           $data = Yii::$app->request->get();
           $model=new Exam();
           $class_id=$data['cid'];
           $group_id=$data['gid'];
           $exam_id=$data['eid'];
           $examType=ExamType::findOne(['id'=>$exam_id]);
            $where= [
                'student_info.class_id'=>$class_id,
                'student_info.group_id'=>($group_id)?$group_id:null,
                'student_info.is_active'=>1,
            ];
            $query= StudentInfo::find()->where($where)->all();
            foreach ($query as $key => $details) {
               $examData = Exam::find()->where([
                    'fk_class_id'   => $class_id,
                    'fk_group_id'   => (empty($group_id))?null:$group_id,
                    'fk_exam_type'  => $exam_id,
                    'fk_branch_id'  => Yii::$app->common->getBranch()
                ])->all();
            $taskcomobine='';
            foreach ($examData as $key => $homeTaskvalue) {
              $taskcomobine.=strtoupper($homeTaskvalue->fkSubject->title).': '.date('d M-Y',strtotime($homeTaskvalue->start_date)) .',\n';
              $contactArray[$details->studentParentsInfos->contact_no]=$taskcomobine;
            }
            }
            $getClassName=\app\models\RefClass::find()->select('title')->where(['class_id'=>$class_id])->one();
            $className=$getClassName->title;
            foreach ($contactArray as $key => $value) {
           $displayClassName='DateSheet of '.$className.'.\n'.$value;
            $send=Yii::$app->common->SendSmsSimple($key,$displayClassName);
            }
            Yii::$app->session->setFlash('success', 'Date sheet successfully send.');
            $this->redirect(['exam-details']);   
        
    }
    public function actionDmcSend(){
        $data=Yii::$app->request->post();
        $class_id=$data['class_id'];
        if(isset($data['group_id'])){
            $group_id=$data['group_id'];
        }else{
            $group_id=null;
        }
        $section_id=$data['section_id'];
        $exam=$data['exam_id'];
        $studentToralMarks = [];
                if($class_id){
                    $students= StudentInfo::find()
                        ->select(['student_info.stu_id','student_info.is_active','student_info.roll_no sroll_no','u.username roll_no','u.id user_id'])
                        ->innerJoin('user u','u.id=student_info.user_id')
                        ->where(['student_info.fk_branch_id'=>Yii::$app->common->getBranch(),'student_info.class_id'=>$class_id,'student_info.group_id'=>($group_id)?$group_id:null,'student_info.section_id'=>$section_id,'student_info.is_active'=>1])
                        ->orderBy(['student_info.roll_no'=>SORT_ASC])
                        ->asArray()
                        ->all();
                    $subjects = Exam::find()
                        ->select([
                            'sb.id subject_id',
                            'sb.title subject',
                            'sum(exam.total_marks) total_marks'
                        ])
                        ->innerJoin('exam_type et','et.id=exam.fk_exam_type')
                        ->innerJoin('ref_class c','c.class_id=exam.fk_class_id')
                        ->leftJoin('ref_group g','g.group_id=exam.fk_group_id ')
                        ->leftJoin('ref_section s','s.section_id=exam.fk_section_id')
                        ->innerJoin('subjects sb','sb.id=exam.fk_subject_id and c.class_id = sb.fk_class_id and g.group_id=sb.fk_group_id')
                        ->where(['et.id'=>$exam, 'c.class_id'=>$class_id,'g.group_id'=>($group_id)?$group_id:null,'s.section_id'=>$section_id])
                        ->groupBy(['sb.title','sb.id'])->asArray()->all();
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
                                ->where(['et.id'=>$exam,'exam.fk_branch_id'=>Yii::$app->common->getBranch(),'c.class_id'=>$class_id,'g.group_id'=>($group_id)?$group_id:null,'s.section_id'=>$section_id,'st.stu_id'=>$stu_id['stu_id'],'st.is_active'=>1])
                                ->groupBy(['st.stu_id','c.class_id','c.title','g.group_id','g.title','s.section_id','s.title','sb.title'])->asArray()->all();
                            if(count($subjects_data)>0){
                                $sumTotalMarks = 0;
                                $studentexam_arr[$stu_id['stu_id']]['student_id']=$stu_id['roll_no'];
                                $studentexam_arr[$stu_id['stu_id']]['student_roll_no']=$stu_id['sroll_no'];
                                $studentexam_arr[$stu_id['stu_id']]['name']=$stu_id['user_id'];
                                $std = $stu_id['stu_id'];
                                //$passing_marks_array=[];
                                foreach ($subjects_data as $indata){
                                    if($std == $stu_id['stu_id']){
                                        $sumTotalMarks  =  $sumTotalMarks + floatval($indata['marks_obtained']);
                                        $studentToralMarks [$stu_id['stu_id']] = $sumTotalMarks;
                                    }
                                    $studentexam_arr[$stu_id['stu_id']][] = floatval($indata['marks_obtained']);
                                    $studentexam_arr[$stu_id['stu_id']]['passing_marks'] = floatval($indata['passing_marks']);
                                    if($skey==0){
                                        $examsubjects_arr['heads'][] = $indata['subject'];
                                        $examsubjects_arr['total_marks'][] = $indata['total_marks'];
                                        $examsubjects_arr['passing_marks'][] = $indata['passing_marks'];

                                    }
                                    if($std != $stu_id['stu_id']){
                                        $sumTotalMarks  = 0;
                                    }
                                }
                            }
                        }
                        natcasesort($studentToralMarks);
                        $sortArr = array_reverse($studentToralMarks, true);
                        $position  = [];
                        $counter= 1;
                        $stdMarks = 0;
                        foreach($sortArr as $key=>$totalStdObtainMarks){
                            if($stdMarks ==0){
                                $stdMarks = $totalStdObtainMarks;
                            }
                            if($stdMarks == $totalStdObtainMarks){
                                $position[] = ['position'=>$counter,'student_id'=>$key,'marks'=>$totalStdObtainMarks];
                            }else{
                                $counter = $counter+1;
                                 $position[] = ['position'=>$counter,'student_id'=>$key,'marks'=>$totalStdObtainMarks];
                            }
                            $stdMarks = $totalStdObtainMarks;
                        }
                        $examtype = ExamType::findOne($exam);
                     if(count($subjects_data)>0){
                       $details = $this->renderAjax('dmc_send_sms',[
                            'query'=>$studentexam_arr,
                            'subjects'=>$subjects,
                            'class_id'=>$class_id,
                            'group_id'=>($group_id)?$group_id:null,
                            'section_id'=>$section_id,
                            'examtype'=>$examtype,
                            'heads_marks'=>$examsubjects_arr,
                            'positions'=>$position
                        ]);
                   }else{
                    $details="<div class='alert alert-danger'>No details found</div>";
                   }
                     return json_encode(['status'=>1 ,'views'=>$details]);


    }
    }
    }
    public function actionSmsClass(){
        $model = new Exam();
        return $this->render('send-sms-dmc', [
            'model' => $model,
        ]);
    }
    public function actionSendDmcSms(){
        
        $data=Yii::$app->request->post('marks');
        $total_marks=Yii::$app->request->post('total_marks');
        $class_id=Yii::$app->request->post('class');
        $class=RefClass::findOne(['class_id'=>$class_id]);
        $class_name=$class->title;
        foreach ($data as $key => $value) {
             $stu_id=$key;
             $taskcomobine='';
            foreach ($value as $subject => $marks) {
                $taskcomobine.=$subject .': '.$marks .',\n';
                $contactArray[$key]=$taskcomobine;
               
            }
        }
        foreach ($contactArray as $key => $value) {
        $studentAlldetails = \app\models\User::find()
            ->select(['student_info.stu_id',"concat(user.first_name, ' ' ,  user.last_name) as name",'student_parents_info.contact_no as parentcontact'])
            ->innerJoin('student_info','student_info.user_id = user.id')
            ->innerJoin('student_parents_info','student_info.stu_id = student_parents_info.stu_id')
            ->where(['student_info.stu_id'=>$key])->asArray()->one();
          $studenName= $studentAlldetails['name'];
          $totalMarks=$total_marks[$key];
           $studentParents= Yii::$app->common->getParent($key);
            $parentContact=$studentParents->contact_no;
           $displayClassName='DMC of '.$studenName .'('.$class_name.'). '.$value .' Total Marks:'.$totalMarks;
           $send=Yii::$app->common->SendSms($parentContact,$displayClassName,$key);
        }
         Yii::$app->session->setFlash('success', 'Marks successfully send.');
          return $this->render('success');
       //echo  Html::a('label', ['sms-class'], ['class'=>'btn btn-primary']);
    }
   public function actionEditexam(){
                if(Yii::$app->request->post('hasEditable'))
                {
                    $examId=Yii::$app->request->post('editableKey');
                    $examEditableModel=Exam::findOne($examId);
                    $out=Json::encode(['output'=>'','message'=>'']);
                    $post=[];
                    $posted=current($_POST['Exam']);
                    $post['Exam']=$posted;
                    if($examEditableModel->load($post))
                    {
                        if(!$examEditableModel->save(false)){print_r($examEditableModel->getErrors());}
                        //$output='my value';
                        //$out=Json::encode(['output'=>$output,'message'=>'']);
                    }
                    //echo $out;
                    return $out;
                }
    }
    /*get exam type against year*/
    public function actionGetExamByYear(){
      $year=Yii::$app->request->post('year');
      $class_id=Yii::$app->request->post('class_id');
      $group_id=Yii::$app->request->post('group_id');
     // $section_id=Yii::$app->request->post('section_id');
      $examYear=Exam::find()->select(['fk_exam_type'])->where(['fk_class_id'=>$class_id,'fk_group_id'=>($group_id)?$group_id:null])->andWhere(['like','start_date',$year])->distinct()->all();
      /*$examYear=Exam::find()->select(['fk_exam_type'])->where(['fk_class_id'=>$class_id,'fk_group_id'=>($group_id)?$group_id:null,'fk_section_id'=>$section_id])->andWhere(['like','start_date',$year])->distinct()->all();*/
     // echo '<pre>';print_r($examYear);die;
      $options = "<option>Select Exam</option>";
      foreach($examYear as $getType)
      {
        $options .= "<option value='".$getType->fk_exam_type."'>".$getType->fkExamType->type."</option>";
      }
      return $options;
    }//end of group
     

    /*exams dmc*/
    public function actionDmc(){
        $request = Yii::$app->request;
        $model = new Exam();
        /*
           *   Process for non-ajax request
           */
        return $this->render('dmc', [
            'model' => $model,
        ]);
    }
    /*clsass group section wise dmc*/
    public function actionCgsDmc(){
        if(Yii::$app->user->isGuest){
            return $this->redirect(['site/login']);
        }else{
            $examsModel = new Exam();
            $data = Yii::$app->request->Post();
            if($data){
                $query = Exam::find()
                    ->select(['exam.fk_exam_type id','et.type title'])
                    ->innerJoin('exam_type et','et.id=exam.fk_exam_type')
                    ->where([
                        'exam.fk_branch_id'=>Yii::$app->common->getBranch(),
                        'exam.fk_class_id'=>$data['class_id'],
                        'exam.fk_group_id'=>($data['group_id'])?$data['group_id']:null,
                        'exam.fk_section_id'=>$data['section_id']
                    ])
                    ->groupBy('fk_exam_type')
                    ->asArray()->all();

                $exam_array = ArrayHelper::map($query, 'id', 'title');


                $details = $this->renderAjax('_ajax/cgs-dmc',[
                    'examModel'=>$examsModel,
                    'exams'=>$exam_array,
                    'class_id'=>$data['class_id'],
                    'group_id'=>($data['group_id'])?$data['group_id']:null,
                    'section_id'=>$data['section_id']
                ]);

                return json_encode(['status'=>1 ,'details'=>$details]);
            }
        }
    } // end of function
    /*start of past dmc*/
    public function actionDmcPast(){
        $request = Yii::$app->request;
        $model = new Exam();
        /*
           *   Process for non-ajax request
           */
        return $this->render('dmc-past', [
            'model' => $model,
        ]);
    }
    public function actionCgsDmcPast(){
        if(Yii::$app->user->isGuest){
            return $this->redirect(['site/login']);
        }else{
            $examsModel = new Exam();
            $data = Yii::$app->request->Post();
            if($data){
                $query = Exam::find()
                    ->select(['exam.fk_exam_type id','et.type title'])
                    ->innerJoin('exam_type et','et.id=exam.fk_exam_type')
                    ->where([
                        'exam.fk_branch_id'=>Yii::$app->common->getBranch(),
                        'exam.fk_class_id'=>$data['class_id'],
                        'exam.fk_group_id'=>($data['group_id'])?$data['group_id']:null,
                        'exam.fk_section_id'=>$data['section_id']
                    ])
                    ->groupBy('fk_exam_type')
                    ->asArray()->all();

                $exam_array = ArrayHelper::map($query, 'id', 'title');


                $details = $this->renderAjax('_ajax/cgs-dmc-past',[
                    'examModel'=>$examsModel,
                    'exams'=>$exam_array,
                    'class_id'=>$data['class_id'],
                    'group_id'=>($data['group_id'])?$data['group_id']:null,
                    'section_id'=>$data['section_id']
                ]);

                return json_encode(['status'=>1 ,'details'=>$details]);
            }
        }
    } 
    public function actionStdDmcPast(){
        if(Yii::$app->request->isAjax){

            $dataUnserialized = parse_str(Yii::$app->request->post('data'), $serialize_data);
            //echo '<pre>';print_r(Yii::$app->request->post('data'));die;
            $class_id = $serialize_data['class_id'];
            $group_id = $serialize_data['group_id'];
            $section_id = $serialize_data['section_id'];
            $exam = $serialize_data['Exam']['fk_exam_type'];
            $status = 0;
            $tabId = '';
            $details_html='';
            if($serialize_data['tab_type']=='#Single-Examination'){

                $examid =  $exam[1];
                $tabId = 'Single-Examination';
                /*total students in class*/
                $students= StudentInfo::find()
                    ->select(['student_info.stu_id','student_info.is_active','u.username roll_no','u.id user_id'])
                    ->innerJoin('user u','u.id=student_info.user_id')
                    ->innerJoin('stu_reg_log_association st',' st.fk_stu_id=student_info.stu_id')
                    ->where(['student_info.fk_branch_id'=>Yii::$app->common->getBranch(),'st.current_class_id'=>$class_id,'st.current_group_id'=>($group_id)?$group_id:null,'st.current_section_id'=>$section_id])
                    ->asArray()
                    ->all();
                       $exams_students = Exam::find()
                    ->select([
                        'si.stu_id','si.is_active','concat(u.first_name," ",u.middle_name," ",u.last_name) student_name'
                    ])
                    ->innerJoin('exam_type et','et.id=exam.fk_exam_type')
                    ->innerJoin('ref_class c','c.class_id=exam.fk_class_id')
                    ->innerJoin('subjects sb','sb.id=exam.fk_subject_id')
                    ->leftJoin('student_marks sm','sm.fk_exam_id=exam.id')
                    ->leftJoin('stu_reg_log_association st',' st.fk_stu_id=sm.fk_student_id')
                    ->leftJoin('student_info si',' si.stu_id=sm.fk_student_id')
                    ->leftJoin('ref_group g','g.group_id=exam.fk_group_id')
                    ->leftJoin('ref_section s','s.class_id=exam.fk_class_id')
                    ->innerJoin('user u','u.id=si.user_id')
                    ->where(['et.id'=>$examid,'exam.fk_branch_id'=>Yii::$app->common->getBranch(),'st.current_class_id'=>$class_id,'st.current_group_id'=>($group_id)?$group_id:null,'st.current_section_id'=>$section_id,'si.is_active'=>1])
                    ->groupBy(['st.fk_stu_id'])
                    ->asArray()
                    ->all();
                   // echo '<pre>';print_r($exams_students);die;
                /*selected student resutl.*/
                    
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
                            ->where(['et.id'=>$exam,'exam.fk_branch_id'=>Yii::$app->common->getBranch(),'c.class_id'=>$class_id,'g.group_id'=>($group_id)?$group_id:null,'s.section_id'=>$section_id,'st.stu_id'=>$stu_id['stu_id'],'st.is_active'=>1 ])
                            ->groupBy(['st.stu_id','c.class_id','c.title','g.group_id','g.title','s.section_id','s.title','sb.title'])->asArray()->all();
                           // echo '<pre>';print_r($subjects_data);die;
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
                $details_html = $this->renderAjax('_ajax/std-dmc-list-past',[
                    'exam_std_query'=>$exams_students,
                    'tab_type'       => 'single',
                    'class_id'       => $class_id,
                    'group_id'       => $group_id,
                    'section_id'     => $section_id,
                    'exam_id'        => $examid,
                    'positions'      => $position
                ]);

            }
            if($serialize_data['tab_type']=='#Multiple-Examination'){

               $exam_Arr = $exam[2];
                $tabId = 'Multiple-Examination';
                $status = 1;
            }
            if($serialize_data['tab_type']=='#Class-Wise-Examination'){

                $tabId = 'Class-Wise-Examination';
                $studentToralMarks = [];
                if($class_id){
                    $students= StudentInfo::find()
                        ->select(['student_info.stu_id','student_info.is_active','student_info.roll_no sroll_no','u.username roll_no','u.id user_id'])
                        ->innerJoin('user u','u.id=student_info.user_id')
                        ->where(['student_info.fk_branch_id'=>Yii::$app->common->getBranch(),'student_info.class_id'=>$class_id,'student_info.group_id'=>($group_id)?$group_id:null,'student_info.section_id'=>$section_id,'student_info.is_active'=>1])
                        ->orderBy(['student_info.roll_no'=>SORT_ASC])
                        ->asArray()
                        ->all();

                    /*total subjects*/
                    $subjects = Exam::find()
                        ->select([
                            'sb.id subject_id',
                            'sb.title subject',
                            'sum(exam.total_marks) total_marks'
                        ])
                        ->innerJoin('exam_type et','et.id=exam.fk_exam_type')
                        ->innerJoin('ref_class c','c.class_id=exam.fk_class_id')
                        ->leftJoin('ref_group g','g.group_id=exam.fk_group_id ')
                        ->leftJoin('ref_section s','s.section_id=exam.fk_section_id')
                        ->innerJoin('subjects sb','sb.id=exam.fk_subject_id and c.class_id = sb.fk_class_id and g.group_id=sb.fk_group_id')
                        ->where(['et.id'=>$exam, 'c.class_id'=>$class_id,'g.group_id'=>($group_id)?$group_id:null,'s.section_id'=>$section_id])
                        ->groupBy(['sb.title','sb.id'])->asArray()->all();
                    if(count($students)){
                        $studentexam_arr=[];
                        $examsubjects_arr=[];
                        foreach ($students as  $skey=>$stu_id){
                            //echo '<pre>';print_r($stu_id);die;
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
                                ->where(['et.id'=>$exam,'exam.fk_branch_id'=>Yii::$app->common->getBranch(),'c.class_id'=>$class_id,'g.group_id'=>($group_id)?$group_id:null,'s.section_id'=>$section_id,'st.stu_id'=>$stu_id['stu_id'],'st.is_active'=>1])
                                ->groupBy(['st.stu_id','c.class_id','c.title','g.group_id','g.title','s.section_id','s.title','sb.title'])->asArray()->all();
                           
                                //echo '<pre>';print_r($subjects_data);die;
                            if(count($subjects_data)>0){
                                $sumTotalMarks = 0;
                                $studentexam_arr[$stu_id['stu_id']]['student_id']=$stu_id['roll_no'];
                                $studentexam_arr[$stu_id['stu_id']]['student_roll_no']=$stu_id['sroll_no'];
                                $studentexam_arr[$stu_id['stu_id']]['name']=$stu_id['user_id'];
                                $std = $stu_id['stu_id'];
                                //$passing_marks_array=[];
                                foreach ($subjects_data as $indata){
                                    if($std == $stu_id['stu_id']){
                                        $sumTotalMarks  =  $sumTotalMarks + floatval($indata['marks_obtained']);
                                        $studentToralMarks [$stu_id['stu_id']] = $sumTotalMarks;
                                    }
                                    $studentexam_arr[$stu_id['stu_id']][] = floatval($indata['marks_obtained']);
                                    //$passing_marks_array[]=$indata['passing_marks'];
                                   // $studentexam_arr[$stu_id['stu_id']][] = floatval($indata['passing_marks']);
                                    if($skey==0){
                                        $examsubjects_arr['heads'][] = $indata['subject'];
                                        $examsubjects_arr['total_marks'][] = $indata['total_marks'];
                                    }

                                    /*sum condition*/
                                    if($std != $stu_id['stu_id']){
                                        $sumTotalMarks  = 0;
                                    }
                                }
                                //echo '<pre>';print_r($passing_marks_array);die;
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

                       //print_r($position);
                        //echo Yii::$app->common->multidimensional_search($position, ['student_id'=>276]);
                        $examtype = ExamType::findOne($exam);
                        $details_html = $this->renderAjax('/reports/academics/class_wise_resultsheet',[
                            'query'=>$studentexam_arr,
                            //'passing_marks_array'=>$passing_marks_array,
                            'subjects'=>$subjects,
                            'class_id'=>$class_id,
                            'group_id'=>($group_id)?$group_id:null,
                            'section_id'=>$section_id,
                            'examtype'=>$examtype,
                            'heads_marks'=>$examsubjects_arr,
                            'positions'=>$position
                        ]);
                        //echo $details_html;die;
                        if(count($studentexam_arr)>0){
                            $status = 1;
                        }
                    }
                }
            }
            if($status == 1){
                return json_encode(['status'=>$status,'html'=>$details_html,'tabId'=>$tabId]);
            }else{
                return json_encode(['status'=>$status,'html'=>'<strong>Records Not found</strong>','tabId'=>$tabId]);
            }
        }
    }
    /*end of past dmc*/
    /*std dmc*/
    public function actionStdDmc(){
        if(Yii::$app->request->isAjax){

            $dataUnserialized = parse_str(Yii::$app->request->post('data'), $serialize_data);
            //echo '<pre>';print_r(Yii::$app->request->post('data'));die;
            $class_id = $serialize_data['class_id'];
            $group_id = $serialize_data['group_id'];
            $section_id = $serialize_data['section_id'];
            $exam = $serialize_data['Exam']['fk_exam_type'];
            $status = 0;
            $tabId = '';
            $details_html='';
            if($serialize_data['tab_type']=='#Single-Examination'){

                $examid =  $exam[1];
                $tabId = 'Single-Examination';
                /*total students in class*/
                $students= StudentInfo::find()
                    ->select(['student_info.stu_id','student_info.is_active','u.username roll_no','u.id user_id'])
                    ->innerJoin('user u','u.id=student_info.user_id')
                    ->where(['student_info.fk_branch_id'=>Yii::$app->common->getBranch(),'student_info.class_id'=>$class_id,'student_info.group_id'=>($group_id)?$group_id:null,'student_info.section_id'=>$section_id,'student_info.is_active'=>1])
                    ->asArray()
                    ->all();
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
                    ->where(['et.id'=>$examid,'exam.fk_branch_id'=>Yii::$app->common->getBranch(),'st.class_id'=>$class_id,'st.group_id'=>($group_id)?$group_id:null,'st.section_id'=>$section_id,'st.is_active'=>1])
                    ->groupBy(['st.stu_id'])
                    ->asArray()
                    ->all();
                   // echo '<pre>';print_r($exams_students);die;
                /*selected student resutl.*/
                    
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
                            ->where(['et.id'=>$exam,'exam.fk_branch_id'=>Yii::$app->common->getBranch(),'c.class_id'=>$class_id,'g.group_id'=>($group_id)?$group_id:null,'s.section_id'=>$section_id,'st.stu_id'=>$stu_id['stu_id'],'st.is_active'=>1 ])
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
                $details_html = $this->renderAjax('_ajax/std-dmc-list',[
                    'exam_std_query'=>$exams_students,
                    'tab_type'       => 'single',
                    'class_id'       => $class_id,
                    'group_id'       => $group_id,
                    'section_id'     => $section_id,
                    'exam_id'        => $examid,
                    'positions'      => $position
                ]);

            }
            if($serialize_data['tab_type']=='#Multiple-Examination'){

               $exam_Arr = $exam[2];
                $tabId = 'Multiple-Examination';
                $status = 1;
            }
            if($serialize_data['tab_type']=='#Class-Wise-Examination'){

                $tabId = 'Class-Wise-Examination';
                $studentToralMarks = [];
                if($class_id){
                    $students= StudentInfo::find()
                        ->select(['student_info.stu_id','student_info.is_active','student_info.roll_no sroll_no','u.username roll_no','u.id user_id'])
                        ->innerJoin('user u','u.id=student_info.user_id')
                        ->where(['student_info.fk_branch_id'=>Yii::$app->common->getBranch(),'student_info.class_id'=>$class_id,'student_info.group_id'=>($group_id)?$group_id:null,'student_info.section_id'=>$section_id,'student_info.is_active'=>1])
                        ->orderBy(['student_info.roll_no'=>SORT_ASC])
                        ->asArray()
                        ->all();

                    /*total subjects*/
                    $subjects = Exam::find()
                        ->select([
                            'sb.id subject_id',
                            'sb.title subject',
                            'sum(exam.total_marks) total_marks'
                        ])
                        ->innerJoin('exam_type et','et.id=exam.fk_exam_type')
                        ->innerJoin('ref_class c','c.class_id=exam.fk_class_id')
                        ->leftJoin('ref_group g','g.group_id=exam.fk_group_id ')
                        ->leftJoin('ref_section s','s.section_id=exam.fk_section_id')
                        ->innerJoin('subjects sb','sb.id=exam.fk_subject_id and c.class_id = sb.fk_class_id and g.group_id=sb.fk_group_id')
                        ->where(['et.id'=>$exam, 'c.class_id'=>$class_id,'g.group_id'=>($group_id)?$group_id:null,'s.section_id'=>$section_id])
                        ->groupBy(['sb.title','sb.id'])->asArray()->all();
                    if(count($students)){
                        $studentexam_arr=[];
                        $examsubjects_arr=[];
                        foreach ($students as  $skey=>$stu_id){
                            //echo '<pre>';print_r($stu_id);die;
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
                                ->where(['et.id'=>$exam,'exam.fk_branch_id'=>Yii::$app->common->getBranch(),'c.class_id'=>$class_id,'g.group_id'=>($group_id)?$group_id:null,'s.section_id'=>$section_id,'st.stu_id'=>$stu_id['stu_id'],'st.is_active'=>1])
                                ->groupBy(['st.stu_id','c.class_id','c.title','g.group_id','g.title','s.section_id','s.title','sb.title'])->asArray()->all();
                           
                                //echo '<pre>';print_r($subjects_data);die;
                            if(count($subjects_data)>0){
                                $sumTotalMarks = 0;
                                $studentexam_arr[$stu_id['stu_id']]['student_id']=$stu_id['roll_no'];
                                $studentexam_arr[$stu_id['stu_id']]['student_roll_no']=$stu_id['sroll_no'];
                                $studentexam_arr[$stu_id['stu_id']]['name']=$stu_id['user_id'];
                                $std = $stu_id['stu_id'];
                                //$passing_marks_array=[];
                                foreach ($subjects_data as $indata){
                                    if($std == $stu_id['stu_id']){
                                        $sumTotalMarks  =  $sumTotalMarks + floatval($indata['marks_obtained']);
                                        $studentToralMarks [$stu_id['stu_id']] = $sumTotalMarks;
                                    }
                                    /*$studentexam_arr[$stu_id['stu_id']]['subject_marks'] = floatval($indata['marks_obtained']);*/
                                   // $studentexam_arr[$stu_id['stu_id']]['passing_marks'] = floatval($indata['passing_marks']);
                                    //$passing_marks_array[]=$indata['passing_marks'];
                                    $studentexam_arr[$stu_id['stu_id']][] = floatval($indata['marks_obtained']);
                                    $studentexam_arr[$stu_id['stu_id']]['passing_marks'] = floatval($indata['passing_marks']);
                                    if($skey==0){
                                        $examsubjects_arr['heads'][] = $indata['subject'];
                                        $examsubjects_arr['total_marks'][] = $indata['total_marks'];
                                        $examsubjects_arr['passing_marks'][] = $indata['passing_marks'];
                                    }

                                    /*sum condition*/
                                    if($std != $stu_id['stu_id']){
                                        $sumTotalMarks  = 0;
                                    }
                                }
                                //echo '<pre>';print_r($passing_marks_array);die;
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

                       //print_r($position);
                        //echo Yii::$app->common->multidimensional_search($position, ['student_id'=>276]);
                        $examtype = ExamType::findOne($exam);
                        $details_html = $this->renderAjax('/reports/academics/class_wise_resultsheet',[
                            'query'=>$studentexam_arr,
                            //'passing_marks_array'=>$passing_marks_array,
                            'subjects'=>$subjects,
                            'class_id'=>$class_id,
                            'group_id'=>($group_id)?$group_id:null,
                            'section_id'=>$section_id,
                            'examtype'=>$examtype,
                            'heads_marks'=>$examsubjects_arr,
                            'positions'=>$position
                        ]);
                        //echo $details_html;die;
                        if(count($studentexam_arr)>0){
                            $status = 1;
                        }
                    }
                }
            }
            if($status == 1){
                return json_encode(['status'=>$status,'html'=>$details_html,'tabId'=>$tabId]);
            }else{
                return json_encode(['status'=>$status,'html'=>'<strong>Records Not found</strong>','tabId'=>$tabId]);
            }
        }
    }
    /*exam->dmc->student-dmc*/
    public function actionStudentDmc(){
        if(Yii::$app->request->isAjax){
            $data= Yii::$app->request->post();
            $student = Yii::$app->common->getStudent($data['stu_id']);
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
                ->where(['exam.fk_branch_id'=>Yii::$app->common->getBranch(),'c.class_id'=>$data['class_id'],'g.group_id'=>($data['group_id'])?$data['group_id']:null,'s.section_id'=>$data['section_id'],'st.stu_id'=>$data['stu_id'],'et.id'=>$data['exam_id'],'st.is_active'=>1 ])
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
                    'c.class_id'=>$data['class_id'],
                    'g.group_id'=>($data['group_id'])?$data['group_id']:null,
                    's.section_id'=>$data['section_id'],
                    'et.id'=>$data['exam_id'],
                    'st.is_active'=>1
                ])
                ->groupBy(['c.title','g.title','s.title','sb.title'])->asArray()->all();

            $examtype = ExamType::findOne($data['exam_id']);
            if(count($subjects_data)>0){

                $details_html = $this->renderAjax('_ajax/student-dmc',[
                    'student'=>$student,
                    'class_id'=>$data['class_id'],
                    'group_id'=>$data['group_id'],
                    'section_id'=>$data['section_id'],
                    'stu_id'=>$data['stu_id'],
                    'query' =>$subjects_data,
                    'exam_details'=>$examtype,
                    'branch_details'=>$branch_details,
                    'position'=>$data['stdPosition'],
                ]);
                $status =1;

            }else{
                $details_html = "<strong>Records not found.</strong>";
            }
            $total_class_subjects =[];
            $total_marks_subjects =[];
            foreach ($class_data as $key=>$total_class){
                $percentage = $subjects_data[$key]['marks_obtained']/$subjects_data[$key]['total_marks']*100;
                $total_marks_subjects [] = [round($percentage,2)];
                $total_class_subjects [] = $total_class['subject'];
            }
            return json_encode([
                'status'=>$status,
                'html'=>$details_html,
                'class_data'=>$class_data,
                'subject_data'=>$subjects_data,
                'total_subjects'=>$total_class_subjects,
                'total_marks_subjects'=>$total_marks_subjects,
                'position'=>$total_marks_subjects,
                'total_count'=>count($class_data)
            ]);
        }
        else{
            if(Yii::$app->request->get()){
                $data= Yii::$app->request->get();
                $student = Yii::$app->common->getStudent($data['stu_id']);
                $branch_details = Yii::$app->common->getBranchDetail();

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
                    ->where(['exam.fk_branch_id'=>Yii::$app->common->getBranch(),'c.class_id'=>$data['class_id'],'g.group_id'=>(isset($data['group_id']))?$data['group_id']:null,'s.section_id'=>$data['section_id'],'st.stu_id'=>$data['stu_id'],'et.id'=>$data['exam_id'],'st.is_active'=>1 ])
                    ->groupBy(['st.stu_id','c.class_id','c.title','g.group_id','g.title','s.section_id','s.title','sb.title'])->asArray()->all();
                $examtype = ExamType::findOne($data['exam_id']);

                if(count($subjects_data)>0){
                    $resultsheet = Yii::$app->common->getName($student->user_id).'-'.Yii::$app->common->getCGSName($data['class_id'],(isset($data['group_id']))?$data['group_id']:null,$data['section_id']).' - '.ucfirst($examtype->type);
                    if(Yii::$app->common->getBranch()== 64 || Yii::$app->common->getBranch()== 65) {
                        $details_html = $this->renderPartial('_ajax/pdfs/student-dmc-pdf-meesaq', [
                            'student' => $student,
                            'query' => $subjects_data,
                            'exam_details' => $examtype,
                            'branch_details' => $branch_details,
                            'position'=>$data['position']

                        ]);
                    }else{
                        $details_html = $this->renderPartial('_ajax/pdfs/student-dmc-pdf', [
                            'student' => $student,
                            'class_id'=>$data['class_id'],
                            'section_id'=>$data['section_id'],
                            'group_id'=>isset($data['group_id'])?$data['group_id']:'NA',
                            'stu_id'=>$data['stu_id'],
                            'query' => $subjects_data,
                            'exam_details' => $examtype,
                            'branch_details' => $branch_details,
                            'position'=>$data['position']

                        ]);
                    }
                   //echo $details_html;die();
                    /*$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@webroot/vendor/bower/bootstrap/dist').'/css/bootstrap.css';
                    $dash = explode('/mis/',$directoryAsset);*/
                    $this->layout = 'pdf';
                    //$mpdf = new mPDF('', 'A4');
                    $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
                    $stylesheet = file_get_contents('css/site.css');
                    $stylesheet .= file_get_contents('css/std-dmc-pdf.css');
                    $mpdf->WriteHTML($stylesheet,1);
                    $mpdf->WriteHTML($details_html,2);

                    $mpdf->Output('Result-sheet-'.$resultsheet.'.pdf', 'D');

                }
            }
        }
    }

    /*print position certificate*/
    public function actionStudentPositionCetificate(){
    if(Yii::$app->request->get()){
        $data= Yii::$app->request->get();
        $exam_id=$data['exam_id'];
        $class_id=$data['class_id'];
        $section_id=$data['section_id'];
        $position=$data['position'];
        $student = Yii::$app->common->getStudent($data['stu_id']);
        $branch_details = Yii::$app->common->getBranchDetail();
        $examtype = ExamType::findOne($data['exam_id']);
        $details_html = $this->renderPartial('_ajax/pdfs/position-certificate-pdf', [
                        'student' => $student,
                        'exam_details' => $examtype,
                        'branch_details' => $branch_details,
                        'position'=>$position

                        ]);
        //echo $details_html;die;
        $this->layout = 'pdf';
        $mpdf = new mPDF('c', 'A4-L');
        $mpdf->WriteHTML($details_html);
        $mpdf->Output('position-certificate-'.$resultsheet.'.pdf', 'D');
        }
    }
    /*end of print position certificate*/

    /*export all dmc*/
    public function actionExportAllDmc(){
        if(Yii::$app->user->isGuest){
            return $this->redirect('site/login');
        }else{
            if(Yii::$app->request->get()){
                $data= Yii::$app->request->get();
                $examid = $data['exam_id'];
                $class_id = $data['class_id'];
                $group_id = (isset($data['group_id']))?$data['group_id']:null;
                $section_id = $data['section_id'];

                /*$student = Yii::$app->common->getStudent($data['stu_id']);*/
                $branch_details = Yii::$app->common->getBranchDetail();
                /*exam students*/
                $exams_students = Exam::find()
                    ->select([
                        'st.stu_id','st.roll_no','st.user_id','st.is_active','concat(u.first_name," ",u.middle_name," ",u.last_name) student_name'
                    ])
                    ->innerJoin('exam_type et','et.id=exam.fk_exam_type')
                    ->innerJoin('ref_class c','c.class_id=exam.fk_class_id')
                    ->innerJoin('subjects sb','sb.id=exam.fk_subject_id')
                    ->leftJoin('student_marks sm','sm.fk_exam_id=exam.id')
                    ->leftJoin('student_info st',' st.stu_id=sm.fk_student_id')
                    ->leftJoin('ref_group g','g.group_id=exam.fk_group_id')
                    ->leftJoin('ref_section s','s.class_id=exam.fk_class_id')
                    ->innerJoin('user u','u.id=st.user_id')
                    ->where(['et.id'=>$examid,'exam.fk_branch_id'=>Yii::$app->common->getBranch(),'st.class_id'=>$class_id,'st.group_id'=>($group_id)?$group_id:null,'st.section_id'=>$section_id,'st.is_active'=>1])
                    ->groupBy(['st.stu_id'])->asArray()->all();
                if(count($exams_students)>0){
                    $resultsheet = Yii::$app->common->getCGSName($class_id,$group_id,$section_id).' - '.ucfirst($examid);
                    $this->layout = 'pdf';
                    //$mpdf = new mPDF('', 'A4');

                    $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
                    foreach($exams_students as $skey=>$students){
                        $student = Yii::$app->common->getStudent($students['stu_id']);
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
                            ->where(['exam.fk_branch_id'=>Yii::$app->common->getBranch(),'c.class_id'=>$class_id,'g.group_id'=>($group_id)?$group_id:null,'s.section_id'=>$section_id,'st.stu_id'=>$students['stu_id'],'et.id'=>$examid,'st.is_active'=>1 ])
                            ->groupBy(['st.stu_id','c.class_id','c.title','g.group_id','g.title','s.section_id','s.title','sb.title'])->asArray()->all();
                        $examtype = ExamType::findOne($examid);
                        /////////////////////////
                        if(count($subjects_data)>0){
                            $sumTotalMarks = 0;
                            $studentexam_arr[$students['stu_id']]['student_id']=$students['roll_no'];
                            $studentexam_arr[$students['stu_id']]['name']=$students['user_id'];
                            $std = $students['stu_id'];
                            //print_r($subjects_data);exit;
                            foreach ($subjects_data as $indata){
                                if($std == $students['stu_id']){
                                    $sumTotalMarks  =  $sumTotalMarks + $indata['marks_obtained'];
                                    $studentToralMarks [$students['stu_id']] = $sumTotalMarks;
                                }
                                $studentexam_arr[$students['stu_id']][] = $indata['marks_obtained'];
                                if($skey==0){
                                    $examsubjects_arr['heads'][] = $indata['subject'];
                                    $examsubjects_arr['total_marks'][] = $indata['total_marks'];
                                }

                                /*sum condition*/
                                if($std != $students['stu_id']){
                                    $sumTotalMarks  = 0;
                                }
                            }
                        }
                        natcasesort($studentToralMarks);
                    $sortArr = array_reverse($studentToralMarks, true);
                    $position  = [];
                    $counter= 1;
                    $stdMarks = 0;
                    //print_r($studentToralMarks);exit;
                    /*custom sort*/
                    foreach($sortArr as $key=>$totalStdObtainMarks){
                        if($stdMarks ==0){
                            $stdMarks = $totalStdObtainMarks;
                        }
                        if($stdMarks == $totalStdObtainMarks){
                            //echo $stdMarks.'----'.$totalStdObtainMarks.'----' .$counter."<br/>";
                             $position[$key] = ['position'=>$counter,'student_id'=>$key,'marks'=>$totalStdObtainMarks];
                        }else{
                            $counter = $counter+1;
                             $position[$key] = ['position'=>$counter,'student_id'=>$key,'marks'=>$totalStdObtainMarks];
                            //echo $stdMarks.'----'.$totalStdObtainMarks.'----' .$counter."-No pos - <br/>";
                        }
                     //   echo $data['class_id'];die;
                       //$pp= $data['stu'];
                        $stdMarks = $totalStdObtainMarks;
                    }
                        ////////////////////////
                            $details_html = $this->renderPartial('_ajax/pdfs/student-dmc-pdf', [
                                'student' => $student,
                                'query' => $subjects_data,
                                'exam_details' => $examtype,
                                'branch_details' => $branch_details,
                                //'position' =>  $position[$students['stu_id']]['position'],
                                'position' =>  $data['stu'],
                                'class_id' =>  $class_id,
                                'group_id' =>  $group_id,
                                'section_id' =>  $section_id,
                            ]);
                            //echo $details_html;die;
                            $mpdf->AddPage();
                            $stylesheet = file_get_contents('css/site.css');
                            $stylesheet .= file_get_contents('css/std-dmc-pdf.css');
                            $mpdf->WriteHTML($stylesheet,1);
                            $mpdf->WriteHTML($details_html,2);
                        
                    }
                    $mpdf->Output('Result-sheet-'.$resultsheet.'.pdf', 'D');
                }
            }
        }
    }
    /*generate blank awardlist*/
    public function actionGenerateBlankAwardlist(){
        if(Yii::$app->user->isGuest){
            return $this->goHome();
        }
        else
        {
            if (Yii::$app->request->get()) {
                $model = new StudentMarks();
                $exam_id  = base64_decode(Yii::$app->request->get('exam_id'));
                $section_id  = base64_decode(Yii::$app->request->get('section_id'));
                $modelExam = $this->findModel($exam_id);
                $type     = base64_decode(Yii::$app->request->get('type'));
                $where=[];
                if($modelExam->fk_subject_division_id){
                    $where= [
                        'sd.id'=>$modelExam->fk_subject_division_id,
                        // 'se.section_id'=>$modelExam->fk_section_id,
                        'se.section_id'=>$section_id,
                        'sb.id'=>$modelExam->fk_subject_id,
                        'student_info.fk_branch_id'=>Yii::$app->common->getBranch(),
                        'student_info.is_active'=>1,
                    ];
                }
                else{
                    $where= [
                        'sb.id'=>$modelExam->fk_subject_id,
                        'sd.id'=>null,
                        'se.section_id'=>$section_id,
                        'student_info.fk_branch_id'=>Yii::$app->common->getBranch(),
                        'student_info.is_active'=>1,
                    ];
                }
                $query= StudentInfo::find()
                    ->select(['student_info.stu_id'])
                    ->innerJoin('user u', 'u.id=student_info.user_id')
                    ->innerJoin('ref_section se', 'se.section_id=student_info.section_id')
                    ->innerJoin('subjects sb', 'sb.fk_class_id=student_info.class_id')
                    ->leftJoin('subject_division sd', 'sd.fk_subject_id=sb.id')
                    ->where($where)
                    ->orderBy(['student_info.roll_no'=>SORT_ASC]);
                $exam_model= $query->createCommand()->queryAll();
                if($type =='blank'){
                    $examDetails = $this->renderPartial('_ajax/pdfs/generate-blank-awardlist',[
                        'dataprovider'  =>$exam_model,
                        'modelExam'     =>$modelExam,
                        'model'         =>$model,
                        'type'         => $type,
                        'section_id'   => $section_id
                    ]);
                    $this->layout = 'pdf';
                    $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
                    $mpdf->WriteHTML($examDetails);
                    $mpdf->Output('Blank Award list.pdf', 'D');
                    //return json_encode(['status'=>1,'details'=>$examDetails]);
                }
                if($type =='std_marks'){

                    $examDetails = $this->renderPartial('_ajax/pdfs/generate-blank-awardlist',[
                        'dataprovider'  =>$exam_model,
                        'modelExam'     =>$modelExam,
                        'model'         =>$model,
                        'type'         => $type,
                        'section_id'   => $section_id
                    ]);
                    $this->layout = 'pdf';
                    $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
                    $mpdf->WriteHTML($examDetails);
                    $mpdf->Output('Student Award list.pdf', 'D');
                    //return json_encode(['status'=>1,'details'=>$examDetails]);
                }

            }
        }
    }
    public function actionGetQuizFillAwardlist(){
        $model=new ExamQuiz();
        $data=Yii::$app->request->get();
        // echo '<pre>';print_r($data);die;
        $subject_id=$data['subject_id'];
        if(empty($data['group_id'])){
                $group_id=null;
            }else{
                $group_id= $data['group_id'];
            }
        $class_id=$data['class_id'];
        $quiz_id=$data['quiz_id'];
        $subject_name=\app\models\Subjects::find()->where(['id'=>$subject_id])->one();
        $model2=ExamQuizType::find()->where(['id'=>$quiz_id,'fk_branch_id'=>Yii::$app->common->getBranch()])->one();
        $employeeDetails=EmployeeInfo::find()->where(['emp_id'=>$model2->teacher_id])->one();
        $class_details=\app\models\RefClass::find()->where(['class_id'=>$class_id])->one();
        $where= [
                'sb.id'=>$subject_id,
                'student_info.fk_branch_id'=>Yii::$app->common->getBranch(),
                'student_info.group_id'=>$group_id,
                'student_info.is_active'=>1,
                ];
        $query= StudentInfo::find()
                    ->select(['student_info.stu_id'])
                    ->innerJoin('user u', 'u.id=student_info.user_id')
                    ->innerJoin('ref_section se', 'se.section_id=student_info.section_id')
                    ->innerJoin('subjects sb', 'sb.fk_class_id=student_info.class_id')
                    ->leftJoin('subject_division sd', 'sd.fk_subject_id=sb.id')
                    ->where($where)
                    ->orderBy(['student_info.roll_no'=>SORT_ASC]);
                    $exam_model= $query->createCommand()->queryAll();
        if(!empty($quiz_id)){
            $quizView=$this->renderAjax('quiz/pdf/fill-quiz-students',['model'=>$model,'model2'=>$model2,'dataprovider'=>$exam_model,'class_id'=>$class_id,'group_id'=>$group_id,'subject_id'=>$subject_id,'employeeDetails'=>$employeeDetails,'subject_name'=>$subject_name,'class_details'=>$class_details]);
        }else{
            $quizView='<div class="alert alert-warning">No record found ..!</div>';
        }
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($quizView);
        $mpdf->Output('fill-quiz-award-list-'.date("d-m-Y").'.pdf', 'D'); 
        return json_encode(['quizGrid'=>$quizView]);
    }
    public function actionSaveQuiz(){
        $data=Yii::$app->request->post('ExamQuiz');
        $dataType=Yii::$app->request->post('ExamQuizType');
        //echo '<pre>';print_r($_POST);die;
        $obtained_marks_stu_id=$data['obtained_marks'];
        $remarks=$data['remarks'];
        $fk_class_id=$data['fk_class_id'];
        $fk_group_id=$data['fk_group_id'];
        $subject=$data['fk_subject_id'];
        $test_id=$data['test_id'];
      // echo '<pre>';print_r($data);die;

        $quizExists=ExamQuiz::find()->where(['test_id'=>$test_id])->one();
        if(count($quizExists)>0){
        $this->redirect(['updatequiz','data'=>$data]);
        }else{
        foreach ($obtained_marks_stu_id as $key => $obtained_marksValue) {
        $model=new ExamQuiz();
        /*if(count($model)>0){
        $model=ExamQuiz::find()->where(['test_id'=>$test_id])->one();
        }else{
        $model=new ExamQuiz();
        } */   
        $model->obtained_marks=$obtained_marksValue;
        $model->stu_id=$key;
        $model->remarks=$remarks[$key];
        $model->obtained_marks=$obtained_marksValue;
        $model->fk_branch_id=Yii::$app->common->getBranch();
        $model->fk_subject_id=$subject;
        $model->fk_class_id=$fk_class_id;
        $model->fk_group_id=$fk_group_id;
        $model->created_date=date('Y-m-d');
        $model->test_id=$test_id;
        $model->user_id=Yii::$app->user->id;
          // echo $obtained_marksValue;
           //echo '<br>';
           //echo $key;
        if($model->save()){
        Yii::$app->session->setFlash('success', 'Quiz successfully created.');
        $this->redirect(['quizs']);
        }else{print_r($model->getErrors());die;}
        }
        }
        //echo '<pre>';print_r($_POST);die;
    }
     public function actionUpdatequiz(){
            $award_list_data = Yii::$app->request->get('data');
            $exam_id = $award_list_data['test_id'];
            $obtained_stuid=$award_list_data['stu_id'];
            $row=[];
            $count = count($award_list_data['stu_id']);
            foreach ($obtained_stuid as $key => $i) {
                $student_id=$i;
                $find = ExamQuiz::find()->where(['test_id'=>$exam_id,'stu_id'=>$student_id])->one();
                if(count($find)>0){
                     $find->obtained_marks = $award_list_data['obtained_marks'][$i];
                    $find->remarks = $award_list_data['remarks'][$i];
                    if($find->save()){
                        Yii::$app->session->setFlash('success', 'Quiz successfully Updated.');
                        $this->redirect(['quizs']);
                    }else{
                        //print_r($find->getErrors());die;
                    };
                }else{
                    $row[]=[
                        $exam_id,
                        $student_id,
                        $award_list_data['obtained_marks'][$i],
                        $award_list_data['remarks'][$i],
                    ];
                }
            }
            if(count($row)>0){
                Yii::$app->db->createCommand()->batchInsert('exam_quiz', ['test_id','stu_id','obtained_marks','remarks'],$row)->execute();
            }       
    }
   
        /*second quiz*/
      public function actionQuizs() 
    { 
        $model = new ExamQuizType(); 

        if ($model->load(Yii::$app->request->post())) {
            $data=Yii::$app->request->post('ExamQuizType');
            if(empty($data['group_id'])){
                $group_id=null;
            }else{
                $group_id= $data['group_id'];
            }
            
            //($data['group_id'])?$data['group_id']:null
            //echo '<pre>';print_r($_POST);die;
            $exam_quiz_type_details=ExamQuizType::find()->where(['class_id'=>$data['class_id'],'group_id'=>$group_id,'subject_id'=>$data['subject_id'],'quiz_date'=>$data['quiz_date'],'teacher_id'=>$data['teacher_id']])->one();
            if(count($exam_quiz_type_details) > 0){
                return $this->redirect(['fill-quiz','id'=>base64_encode($exam_quiz_type_details->id)]); 
            }
            $model->save(); 
            Yii::$app->session->setFlash('success', 'Quiz successfully created.');
            return $this->redirect(['fill-quiz','id'=>base64_encode($model->id)]); 
        } else { 
            return $this->render('quiz/quizs', [ 
                'model' => $model, 
            ]); 
        } 
    } // end of first page of quiz
    public function actionFillQuiz($id){
        $model=new ExamQuiz();
        $id=base64_decode($id);
        if(!isset($_GET['class_id'])){
        /*$data=Yii::$app->request->post();
        $subject_id=$data['subject_id'];
        $section_id=$data['section_id'];
        $group_id=$data['group_id'];
        $class_id=$data['class_id'];
        $quiz_id=$data['quiz_id'];*/
        $model2=ExamQuizType::find()->where(['id'=>$id,'fk_branch_id'=>Yii::$app->common->getBranch()])->one();
        $employeeDetails=EmployeeInfo::find()->where(['emp_id'=>$model2->teacher_id])->one();
        $where= [
                'sb.id'=>$model2->subject_id,
                'student_info.fk_branch_id'=>Yii::$app->common->getBranch(),
                'student_info.group_id'=>($model2->group_id)?$model2->group_id:null,
                'student_info.is_active'=>1,
                ];
        $query= StudentInfo::find()
                    ->select(['student_info.stu_id'])
                    ->innerJoin('user u', 'u.id=student_info.user_id')
                    ->innerJoin('ref_section se', 'se.section_id=student_info.section_id')
                    ->innerJoin('subjects sb', 'sb.fk_class_id=student_info.class_id')
                    ->leftJoin('subject_division sd', 'sd.fk_subject_id=sb.id')
                    ->where($where)
                    ->orderBy(['student_info.roll_no'=>SORT_ASC]);
                    $exam_model= $query->createCommand()->queryAll();

            return $quizView=$this->render('quiz/quiz-students',['model'=>$model,'model2'=>$model2,'dataprovider'=>$exam_model,'employeeDetails'=>$employeeDetails]);
        //return json_encode(['quizGrid'=>$quizView]);
    }else{
         $getData=Yii::$app->request->get();
         $subject_id=base64_decode($getData['subject_id']);
         $section_id=base64_decode($getData['section_id']);
         $group_id=base64_decode($getData['group_id']);
         $class_id=base64_decode($getData['class_id']);
         $quizname=$getData['quizname'];
         $passing_marks=$getData['passing_marks'];
         $total_marks=$getData['total_marks'];
         $teacher_name=$getData['teacher_name'];
         $where= [
                'sb.id'=>$subject_id,
                'student_info.section_id'=>$section_id,
                'student_info.fk_branch_id'=>Yii::$app->common->getBranch(),
                'student_info.group_id'=>($group_id)?$group_id:null,
                'student_info.is_active'=>1,
                ];
        $subject_name=\app\models\Subjects::find()->where(['id'=>$subject_id])->one();
        $query= StudentInfo::find()
                    ->select(['student_info.stu_id'])
                    ->innerJoin('user u', 'u.id=student_info.user_id')
                    ->innerJoin('ref_section se', 'se.section_id=student_info.section_id')
                    ->innerJoin('subjects sb', 'sb.fk_class_id=student_info.class_id')
                    ->leftJoin('subject_division sd', 'sd.fk_subject_id=sb.id')
                    ->where($where)
                    ->orderBy(['student_info.roll_no'=>SORT_ASC]);
                    $exam_model= $query->createCommand()->queryAll();
        $quizView=$this->renderAjax('quiz/pdf/quiz-empty-awardlist',['teacher_name'=>$teacher_name,'passing_marks'=>$passing_marks,'total_marks'=>$total_marks,'quizname'=>$quizname,'model'=>$model,'dataprovider'=>$exam_model,'section_id'=>$section_id,'class_id'=>$class_id,'group_id'=>$group_id,'subject_name'=>$subject_name,'$model2'=>$model2]);
       // echo $quizView;die;
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($quizView);
        $mpdf->Output('empty-quiz-award-list-'.date("d-m-Y").'.pdf', 'D'); 
    }
    } // end of action 
    public function actionFillQuizPdf(){
        $model=new ExamQuiz();
         $getData=Yii::$app->request->get();
         $subject_id=base64_decode($getData['subject_id']);
         $group_id=base64_decode($getData['group_id']);
         $class_id=base64_decode($getData['class_id']);
         $quiz_id=$getData['quiz_id'];
         $passing_marks=$getData['passing_marks'];
         $total_marks=$getData['total_marks'];
         $teacher_name=$getData['teacher_name'];
          $model2=ExamQuizType::find()->where(['id'=>$quiz_id,'fk_branch_id'=>Yii::$app->common->getBranch()])->one();
         $where= [
                'sb.id'=>$subject_id,
                'student_info.fk_branch_id'=>Yii::$app->common->getBranch(),
                'student_info.group_id'=>($group_id)?$group_id:null,
                'student_info.is_active'=>1,
                ];
        $subject_name=\app\models\Subjects::find()->where(['id'=>$subject_id])->one();
        $class_details=\app\models\RefClass::find()->where(['class_id'=>$class_id])->one();
        $query= StudentInfo::find()
                    ->select(['student_info.stu_id'])
                    ->innerJoin('user u', 'u.id=student_info.user_id')
                    ->innerJoin('ref_section se', 'se.section_id=student_info.section_id')
                    ->innerJoin('subjects sb', 'sb.fk_class_id=student_info.class_id')
                    ->leftJoin('subject_division sd', 'sd.fk_subject_id=sb.id')
                    ->where($where)
                    ->orderBy(['student_info.roll_no'=>SORT_ASC]);
                    $exam_model= $query->createCommand()->queryAll();
        $quizView=$this->renderAjax('quiz/pdf/quiz-empty-awardlist',['teacher_name'=>$teacher_name,'passing_marks'=>$passing_marks,'total_marks'=>$total_marks,'model'=>$model,'dataprovider'=>$exam_model,'class_id'=>$class_id,'group_id'=>$group_id,'subject_name'=>$subject_name,'class_details'=>$class_details,'model2'=>$model2]);
       // echo $quizView;die;
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($quizView);
        $mpdf->Output('empty-quiz-award-list-'.date("d-m-Y").'.pdf', 'D'); 
   
    } // end of action
/*second quiz end*/
    /*================parent portal*/
     public function actionDmcParent()
    {
        $model = new Exam();
        $student=\app\models\StudentInfo::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
        $class_id=$student->class_id;
        $group_id=$student->group_id;
        $year=date('Y');
         $examYear=Exam::find()->select(['fk_exam_type'])->where(['fk_class_id'=>$class_id,'fk_group_id'=>($group_id)?$group_id:null])->andWhere(['like','start_date',$year])->distinct()->all();
        return $this->render('parent/dmc-parent', [
            'model' => $model,
            'student' => $student,
            'examYear' => $examYear,
        ]);
    }
     public function actionDmcIndex(){
        $request = Yii::$app->request;
        $model = new Exam();
        $student=\app\models\StudentInfo::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
        $class_id=$student->class_id;
        $group_id=$student->group_id;
        $year=date('Y');
         $examYear=Exam::find()->select(['fk_exam_type'])->where(['fk_class_id'=>$class_id,'fk_group_id'=>($group_id)?$group_id:null])->andWhere(['like','start_date',$year])->distinct()->all();
        return $this->render('parent/dmc-index', [
            'model' => $model,
            'student' => $student,
            'examYear' => $examYear,
        ]);
    }
 
    public function actionStdDmcParent(){
        if(Yii::$app->request->isAjax){
            $dataUnserialized = parse_str(Yii::$app->request->post('data'), $serialize_data);
           $postData=Yii::$app->request->post();
            $loginId=StudentInfo::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
            $class_id=$loginId->class_id;
            $group_id=$loginId->group_id;
            $section_id=$loginId->section_id;
            $status = 0;
            $tabId = '';
            $details_html='';
                $examid =  $postData['examid'];
                /*total students in class*/
                $students= StudentInfo::find()
                    ->select(['student_info.stu_id','student_info.is_active','u.username roll_no','u.id user_id'])
                    ->innerJoin('user u','u.id=student_info.user_id')
                    ->where(['student_info.fk_branch_id'=>Yii::$app->common->getBranch(),'student_info.class_id'=>$class_id,'student_info.group_id'=>($group_id)?$group_id:null,'student_info.section_id'=>$section_id,'student_info.is_active'=>1])
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
                    ->where(['et.id'=>$examid,'exam.fk_branch_id'=>Yii::$app->common->getBranch(),'st.class_id'=>$class_id,'st.group_id'=>($group_id)?$group_id:null,'st.section_id'=>$section_id,'st.is_active'=>1,'st.stu_id'=>$loginId->stu_id])
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
                     if(count($exams_students) == 0){
                        echo count($exams_students); die;
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
                $details_html = $this->renderAjax('parent/std-dmc-list-parent',[
                    'exam_std_query'=>$exams_students,
                    'tab_type'       => 'single',
                    'class_id'       => $class_id,
                    'group_id'       => $group_id,
                    'section_id'     => $section_id,
                    'exam_id'        => $examid,
                    'positions'      => $position
                ]);

            }
            //echo $details_html;die;
            
           
            if($status == 1){
                return json_encode(['status'=>$status,'html'=>$details_html,'tabId'=>'Single-Examination']);
            }else{
                return json_encode(['status'=>$status,'html'=>'<strong>Records Not found</strong>','tabId'=>$tabId]);
            }
    }
    /*==== exam scheduale*/
    public function actionGetExamsListParent(){
    if(Yii::$app->user->isGuest){
        return $this->goHome();
    }
    else {
        if (Yii::$app->request->isAjax) {
           $data    = Yii::$app->request->post();
            $student=\app\models\StudentInfo::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
            $class_id=$student->class_id;
            $group_id=$student->group_id;
            $exam_id=$data['exam_id'];
            $searchModel = new ExamsSearch();
            $searchModel->fk_branch_id  = Yii::$app->common->getBranch();
            $searchModel->fk_class_id   = $class_id;
            $searchModel->fk_group_id   = (empty($group_id))?null:$group_id;
            // $searchModel->fk_section_id = $data['section_id'];
            $searchModel->fk_exam_type  = $data['exam_id'];
            $searchModel->do_not_create  = 0;
            $searchModel->skip_in_schedule=0;
            $exam_check = Exam::find()->where([
                'fk_class_id'   => $class_id,
                'fk_group_id'   => (empty($group_id))?null:$group_id,
                // 'fk_section_id' => $data['section_id'],
                'fk_exam_type'  => $data['exam_id']
            ])->count();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            // sort asc script
            $dataProvider->sort->enableMultiSort = true;
            $dataProvider->sort->defaultOrder = [
                'start_date' => SORT_ASC,
                //'end_date' => SORT_ASC,
            ];
            // end of sort asc script
            if($exam_check>0){
                $html =  $this->renderAjax('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'class_id' => $class_id,
                    'group_id' => $group_id,
                    // 'section_id' => $section_id,
                    'exam_id' => $exam_id,
                ]);
            }else{
                $html = '<div class="col-md-12"><div class="alert alert-warning">No Records Found.</div></div>';
            }
            return json_encode(['views'=>$html]);
            }
        }
    }//end of function
    /*==== end of exam scheduale*/
    public function actionStudentQuizPortal(){
        $student=\app\models\StudentInfo::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
        $settings = Yii::$app->common->getBranchSettings();
        $sessionStartDate=$settings->current_session_start;
        $sessionEndDate=$settings->current_session_end;
        $quizResults = \app\models\ExamQuizType::find()
        ->select(['exam_quiz_type.*','eq.*'])
        ->innerJoin('exam_quiz eq','eq.test_id=exam_quiz_type.id')
        ->where([
            'eq.fk_branch_id'=>Yii::$app->common->getBranch(),
            'eq.stu_id'=>$student->stu_id,
        ])->andWhere(['between', 'exam_quiz_type.quiz_date', $sessionStartDate, $sessionEndDate])
        ->asArray()->all();
        return $this->render('quiz/student-portal-quiz',['quizResults'=>$quizResults]);
    }
    protected function findModel($id)
    {
        if (($model = Exam::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /*================end of parent portal*/
} // end of main class
