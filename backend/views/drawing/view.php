<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;


/* @var $this yii\web\View */
/* @var $model backend\models\Drawing */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Drawings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;





$path_txt = '/dviewer/common/data/txt';
$path_xml = '/dviewer/common/data/xml';
$path_pdf = '/dviewer/common/data/pdf';
$path_jpg = '/dviewer/common/data/jpg';
$path_svg = '/dviewer/common/data/svg';


$list = array('drawing_number', 'item_name', 'description', 'all_fields');

$pdf_file = $path_pdf.'/'.str_replace(' ', '_', $model->drawing_number.'-'.$model->revision).'.pdf';
$svg_file = $path_svg.'/'.str_replace(' ', '_', $model->drawing_number).'-'.$model->revision.'.svg';

$path_info = pathinfo($pdf_file);
//$file_stats = stat($pdf_file);
if (!file_exists($svg_file)) {
//  echo $pdf_file;
  //  $outp3 = shell_exec('inkscape -z -l '.$path_svg.'/'.$path_info['filename'].'.svg '.$path_pdf.'/'.$path_info['filename'].'.pdf');
}


$fn_pdf = str_replace(' ', '_', $model->drawing_number).'-'.$model->revision.'.pdf';
$fn_svg = str_replace(' ', '_', $model->drawing_number).'-'.$model->revision.'.svg';





?>

<div>
    <SCRIPT LANGUAGE="JavaScript">
<!--

    $( document ).ready(
        function() {
        //  if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            //  alert('i\m android');
                $('#svg_image').show();
                $('#pdf_file').show();
                $('#pdf_a_link').show();
        //  }
        //  else {
        //      $('#svg_image').hide();
        //      $('#pdf_file').show();
        //      $('#svg_a_link').show();
        //  }

            var height = getBrowserSize('height')-130;
            document.getElementById('main_table').style.height=height + "px";
//          console.log(height);
        //  width = height*(210/297);
        //  document.getElementById('main_table').style.width=width + "px";
//          console.log( "ready!" );
        


            $('.result_row').on('click', 
                function () {
                    window.open($(this).data('link'), '_self', '');
                    
                }
            );
            $('#doc_index_button').click(
                function () {
                    window.open($(this).data('link'), '_self', '');
                    
                }
            );


        }


    );

    function hide_table() {
        $('td[id^="hide_column"]').toggle();
        $('th[id^="hide_column"]').toggle();
        if ($('#hide_button').attr('value') == 'Show')
        {
            $('#hide_button').html('Hide');
        }
        if ($('#hide_button').attr('value') == 'Hide')
        {
            $('#hide_button').html('Show');
        }
    }
//-->
</SCRIPT>



<table id="main_table">
    <tr>
        <td style="vertical-align: top; text-align: left; padding-left: 2% ">
            <span style="font-weight: bold; text-decoration: underline;"><a href="<?= Url::to($path_pdf.'/'.$fn_pdf, true); ?>" id ="pdf_a_link">PDF</a></span><span style="margin-left: 200px;font-weight: bold; text-decoration: underline;"><a href="<?= Url::to($path_svg.'/'.$fn_svg, true); ?>" id="svg_a_link">SVG</a></span><br>
            <img src="<?= Url::to($path_svg.'/'.$fn_svg, true); ?>">
<!--            <object style="margin-top: 5px;" id ="pdf_file" data = "[pdf_file]#page=1&toolbar=0&navpanes=0&scrollbar=1&page=1&view=FitH" type="application/pdf" width="[pdf_width]px" height="[pdf_height]px" >
                <param  value="[pdf_file]" name="data"/>
                <param value="transparent" name="wmode"/> 
                <p>[file_exists_test]</p>
            </object> -->
            
            <img id ="svg_image" src="<?= $svg_file ?>" width="[pdf_width]px" height="[pdf_height]px" style="margin-top: 5px; display: none; background-color: white;">
        <td>
    </tr>
</table>
</div>





<div class="drawing-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'entity_id',
            'date',
            'type',
            'vaultname',
            'doc_aliasset',
            'doc_pdmweid',
            'drawing_number',
            'conf_name',
            'conf_quantity',
            'description1',
            'description2',
            'description3',
            'revision',
            'item_name',
            'creator',
            'approver',
            'product_responsible',
            'state',
            'xml_file_name',
            'pdf_contents',
            'xml_file_created',
            'creation_date',
            'approval_date',
            'created',
            'changed',
        ],
    ]) ?>

</div>

