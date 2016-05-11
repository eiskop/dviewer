<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property integer $entity_id
 * @property string $username
 * @property string $first_name
 * @property string $last_name
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $last_login
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Entity $entity
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity_id', 'username', 'first_name', 'last_name', 'auth_key', 'password_hash', 'email', 'last_login', 'created_at', 'updated_at'], 'required'],
            [['entity_id', 'status', 'last_login', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['first_name', 'last_name'], 'string', 'max' => 100],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['password_reset_token'], 'unique'],
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
            'username' => 'Username',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'last_login' => 'Last Login',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
