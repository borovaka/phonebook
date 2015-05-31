<?php

namespace frontend\modules\phonebook\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "phones".
 *
 * @property integer $id
 * @property string $phone
 * @property integer $id_contact
 * @property integer $id_type
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Contacts $idContact
 * @property PhoneType[] $phoneType
 */
class Phone extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'phones';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone'], 'required','message' => 'Телефонът е задължителен.'],
            [['id_contact', 'id_type'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['phone'], 'integer','message' => 'Телефонът трябва да е само цифри.'],
            [['id_type'],'exist','targetClass' => '\frontend\modules\phonebook\models\PhoneType']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Номер',
            'id_type' => 'Тип',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdContact()
    {
        return $this->hasOne(Contacts::className(), ['id' => 'id_contact']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoneType()
    {
        return $this->hasOne(PhoneType::className(), ['id' => 'id_type']);

    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }
}
