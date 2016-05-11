<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "drawing".
 *
 * @property integer $id
 * @property integer $entity_id
 * @property integer $date
 * @property string $type
 * @property string $vaultname
 * @property string $doc_aliasset
 * @property integer $doc_pdmweid
 * @property string $drawing_number
 * @property integer $conf_name
 * @property integer $conf_quantity
 * @property string $description1
 * @property string $description2
 * @property string $description3
 * @property string $revision
 * @property string $item_name
 * @property string $creator
 * @property string $approver
 * @property string $product_responsible
 * @property string $state
 * @property string $xml_file_name
 * @property string $pdf_contents
 * @property string $xml_file_created
 * @property string $creation_date
 * @property string $approval_date
 * @property string $created
 * @property string $changed
 *
 * @property Entity $entity
 */
class Drawing extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'drawing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity_id', 'date', 'type', 'vaultname', 'doc_aliasset', 'doc_pdmweid', 'drawing_number', 'conf_name', 'conf_quantity', 'description1', 'description2', 'description3', 'revision', 'item_name', 'creator', 'approver', 'product_responsible', 'state', 'xml_file_name', 'pdf_contents', 'xml_file_created', 'creation_date', 'approval_date', 'created'], 'required'],
            [['entity_id', 'date', 'doc_pdmweid', 'conf_name', 'conf_quantity'], 'integer'],
            [['xml_file_created', 'creation_date', 'approval_date', 'created', 'changed'], 'safe'],
            [['type', 'description1', 'description2', 'description3', 'item_name', 'creator', 'approver', 'product_responsible'], 'string', 'max' => 255],
            [['vaultname', 'doc_aliasset', 'xml_file_name'], 'string', 'max' => 100],
            [['drawing_number'], 'string', 'max' => 20],
            [['revision', 'state'], 'string', 'max' => 10],
            [['pdf_contents'], 'string', 'max' => 5000],
            [['entity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Entity::className(), 'targetAttribute' => ['entity_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'entity_id' => 'Entity ID',
            'date' => 'Date',
            'type' => 'Type',
            'vaultname' => 'Vaultname',
            'doc_aliasset' => 'Doc Aliasset',
            'doc_pdmweid' => 'Doc Pdmweid',
            'drawing_number' => 'Drawing Number',
            'conf_name' => 'Conf Name',
            'conf_quantity' => 'Conf Quantity',
            'description1' => 'Description1',
            'description2' => 'Description2',
            'description3' => 'Description3',
            'revision' => 'Revision',
            'item_name' => 'Item Name',
            'creator' => 'Creator',
            'approver' => 'Approver',
            'product_responsible' => 'Product Responsible',
            'state' => 'State',
            'xml_file_name' => 'Xml File Name',
            'pdf_contents' => 'Pdf Contents',
            'xml_file_created' => 'Xml File Created',
            'creation_date' => 'Creation Date',
            'approval_date' => 'Approval Date',
            'created' => 'Created',
            'changed' => 'Changed',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntity()
    {
        return $this->hasOne(Entity::className(), ['id' => 'entity_id']);
    }
}
