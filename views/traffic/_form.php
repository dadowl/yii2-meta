<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Traffic */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="traffic-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ts_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'domain_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
