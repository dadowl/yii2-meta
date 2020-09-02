<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Traffic;

/* @var $this yii\web\View */
/* @var $model app\models\Campaigns */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Campaigns', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="campaigns-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'clicks',
            'leads',
            'conversion_rate',
            [
                'attribute' => 'trafic_id',
                'value' => function ($data) {
                    $temp = Traffic::find()->where(['id' => $data->trafic_id])->one();
                    return "<a href='/traffic/view?id=".$data->trafic_id."'>".$temp['ts_name']." (".$temp["domain_name"].")"."</a>";
                },
                'format' => 'html'
            ],
        ],
    ]) ?>

</div>
