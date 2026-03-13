<?php

namespace app\controllers;

use Yii;
use app\models\ClassTimetable;
use app\models\RefClass;
use app\models\RefGroup;
use app\models\Subjects;
use app\models\search\ClassTimetableSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use mPDF;

/**
 * ClassTimetableController implements the CRUD actions for ClassTimetable model.
 */
class ClassTimetableController extends Controller
{
    /**
     * @inheritdoc
     */
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

    /**
     * Lists all ClassTimetable models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ClassTimetableSearch();
        $searchModel->fk_branch_id = Yii::$app->common->getBranch();
        if(count(Yii::$app->request->get())== 0 ){
            $searchModel->id = 0;
        }else{
            if(empty(Yii::$app->request->get('ClassTimetableSearch')['class_id'])){
                $searchModel->id = 0;
            }
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ClassTimetable model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ClassTimetable model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ClassTimetable();

        if ($model->load(Yii::$app->request->post())) {
           // echo '<pre>';print_r($_POST);die;

            $ClassTimetable=yii::$app->request->post('ClassTimetable');
            $dayname=$ClassTimetable['day'];
            $daynameCnvrt=implode(',', $dayname);
            /*$startime=$ClassTimetable['start_date'];
            $endtime=$ClassTimetable['end_date'];
            $class_id=$ClassTimetable['class_id'];

            if(empty($ClassTimetable['group_id'])){
            $group_id=null;
            
            }else{
                 $group_id=$ClassTimetable['group_id'];
            }
            $subject_id=$ClassTimetable['subject_id'];*/

            /*foreach( $dayname as $key => $value ) {
        $model= new ClassTimetable;
        $model->subject_id=$subject_id;
        $model->fk_branch_id=yii::$app->common->getBranch();
        $model->class_id=$class_id;
        $model->group_id=$group_id;
        $model->start_date=$startime;
        $model->end_date=$endtime;
        $model->day =$daynameCnvrt;
           if($model->save()){
                Yii::$app->session->setFlash('success', "Timetable Successfully saved");
             $this->redirect(['index']);
             }else{
             print_r($model->getErrors());die;
             }
      }*/
      $model->day =$daynameCnvrt;
      if($model->save()){
                Yii::$app->session->setFlash('success', "Timetable Successfully saved");
             $this->redirect(['index']);
             }else{
             print_r($model->getErrors());die;
             }

