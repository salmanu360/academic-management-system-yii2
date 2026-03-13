<?php
use yii\helpers\Html;
use app\components\widgets\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use app\models\StudentInfo;
use app\models\StudentParentsInfo;  
$student=StudentInfo::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
$getParents=StudentParentsInfo::find()->where(['stu_id'=>$student->stu_id])->one();
$getParentsChilds=StudentParentsInfo::find()->where(['cnic'=>$getParents->cnic])->all();
?>
<ul id="w1" class="sidebar-menu"><li class="header">MAIN NAVIGATION</li>
<li><a href="<?= url::to(['/dashboard']) ?>"><i class="fa fa-dashboard" aria-hidden="true"></i> <span><?php echo Yii::t('app','Dashboard') ?></span></a></li>
<li class="active treeview"><a href="#"><i class="fa fa-group"></i><span>Child List</span>
 <i class="fa fa-angle-left pull-right"></i></a>
 <ul id="w2" class="treeview-menu">
<?php 
 foreach ($getParentsChilds as $child) {
	?>
 <li>
 <a href="<?php echo Url::to(['student/profile','id'=>$child->stu_id]) ?>" tabindex="-1"><span>
 <i class="fa fa-circle-o"></i></span> <?php echo $child->stu->user->first_name ?></a>
 </li>
 <?php } ?>
</ul>
</li>
</ul>

<?php 
	echo Nav::widget([
    'items' => [
         ['label' => '<span><i class="fa fa-calendar"></i></span> '.Yii::t('app','Attendance Calendar'), 'url' => ['site/attendance-parent-cal'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['site/attendance-parent-cal'])], 
         ['label' => '<span><i class="fa fa-files-o"></i></span> '.Yii::t('app','Home Tasks'), 'url' => ['/general/parent-task'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/general/parent-task'])],
         ['label' => '<span><i class="fa fa-calendar"></i></span> '.Yii::t('app','Noticeboard'), 'url' => ['site/parent-noticeboard'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['site/parent-noticeboard'])], 
         ['label' => '<span><i class="fa fa-book"></i></span> '.Yii::t('app','Book Return/Renewal'), 'url' => ['book-issue/return-role'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['book-issue/return-role'])],

         ['label' => '<i class="fa fa-envelope-o"></i>'.Yii::t('app','<span>Messages</span>'), 'url' => ['/messages'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/messages'])],
         /*start of Exams*/
        /* [
            'label' => '<i class="fa fa-file-text"></i>'.Yii::t('app','<span>Exams</span>'),
            'items' => [
               
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Exams Schedule'), 'url' => ['/exams/dmc-parent'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/exams/dmc-parent'])],
               
                ['label' => '<span><i class="fa fa-circle-o"></i></span> '.Yii::t('app','Detail Marks Sheet'), 'url' => ['/exams/dmc-index'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/exams/dmc-index'])],
            ],
        ],*/
        ['label' => '<span><i class="fa fa-file-text"></i></span> '.Yii::t('app','Quiz'), 'url' => ['/exams/student-quiz-portal'],'active'=>Yii::$app->request->getUrl() == Url::toRoute(['/exams/student-quiz-portal'])],
        /*end of Exams*/
         
    ],


    ]);
 ?>