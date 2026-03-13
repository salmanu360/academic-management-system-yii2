<?php

namespace app\controllers;

use Yii;
use app\models\Messages;
use app\models\search\MessagesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MessagesController implements the CRUD actions for Messages model.
 */
class MessagesController extends Controller
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
     * Lists all Messages models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MessagesSearch();
        $searchModel->user_id=yii::$app->user->identity->id;
        $getMessageUser=Messages::find()->select('sender_id')->where(['fk_branch_id'=>yii::$app->common->getBranch(),'username'=>yii::$app->user->identity->id])->distinct()->all();
        //echo yii::$app->user->identity->id;die;
       // echo count($getMessageUser);die;
       // echo '<pre>';print_r($getMessageUser);die;
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'getMessageUser' => $getMessageUser,
        ]);
    }
    public function actionFeesms(){
         $date=date('Y-m');
         $studentFeeDetails=Yii::$app->db->createCommand("SELECT `s`.* from student_info `s` WHERE  is_active=1 and not exists( select `fs`.stu_id from `fee_submission` `fs` where fs.stu_id=s.stu_id and FIND_IN_SET('$date',fs.year_month_interval)) order by roll_no asc")->queryAll();
        if (Yii::$app->request->post()) {
            $smsActive=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
           $msg= Yii::$app->request->post('msg');
           foreach ($studentFeeDetails as $key => $value) {
            $parent = \app\models\StudentParentsInfo::find()->where(['stu_id'=>$value['stu_id']])->one();
            $parentContact= $parent['contact_no'];
            if($smsActive->status == 1){
            Yii::$app->common->SendSms($parentContact,$msg,$value['stu_id']);
            Yii::$app->session->setFlash('success', "Message send successfully");
            
        }else{
            Yii::$app->session->setFlash('success', "Some thing went wrong");
        }
         }
         return $this->render('success.php');
    }
       return $this->render('currentfeeremain.php',['studentFeeDetails'=>$studentFeeDetails]);
    }

    /**
     * Displays a single Messages model.
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
     * Creates a new Messages model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Messages();

        if ($model->load(Yii::$app->request->post())) {
            $array=Yii::$app->request->post('Messages');
            $model->sender_id=Yii::$app->user->identity->id;
            $model->username=$array['user_id'];
            $model->sender_recvr=0;
            if($model->save()){
            Yii::$app->session->setFlash('success', "Message send successfully");
            return $this->redirect(['index']);
            }else{echo print_r($model->getErrors());die;};
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


   public function actionReply()
    {   
        $id=yii::$app->request->post('id');
         $model = new Messages();
         $model->message=yii::$app->request->post('val');
         $model->sender_id=yii::$app->request->post('senderIdpass');
         $model->subject=yii::$app->request->post('getSubject');
         $model->user_id=Yii::$app->user->identity->id;
         $model->username=yii::$app->request->post('senderIdpass');
         $model->sender_recvr=1;
         $model->read_status=1;
         $model->fk_branch_id=Yii::$app->common->getBranch();
         $model->send_date=date('Y-m-d');
        if($displayMessage=$model->save()){
        //return json_encode(['displayMessage'=>$displayMessage]);
        }else{
            print_r($model->getErrors());die;
        }
    }



    public function actionComposeMessage()
    {
        $model = new Messages();
        $displayMessage= $this->renderAjax('_form', [
                'model' => $model,
            ]);
        return json_encode(['displayMessage'=>$displayMessage,'model'=>$model]);
    }

    public function actionGetMessage()
    {
        $model = new Messages();
        $id=yii::$app->request->post('id');
        $sender=yii::$app->request->post('sender');
        $getMessage=Messages::find()->where(['sender_id'=>$sender])->all();
        $displayMessage= $this->renderAjax('_message', [
                'model' => $model,
                'id' => $id,
                'getMessage' => $getMessage,
            ]);
        return json_encode(['displayMessage'=>$displayMessage,'model'=>$model]);
    }

    /**
     * Updates an existing Messages model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Messages model.
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
     * Finds the Messages model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Messages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Messages::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