            ///////////////////
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ClassTimetable model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
           $ClassTimetable=yii::$app->request->post('ClassTimetable');
            //$dayname=$ClassTimetable['day'];
            //$daynameCnvrt=implode(',', $dayname);
            /*$dayname=$ClassTimetable['day'];
            $startime=$ClassTimetable['start_date'];
            $endtime=$ClassTimetable['end_date'];
            $class_id=$ClassTimetable['class_id'];
            $subject_id=$ClassTimetable['subject_id'];

            foreach( $dayname as $key => $value ) {
        $model->subject_id=$subject_id;
        $model->start_date=$startime;
        $model->end_date=$endtime;
        $model->day =$value;*/
       // $model->day =$daynameCnvrt;
           if($model->save()){
                Yii::$app->session->setFlash('success', "Timetable Successfully saved");
             $this->redirect(['index']);
             }else{
             print_r($model->getErrors());die;
             }

            
        }  else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionSearchtimetable(){
        $model = new ClassTimetable();
        $model->checktimetableshow=1;
       return $this->render('searchtimetable', [
                'model' => $model,
            ]);
    }

    public function actionSearchTimetableShow(){
         $classid=yii::$app->request->post('classid');
         $groupid=yii::$app->request->post('groupid');
         $subjectid=yii::$app->request->post('subjectid');
        $subjectsdetails = ClassTimetable::find()
                    ->where([
                        'fk_branch_id'  =>Yii::$app->common->getBranch(),
                        'class_id'   => $classid,
                        'subject_id'=>$subjectid,
                        'group_id'   => ($groupid)?$groupid:null,
                        ])->all();
        if(count($subjectsdetails)>0){
            $renderSearchView=$this->renderAjax('getsearchtimetable',['classid'=>$classid,'subjectid'=>$subjectid,'groupid'=>$groupid,'subjectsdetails'=>$subjectsdetails]);
        }else{
            $renderSearchView="<div class='col-md-6'><div class='Alert alert-warning'><strong><center> No Timetable Found !</center></strong></div> </div>"; 
        }
        return json_encode(['renderSearchView'=>$renderSearchView]);
    }

    public function actionSearchTimetableClassShow(){
         $classid=yii::$app->request->post('classid');
         $groupid=yii::$app->request->post('groupid');
        $subjectsdetails = ClassTimetable::find()
                    ->where([
                        'fk_branch_id'  =>Yii::$app->common->getBranch(),
                        'class_id'   => $classid,
                        'group_id'   => ($groupid)?$groupid:null,
                        ])->all();
        if(count($subjectsdetails)>0){
            $renderSearchView=$this->renderAjax('getsearchtimetableClass',['classid'=>$classid,'groupid'=>$groupid,'subjectsdetails'=>$subjectsdetails]);
        }else{
            $renderSearchView="<div class='col-md-6'><div class='Alert alert-warning'><strong><center> No Timetable Found !</center></strong></div> </div>"; 
        }
        return json_encode(['renderSearchView'=>$renderSearchView]);
    }

    public function actionGenerateTimetablePdf(){
          $classid=yii::$app->request->get('classid');
          $groupid=yii::$app->request->get('groupid');
          $subjectid=yii::$app->request->get('subjectid');
          $classname=RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'class_id'=>$classid ])->one();
           $groupname=RefGroup::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'group_id'=>$groupid])->one();
           $subjectname=Subjects::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'id'=>$subjectid])->one();
           $classname=strtoupper($classname->title);
           if(!empty($groupname)){
           $groupname=strtoupper($groupname->title);
           }
           $subjectname=($subjectname->title);
          $subjectsdetails = ClassTimetable::find()
                    ->where([
                        'fk_branch_id'  =>Yii::$app->common->getBranch(),
                        'class_id'   => $classid,
                        'subject_id'=>$subjectid,
                        'group_id'   => ($groupid)?$groupid:null,
                        ])->all();

            $renderSearchView=$this->renderAjax('timetablepdf',['classid'=>$classid,'subjectid'=>$subjectid,'groupid'=>$groupid,'subjectsdetails'=>$subjectsdetails]);
        
        //echo $renderSearchView;die;
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        /*$stylesheet = file_get_contents('css/pdf.css');
        $mpdf->WriteHTML($stylesheet,1);*/

        $mpdf->WriteHTML("<h3 style='text-align:center'>Timetable for the Class($classname)-$groupname-Subject($subjectname)</h3>");
        $mpdf->WriteHTML($renderSearchView);
        $mpdf->Output('Class Subject Timetable-'.date("d-m-Y").'.pdf', 'D'); 
    }

    public function actionGenerateTimetableClassPdf(){
          $classid=yii::$app->request->get('classid');
          $groupid=yii::$app->request->get('groupid');
          $classname=RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'class_id'=>$classid ])->one();
           $groupname=RefGroup::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'group_id'=>$groupid])->one();
           ;
           $classname=strtoupper($classname->title);
           if(!empty($groupname)){
           $groupname=strtoupper($groupname->title);
           }
           
          $subjectsdetails = ClassTimetable::find()
                    ->where([
                        'fk_branch_id'  =>Yii::$app->common->getBranch(),
                        'class_id'   => $classid,
                        'group_id'   => ($groupid)?$groupid:null,
                        ])->all();

            $renderSearchView=$this->renderAjax('timetableclasspdf',['classid'=>$classid,'groupid'=>$groupid,'subjectsdetails'=>$subjectsdetails]);
        
        //echo $renderSearchView;die;
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        /*$stylesheet = file_get_contents('css/pdf.css');
        $mpdf->WriteHTML($stylesheet,1);*/

        $mpdf->WriteHTML("<h3 style='text-align:center'>Timetable for the Class($classname)-$groupname</h3>");
        $mpdf->WriteHTML($renderSearchView);
        $mpdf->Output('Class Wise Timetable-'.date("d-m-Y").'.pdf', 'D'); 
    }

    /**
     * Deletes an existing ClassTimetable model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ClassTimetable model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ClassTimetable the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ClassTimetable::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
