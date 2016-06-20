<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Drawing;
use frontend\models\DrawingSearch;
use frontend\models\Entity;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

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

                if (time()-$start_time > 60*10) {
                  break;
                }                
                $count++;
                //echo 'count: '.$count.'<br>';
                $file = $entry;
                //echo var_dump($file);
                

                if ($entry != '.' AND $entry != '..') {
                    $path_info = pathinfo($dir_.'/'.$file.'');
                    $dir_info = pathinfo($dir_);
                    
                    //get entity_id
                    $entity_data = ArrayHelper::map(Entity::find()->where(['folder_name'=>$dir_info['basename']])->andWhere(['active_import'=>1])->all(), 'folder_name', 'id');
                    //echo var_dump($entity_data);
                    $entity_id = $entity_data[$dir_info['basename']];
                    //END:get entity_id

                    $file_stats = stat($dir_.'/'.$file.'');
                    //remove spaces from file name
                /*  if ( $entry != '.' AND $entry != '..' AND $entry != '.txt') {
                        $file2 = str_replace(' ', '_', $file); 
                        rename($dir_.'/'.$file.'', $dir_.'/'.$file2);
                        $file = $file2;
                    }
                */

                    //END: remove spaces from file name


                    $bname = basename($file, '.XML');
                   
                    $svg_file = str_replace('xml', 'svg', $dir_).'/'.$bname.'.svg';
                

                    $pdf_file = $dir_.'/'.$bname.'.pdf';
                    $pdf_file = str_replace('xml', 'pdf', $dir_).'/'.$bname.'.pdf';

                    $txt_file = $dir_.'/'.$bname.'.txt';
                    $txt_file = str_replace('xml', 'txt', $dir_).'/'.$bname.'.txt';
                    $xml_file = $dir_.'/'.$bname.'.XML';

                    $path_info = pathinfo($pdf_file);
                   // echo '<pre>', var_dump($path_info), '</pre>';
                    if (file_exists($svg_file)) {
                        $svg_file_stat = stat($svg_file);                      
                    }
                    else {
                        $svg_file_stat['mtime'] = 0;
                    }
                    
                    if (file_exists($pdf_file)) {
                        $pdf_file_stat = stat($pdf_file);    
                    }
                    else {
                        $pdf_file_stat['mtime'] = 0;
                    }
                    if (file_exists($txt_file)) {
                        $txt_file_stat = stat($txt_file);    
                    }
                    else {
                        $txt_file_stat['mtime'] = 0;
                    }                    

//get XML data
                       // echo $file_path;
                        //exit;
                        $xml_string = file_get_contents(($xml_file));
                        $xml = simplexml_load_string($xml_string);
                        $json = json_encode($xml);
                        $array = json_decode($json,TRUE);
                        //echo '<pre>';
                        $sql_insert = array();
                        //echo '<pre>', var_dump($array['transactions']['transaction']['document']['configuration']['@attributes']), '</pre>';
                        //echo '<pre>', var_dump($array['transactions']['transaction']['document']['@attributes']), '</pre>';
//                        echo '<pre>', var_dump($array), '</pre>';
                        //echo var_dump($array['transactions']['transaction']['document']['configuration']['attribute']);
                        if (array_key_exists('attribute', $array['transactions']['transaction']['document']['configuration'])) {
                            foreach ($array['transactions']['transaction']['document']['configuration']['attribute'] as $k=>$v) {
                                $sql_insert[strtolower($v['@attributes']['name'])] = htmlentities($v['@attributes']['value']);
                               //echo '<pre>', var_dump(strtolower($v['@attributes']['name']).' = '.$v['@attributes']['value']), '</pre>';    

                            }

                            //echo var_dump($array);
                        }
                        
                        $transaction_date = htmlspecialchars($array['transactions']['transaction']['@attributes']['date']);
                        $transaction_type = htmlspecialchars($array['transactions']['transaction']['@attributes']['type']);
                        $transaction_vaultname = htmlspecialchars($array['transactions']['transaction']['@attributes']['vaultname']);
                        $document_pdmweid = $array['transactions']['transaction']['document']['@attributes']['pdmweid'];
                        if (array_key_exists('@attributes', $array['transactions']['transaction']['document']['configuration'])) {
                            $configuration_name = htmlspecialchars($array['transactions']['transaction']['document']['configuration']['@attributes']['name']);    
                            $configuration_quantity = htmlspecialchars($array['transactions']['transaction']['document']['configuration']['@attributes']['quantity']);
                        }
                        else {
                            $configuration_name = '';
                            $configuration_quantity = 0;
                        }
                        

                        $sql_insert['date'] = $transaction_date; 
                        $sql_insert['type'] = $transaction_type;
                        $sql_insert['vaultname'] = $transaction_vaultname;
                        $sql_insert['doc_pdmweid'] = $document_pdmweid;
                        $sql_insert['conf_name'] = $configuration_name;
                        $sql_insert['conf_quantity'] = $configuration_quantity;
                        $sql_insert['entity_id'] = $entity_id;
                        $sql_insert['xml_file_name'] = $bname;
                        $sql_insert['created'] = date('Y-m-d H:i:s', time());
                        if (array_key_exists('creation_date', $sql_insert)) {
                            $sql_insert['creation_date'] = date('Y-m-d', strtotime($sql_insert['creation_date']));
                        }
                        else {
                            $sql_insert['creation_date'] = '0000-00-00';
                        }

                        if (array_key_exists('approval_date', $sql_insert)) {
                            $sql_insert['approval_date'] = date('Y-m-d', strtotime($sql_insert['approval_date']));
                        }
                        else {
                            $sql_insert['approval_date'] = '0000-00-00';
                        }
                        if (!array_key_exists('revision', $sql_insert)) {
                            $sql_insert['revision'] = '';
                        }
                        
                        

                        $drawing_data = Drawing::find()->where(['doc_pdmweid'=>$document_pdmweid])->andWhere(['active'=>1])->orderBy('created DESC')->all(); 

                        if (array_key_exists(0, $drawing_data)) { // check if drawing with the revision exists
                            if ($drawing_data[0]['date'] != $sql_insert['date'] OR $drawing_data[0]['creation_date'] != $sql_insert['creation_date'] OR $drawing_data[0]['approval_date'] !=  $sql_insert['approval_date'] OR $drawing_data[0]['revision'] != $sql_insert['revision']) {


        //echo '<pre>', var_dump($array['transactions']['transaction']['document']['configuration']), '</pre>';

                                   

                                    
                                     echo '<strong>'.$sql_insert['drawing_number'].' - '.$sql_insert['doc_pdmweid'].' EI OLE SAMA</strong><br>';
                                      var_dump($sql_insert);
                                      echo '<p>';
                                       var_dump($drawing_data);
                                       echo '<p>';
                                       echo date('Y-m-d H:i:s', $drawing_data[0]['date']);
                                       echo '<p>';
                                        echo date('Y-m-d H:i:s', $sql_insert['date']);
                                        echo '<p>';
                                        if ($drawing_data[0]['date'] < $sql_insert['date']) {
                                            // imported drawing is newer
                                            echo 'imporditav joonis on uuem<p>';
                                            Drawing::find()->where(['doc_pdmweid'=>$document_pdmweid])->andWhere(['active'=>1])->orderBy('created DESC')->all(); 
                                            Yii::$app->db->createCommand()->update('drawing', ['active' => 0], 'id ='.$drawing_data[0]['id'])->execute(); 

                                            Yii::$app->db->createCommand()->insert('drawing', $sql_insert)->execute(); 
                                            if (file_exists($pdf_file) AND (!file_exists($svg_file) OR $svg_file_stat['mtime'] < $pdf_file_stat['mtime'])) {
                                                //echo var_dump(file($pdf_file));
                                                //echo '<p>SVG-d Pole '.$svg_file.'</p>';
                                                $outp1 = shell_exec('inkscape -z -l "'.$svg_file.'" "'.$pdf_file.'"');
                                                //echo var_dump(file($svg_file));
                                               //echo '<p>inkscape --without-gui -export-plain-svg="'.$svg_file.'" "'.$pdf_file.'"</p>';
                                               //echo var_dump($outp1);

                                            }
                                            
                                            if ((!file_exists($txt_file)  OR $txt_file_stat['mtime'] < $pdf_file_stat['mtime']) AND file_exists($pdf_file)) {
                                                $outp2 = shell_exec('pdftotext -raw "'.$pdf_file.'" "'.$txt_file.'"');
                                                Yii::$app->db->createCommand()->update('drawing', ['pdf_contents' => file_get_contents($txt_file)], 'id ='.$sql_[0]['id'])->execute(); 
                                                //echo $outp2;
                                            //    echo '<p>TXT-d Pole - pdftotext -raw "'.$pdf_file.'" "'.$txt_file.'"</p>';

                                            }                                       

                                        }
                                        else {
                                            //imported drawing is older
                                            echo 'imporditav joonis on vanem<p>';


                                        }
                                                                   
                                
                            }
                            else {
                                //echo $drawing_data[0]['drawing_number'].' - '.$drawing_data[0]['doc_pdmweid'].' SAMA<br>';
                            
                            }                            
                        }
                        else {
                          Yii::$app->db->createCommand()->insert('drawing', $sql_insert)->execute(); 
                            if (file_exists($pdf_file) AND (!file_exists($svg_file) OR $svg_file_stat['mtime'] < $pdf_file_stat['mtime'])) {
                                //echo var_dump(file($pdf_file));
                                //echo '<p>SVG-d Pole '.$svg_file.'</p>';
                                $outp1 = shell_exec('inkscape -z -l "'.$svg_file.'" "'.$pdf_file.'"');
                                //echo var_dump(file($svg_file));
                               //echo '<p>inkscape --without-gui -export-plain-svg="'.$svg_file.'" "'.$pdf_file.'"</p>';
                               //echo var_dump($outp1);

                            }
                            
                            if ((!file_exists($txt_file)  OR $txt_file_stat['mtime'] < $pdf_file_stat['mtime']) AND file_exists($pdf_file)) {
                                $outp2 = shell_exec('pdftotext -raw "'.$pdf_file.'" "'.$txt_file.'"');
                                $txt_file_info = pathinfo($txt_file);
                                //echo '<pre>', var_dump($txt_file_info), '</pre>';
                                $drawing = Drawing::find()->where(['xml_file_name'=>$bname, 'active'=>'1'])->one();
                                //echo '<pre>', var_dump($drawing), '</pre>';
                                $txt_file_lc = strtolower(file_get_contents($txt_file));
                                $txt_file_uc = strtoupper(file_get_contents($txt_file));
                                if (!is_null($drawing)) {
                                    Yii::$app->db->createCommand()->update('drawing', ['pdf_contents' => file_get_contents($txt_file),'pdf_contents_uc' => $txt_file_uc,'pdf_contents_lc' => $txt_file_lc], ['xml_file_name' => $bname, 'active'=>1])->execute(); 
                                     echo '<p>TXT file contents added</p>';
                                }
                                else {
                                     echo '<p>no TXT file</p>';
                                }
                                //echo $outp2;
                            //    echo '<p>TXT-d Pole - pdftotext -raw "'.$pdf_file.'" "'.$txt_file.'"</p>';

                            }                               
                        }
                        //echo '<pre>', var_dump($drawing_data), '</pre>';


                        

//END: get XML data


                  

                }
            }
            $d->close();


           //END: PDF to SVG convert and PDF to TXT convert

            //XML info import
            




            $xml_folder = '../../common/data/xml/';
            $d = dir($xml_folder);


            $count = 0;
                $start_time = time();            
            while (false !== ($file = $d->read())) {

                if (time()-$start_time > 60*10) {
                  break;
                }                
                $count++;
                $stack = array();

                $file_path = $xml_folder.'/'.$file;
                $path_info = pathinfo($file_path);
             
            //  echo $file_path.'<br>';
           //   echo 'count: '.$count.'<br>';
          //    echo 'Time: '.time()-$start_time.'<br>'; 
                if ($path_info['basename'] != '.' AND $path_info['basename'] != '..' AND isset($path_info['extension'])) {

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
        $xml_folder = '../../common/data/xml/';
        $dir = $xml_folder;

        // Open a known directory, and proceed to read its contents
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                $start_time = time();   
                while (($file = readdir($dh)) !== false) {
                    
                    if (time()-$start_time > 60*10) {
                      break;
                    }
                    echo filetype($dir . $file)." filename: $file<br>";
                    $is_dir = filetype($dir . $file);
                    if ($is_dir == 'dir' AND $file != '.' AND $file != '..') {
                        
                        $entity_data = ArrayHelper::map(Entity::find()->where(['folder_name'=>$file])->andWhere(['active_import'=>1])->all(), 'folder_name', 'id');
                       // echo var_dump($entity_data);
                        if (count($entity_data) > 0) {
                            getXMLdata($xml_folder.$file);
                        }
                        else {
                            echo 'No Entity Data Fround';
                        }
                    }
                    
                }
                closedir($dh);
            }
        }


    }

}