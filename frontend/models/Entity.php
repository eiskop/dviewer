<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "entity".
 *
 * @property integer $id
 * @property string $name
 * @property string $name_short
 * @property string $country
 * @property string $county
 * @property string $city
 * @property string $street
 * @property string $zip_code
 * @property integer $updated_at
 * @property integer $created_at
 *
 * @property Drawing[] $drawings
 * @property User[] $users
 */
class Entity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'entity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'name_short', 'country', 'county', 'city', 'street', 'zip_code', 'updated_at', 'created_at'], 'required'],
            [['updated_at', 'created_at'], 'integer'],
            [['name'], 'string', 'max' => 200],
            [['name_short'], 'string', 'max' => 2],
            [['country', 'county', 'city', 'street'], 'string', 'max' => 255],
            [['zip_code'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'name_short' => 'Name Short',
            'country' => 'Country',
            'county' => 'County',
            'city' => 'City',
            'street' => 'Street',
            'zip_code' => 'Zip Code',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDrawings()
    {
        return $this->hasMany(Drawing::className(), ['entity_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['entity_id' => 'id']);
    }
}
