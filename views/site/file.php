<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<h1>File Upload</h1>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

<?= $form->field($model, 'file')->fileInput() ?>
<?= $form->field($model, 'name')->textInput() ?>
<?= $form->field($model, 'description')->textarea() ?>

<div class="form-group">
    <?= Html::submitButton('Upload', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>