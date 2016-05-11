<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Drawing */

$this->title = 'Create Drawing';
$this->params['breadcrumbs'][] = ['label' => 'Drawings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="drawing-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
