<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prestaver".
 *
 * @property int $id
 * @property string $name Версия Prestashop
 * @property string $value Значение
 */
class Prestaver extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prestaver';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Версия Prestashop',
            'value' => 'Значение',
        ];
    }
}
