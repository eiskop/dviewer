<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use frontend\models\Entity;


/* @var $this yii\web\View */
/* @var $model frontend\models\Drawing */

$this->title = $model->drawing_number.'-'.$model->revision.', '.$model->description1;
$this->params['breadcrumbs'][] = ['label' => 'Drawings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;



$entity_data = Entity::findOne(['id' => $model->entity_id]);
;

$path_txt = '/dviewer/common/data/txt/'.$entity_data->folder_name.'';
$path_xml = '/dviewer/common/data/xml/'.$entity_data->folder_name.'';
$path_pdf = '/dviewer/common/data/pdf/'.$entity_data->folder_name.'';
$path_jpg = '/dviewer/common/data/jpg/'.$entity_data->folder_name.'';
$path_svg = '/dviewer/common/data/svg/'.$entity_data->folder_name.'';


$list = array('drawing_number', 'item_name', 'description', 'all_fields');
function str2_replace($search, $replace, $subject) {
    return str_replace('', '', $subject);
}

$pdf_file = $path_pdf.'/'.str2_replace(' ', '_', $model->drawing_number.'-'.$model->revision).'.pdf';
$svg_file = $path_svg.'/'.str2_replace(' ', '_', $model->drawing_number).'-'.$model->revision.'.svg';


//$file_stats = stat($pdf_file);
$svg_file = '../../common/data/svg/'.$entity_data->folder_name.'/'.str2_replace(' ', '_', $model->drawing_number).'-'.$model->revision.'.svg';
$pdf_file = '../../common/data/pdf/'.$entity_data->folder_name.'/'.str2_replace(' ', '_', $model->drawing_number).'-'.$model->revision.'.pdf';
$txt_file = '../../common/data/txt/'.$entity_data->folder_name.'/'.str2_replace(' ', '_', $model->drawing_number).'-'.$model->revision.'.txt';
$path_info = pathinfo($pdf_file);


if (!file_exists($svg_file)) {
    //echo $svg_file;
    $outp1 = shell_exec('inkscape -z -l "'.$svg_file.'" "'.$pdf_file.'"');
 //   echo '<p>inkscape -z -l "'.$svg_file.'" "'.$pdf_file.'"</p>';
   // echo $outp1;
}
if (!file_exists($txt_file) AND file_exists($pdf_file)) {
    $outp2 = shell_exec('pdftotext -raw "'.$pdf_file.'" "'.$txt_file.'"');
    echo $outp2;
    echo '<p>pdftotext -raw "'.$pdf_file.'" "'.$txt_file.'"</p>';
}

$fn_pdf = str2_replace(' ', '_', $model->drawing_number).'-'.$model->revision.'.pdf';
$fn_svg = str2_replace(' ', '_', $model->drawing_number).'-'.$model->revision.'.svg';
    //if (!file_exists($path_pdf.'/'.$fn_pdf) AND !file_exists($path_svg.'/'.$fn_svg)) {
    //  echo $pdf_file;
  //      $outp3 = shell_exec('inkscape -z -l '.$path_pdf.'/'.$fn_pdf.' '.$path_svg.'/'.$fn_svg);
    //}
//        echo 'inkscape -z -l '.$path_pdf.'/'.$fn_pdf.' '.$path_svg.'/'.$fn_svg;
/*if (stat('http://10.41.1.100/'.$path_pdf.'/'.$fn_pdf)) { 
    echo 'file';
}
else {
    echo 'no file '.$path_pdf.'/'.$fn_pdf;
}
exit;
*/

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
            'description1:html',
            'description2:html',
            'description3:html',
            'noa:html',
            'revision',
            'item_name:html',
            'creator',
            'approver',
            'product_responsible:html',
            'state',
            'xml_file_name',
            'pdf_contents:html',
            'xml_file_created',
            'creation_date',
            'approval_date',
            'created',
            'changed',
        ],
    ]) ?>

</div>

