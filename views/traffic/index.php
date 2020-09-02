<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\UpdateLog;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TrafficSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Traffics';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="traffic-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <? 
        $maxElemID = UpdateLog::find()->select(['id'=>'MAX(`id`)'])->one();
        $maxDate = UpdateLog::find()->where(['id' => $maxElemID['id']])->one();
        echo "Дата последней выгрузки: ".$maxDate["logs"]."<br /><br />"; 
    ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'firstPageLabel' => 'Начало',
            'lastPageLabel' => 'Конец',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'id',
                'attribute' => 'ts_name',
                'value' => function ($data) {
                    return "<a href='/traffic/view?id=".$data->id."'>".$data->ts_name."</a>";
                },
                'format' => 'html'
            ],
            'domain_name',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
