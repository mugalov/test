<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

?>

<div class="site-index">
    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'enableAjaxValidation' => false
    ]); ?>

    <div class="error-message"></div>

    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'file')->fileInput() ?>
<!-- 
    <input type="file" id="file-file" name="File[name]"> -->

    <?= $form->field($model, 'description')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<script>

    
</script>