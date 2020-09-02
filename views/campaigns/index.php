<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\UpdateLog;
use app\models\Traffic;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CampaignsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Campaigns';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="campaigns-index">

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

            [
                'attribute' => 'id',
                'attribute' => 'name',
                'value' => function ($data) {
                    return "<a href='/campaigns/view?id=".$data->id."'>".$data->name."</a>";
                },
                'format' => 'html'
            ],
            'clicks',
            'leads',
            'conversion_rate',
            [
                'attribute' => 'trafic_id',
                'value' => function ($data) {
                    $temp = Traffic::find()->where(['id' => $data->trafic_id])->one();
                    return "<a href='/traffic/view?id=".$data->trafic_id."'>"
                        //.$temp['id'].") "
                        .$temp['ts_name']." (".$temp["domain_name"].")"."</a>";
                },
                'format' => 'html'
            ],

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
