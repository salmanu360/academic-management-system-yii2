<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
$this->title = $model->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><?= Html::encode($this->title) ?></h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
        <div class="row">
        <div class="col-md-12"> 
    <?php if(!empty($model->description)){ ?>
    <textarea name="" id="" cols="100" rows="3"><?= $model->description; ?></textarea>
    <?php } ?>
         
        <!-- <?/*= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])*/ ?> -->
       <?php $src=Yii::$app->request->baseUrl.'/'; ?>
     <iframe src = "<?= $src ?>/uploads/assigments/<?= $model->image ?>" width='1000' height='750' allowfullscreen webkitallowfullscreen></ifrasme> 
</div>
</div>
</div>
</div>
</div>