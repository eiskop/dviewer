<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Drawing */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="drawing-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'entity_id')->textInput() ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vaultname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_aliasset')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_pdmweid')->textInput() ?>

    <?= $form->field($model, 'drawing_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'conf_name')->textInput() ?>

    <?= $form->field($model, 'conf_quantity')->textInput() ?>

    <?= $form->field($model, 'description1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description3')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'revision')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'creator')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'approver')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'product_responsible')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'state')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'xml_file_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pdf_contents')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'pdf_contents_lc')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'pdf_contents_uc')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'xml_file_created')->textInput() ?>

    <?= $form->field($model, 'creation_date')->textInput() ?>

    <?= $form->field($model, 'approval_date')->textInput() ?>

    <?= $form->field($model, 'created')->textInput() ?>

    <?= $form->field($model, 'changed')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
