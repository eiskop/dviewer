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
        <?= Html::a('ImportPDM Data', ['/drawing/import-pdm'], ['class' => 'btn btn-success']) ?>
    </p>


<?php Pjax::begin(); ?>    

<?= GridView::widget([

        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'firstPageLabel' => '<<<',
            'lastPageLabel' => '>>>',
        ],
        'layout'=>"{pager} {summary} {items} {pager}",        
        'showHeader' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'date',
            //'type',
            //'vaultname',
            // 'doc_aliasset',
            // 'doc_pdmweid',
            [
                'attribute' => 'drawing_number',
                'format' => 'html',
                'value'=>function($data) {
                    $path_pdf = '/dviewer/common/data/pdf';
                    $fn_pdf = str_replace(' ', '_', $data->drawing_number).'-'.$data->revision.'.pdf';
                    if (file_exists('../../common/data/pdf/'.$fn_pdf)) {
                        return $data->drawing_number.'<span class="glyphicon glyphicon-file pull-right"></span>';
                    }
                    else {
                        return $data->drawing_number;
                    }
                },
            ],            
            // 'conf_name',
            // 'conf_quantity',
            'description1:html',
            'revision',
            'item_name:html',
            
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
            /*
            [
                'attribute' => 'SVG',
                'format' => 'html',
                'value'=>function($data) {
                    $path_svg = '/dviewer/common/data/svg';
                    $fn_svg = str_replace(' ', '_', $data->drawing_number).'-'.$data->revision.'.svg';
                    if (file_exists('../../common/data/svg/'.$fn_svg)) {
                        return '<span class="glyphicon glyphicon-file"></span>';
                    }
                    else {
                        return '-';
                    }
                },
                'contentOptions' => ['style' => 'text-align: center;'],
            ],
            [
                'attribute' => 'pdf',
                'format' => 'html',
                'value'=>function($data) {
                    $path_pdf = '/dviewer/common/data/pdf';
                    $fn_pdf = str_replace(' ', '_', $data->drawing_number).'-'.$data->revision.'.pdf';
                    if (file_exists('../../common/data/pdf/'.$fn_pdf)) {
                        return '<span class="glyphicon glyphicon-file"></span>';
                    }
                    else {
                        return '-';
                    }
                },
                'contentOptions' => ['style' => 'text-align: center;'],
            ],
            [
                'attribute' => 'txt',
                'format' => 'html',
                'value'=>function($data) {
                    $path_txt = '/dviewer/common/data/txt';
                    $fn_txt = str_replace(' ', '_', $data->drawing_number).'-'.$data->revision.'.txt';
                    if (file_exists('../../common/data/txt/'.$fn_txt)) {
                        return '<span class="glyphicon glyphicon-file"></span>';
                    }
                    else {
                        return '-';
                    }
                },
                'contentOptions' => ['style' => 'text-align: center;'],
            ],
            [
                'attribute' => 'xml',
                'format' => 'html',
                'value'=>function($data) {
                    $path_xml = '/dviewer/common/data/xml';
                    $fn_xml = str_replace(' ', '_', $data->drawing_number).'-'.$data->revision.'.XML';
                    if (file_exists('../../common/data/xml/'.$fn_xml)) {
                        return '<span class="glyphicon glyphicon-file"></span>';
                    }
                    else {
                        return '-';
                    }
                },
                'contentOptions' => ['style' => 'text-align: center;'],
            ],     */       
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
<?php Pjax::end(); ?>
</div>
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

