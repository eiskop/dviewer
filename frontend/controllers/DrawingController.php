<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Drawing;
use frontend\models\DrawingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DrawingController implements the CRUD actions for Drawing model.
 */
class DrawingController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Drawing models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DrawingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Drawing model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Drawing model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Drawing();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Drawing model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Drawing model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Drawing model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Drawing the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Drawing::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     * Import drawing data from PDM server XML files and create SVG files and TXT files and include TXT files in the DB.
     */
    public function actionImportPdm()
    {


            function startTag($parser, $name, $attrs) 
            {
                global $stack;
                $tag=array("name"=>$name,"attrs"=>$attrs);   
                array_push($stack,$tag);

            }

            function cdata($parser, $cdata)
            {
                global $stack, $i;
                
                if(trim($cdata))
                {     
                    $stack[count($stack)-1]['cdata']= $cdata;
                }
            }

            function endTag($parser, $name) 
            {
               global $stack;   
               $stack[count($stack)-2]['children'][] = $stack[count($stack)-1];
               array_pop($stack);
            }



 
        function getXMLdata ($dir_) {
            $count = 0;
            $start_time = time();
            $d = dir($dir_);
            while (false !== ($entry = $d->read())) {
                $count++;
                $file = $entry;
                if ($entry != '.' AND $entry != '..') {
                    $path_info = pathinfo($dir_.'/'.$file.'');
                    echo '<pre>', var_dump($path_info), '</pre>';

                    $file_stats = stat($dir_.'/'.$file.'');
                    if ( $entry != '.' AND $entry != '..' AND $entry != '.txt') {
                        $file2 = str_replace(' ', '_', $file); 
                        rename($dir_.'/'.$file.'', $dir_.'/'.$file2);
                        $file = $file2;
                    }
                    $bname = basename($file, '.pdf');;
                    $svg_file = './../../common/data/svg/'.$bname.'.svg';
                    $pdf_file = './../../common/data/pdf/'.$bname.'.pdf';
                    $txt_file = './../../common/data/txt/'.$bname.'.txt';

                    $path_info = pathinfo($pdf_file);
                  //  echo '<p>'.var_dump($path_info).'</p>';
                    if (!file_exists($svg_file) AND file_exists($pdf_file)) {
                        //echo var_dump(file($pdf_file));
                      //  echo '<p>SVG-d Pole '.$svg_file.'</p>';
                        $outp1 = shell_exec('inkscape -z -l "'.$svg_file.'" "'.$pdf_file.'"');
                        //echo var_dump(file($svg_file));
    //                    echo '<p>inkscape --without-gui -export-plain-svg="'.$svg_file.'" "'.$pdf_file.'"</p>';
    //                   echo $outp1;

                    }
                    if (!file_exists($txt_file) AND file_exists($pdf_file)) {
                        $outp2 = shell_exec('pdftotext -raw "'.$pdf_file.'" "'.$txt_file.'"');
                        //echo $outp2;
                    //    echo '<p>TXT-d Pole - pdftotext -raw "'.$pdf_file.'" "'.$txt_file.'"</p>';

                    }
                }
            }
            $d->close();

            if (time()-$start_time > 60*10) {
              break;
            }


            //END: PDF to SVG convert and PDF to TXT convert

            //XML info import
            




            $xml_folder = '../../common/data/xml/';
            $d = dir($xml_folder);


            $count = 0;
            while (false !== ($file = $d->read())) {
                $count++;
                $stack = array();

                $file_path = $xml_folder.'/'.$file;
                $path_info = pathinfo($file_path);
             
            //  echo $file_path.'<br>';
            //  echo 'count: '.$count.'<br>';
            //  echo 'Time: '.time()-$start.'<br>'; 
                if ($path_info['basename'] != '.' AND $path_info['basename'] != '..' AND isset($path_info['extension'])) {
                    if ($path_info['extension'] == 'XML') {
                        echo $file_path;
                        //exit;
                        $xml_string = file_get_contents(($file_path));
                        $xml = simplexml_load_string($xml_string);
                        $json = json_encode($xml);
                        $array = json_decode($json,TRUE);
                        echo '<pre>';

                        //echo var_dump($array['transactions']['transaction']['document']['configuration']['attribute']);
                        foreach ($array['transactions']['transaction']['document']['configuration']['attribute'] as $k=>$v) {
                            echo var_dump(strtolower($v['@attributes']['name']).' = '.$v['@attributes']['value']);    

                        }

                        echo var_dump($array);
                    }
    /*


                    $sql = 'INSERT INTO xml_info SET ';


                    $transaction_attrs = $stack[0]['children'][0]['children'][0]['attrs'];

                    foreach ($transaction_attrs as $k=>$v) {
                        $sql .= strtolower($k).'='.fixDb($v).', ';
                    }

                    $document_attrs = $stack[0]['children'][0]['children'][0]['children'][0]['attrs'];

                    foreach ($document_attrs as $k=>$v) {
                        $sql .= 'doc_'.strtolower($k).'='.fixDb($v).', ';
                    }

                    $configuration_attrs = $stack[0]['children'][0]['children'][0]['children'][0]['children'][0]['attrs'];

                    foreach ($configuration_attrs as $k=>$v) {
                        $sql .= 'conf_'.strtolower($k).'='.fixDb($v).', ';
                    }

                    $attributes = $stack[0]['children'][0]['children'][0]['children'][0]['children'][0]['children'];

                    foreach ($attributes as $k=>$v) {
                        if (strpos(strtolower($v['attrs']['NAME']), 'date') != FALSE) {
                            $sql .= strtolower($v['attrs']['NAME']).'='.fixDb(date('Y-m-d', strtotime(str_replace('/', '-', $v['attrs']['VALUE'])))).', ';
                        }
                        else {
                            $sql .= strtolower($v['attrs']['NAME']).'='.fixDb($v['attrs']['VALUE']).', ';
                        }
                    }

                    $sql .= ' xml_file_name = '.fixDb($file).', xml_file_created = '.fixDb(date('Y-m-d H:i:s', $file_stats['mtime'])).', created = NOW()';

                    $sql_check = 'SELECT id FROM xml_info WHERE doc_pdmweid = '.fixDb($document_attrs['PDMWEID']).' AND revision = '.fixDb($attributes[3]['attrs']['VALUE']).' AND xml_file_created = '.fixDb(date('Y-m-d H:i:s', $file_stats['mtime']));
                   // $res_check = $db2->getData($sql_check);


                    if ($res_check != TRUE) {
                        if ($db2->query($sql) != FALSE) {
            //              echo 'GREAT SUCCESS!!!'.'<BR>';
                        }
                        else {
            //              echo 'Import failed'.'</p>';
                        }
                    }
                    else {
            //          echo 'Juba olemas</p>';
                    }
                }
    */
                }
            }

            $d->close();




            //END: XML info import
        }
                //PDF to SVG convert and PDF to TXT convert
        $pdf_folder = '../../common/data/pdf/';
        $dir = $pdf_folder;

        // Open a known directory, and proceed to read its contents
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    echo filetype($dir . $file)." filename: $file<br>";
                    $is_dir = filetype($dir . $file);
                    if ($is_dir == 'dir' AND $file != '.' AND $file != '..') {
                        getXMLdata($pdf_folder.$file);    
                    }
                    
                }
                closedir($dh);
            }
        }


    }

}