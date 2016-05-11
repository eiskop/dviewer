<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\DrawingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = 'Drawings';
//$this->params['breadcrumbs'][] = $this->title;
$path_txt = '/dviewer/common/data/txt';
$path_xml = '/dviewer/common/data/xml';
$path_pdf = '/dviewer/common/data/pdf';
$path_jpg = '/dviewer/common/data/jpg';
$path_svg = '/dviewer/common/data/svg';


$list = array('drawing_number', 'item_name', 'description', 'all_fields');

?>
<div class="drawing-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Reset Filters', ['index'], ['class' => 'btn btn-success']) ?>
    </p>


<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'date',
            //'type',
            //'vaultname',
            // 'doc_aliasset',
            // 'doc_pdmweid',
            'drawing_number',
            // 'conf_name',
            // 'conf_quantity',
            'description1',
            'revision',
            'item_name',
            
            // 'product_responsible',
            // 'state',
            // 'xml_file_name',
            // 'pdf_contents:ntext',
            // 'pdf_contents_lc:ntext',
            // 'pdf_contents_uc:ntext',
            // 'xml_file_created',
            'creation_date',
            'creator',
            'approval_date',
            'approver',
            'created',
            //'changed',
            [  
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width:50px;'],
                'header'=>'',
                'template' => '{view}',
                'buttons' => 
                [

                    //view button
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                    'title' => Yii::t('app', 'View'),                              
                        ]);
                    },                                        
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        return Url::to(['drawing/view', 'id'=>$model->id]);
                    }
                },

            ],            
        ],

    ]); ?>
<?php Pjax::end(); ?></div>
<?php
$this->registerJs("
    $('td').dblclick(function (e) {
        var id = $(this).closest('tr').attr('data-key');
        if(e.target == this)
            location.href = '" . Url::to(['drawing/view']) . "&id=' + id;
    });
    $('tr').hover(function() {
        $(this).css({'cursor':'hand', 'cursor':'pointer'});
    });

");