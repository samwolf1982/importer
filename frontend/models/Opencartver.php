<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "opencartver".
 *
 * @property int $id
 * @property string $name Версия Opencart
 * @property string $value Значение
 */
class Opencartver extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'opencartver';
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
            'name' => 'Версия Opencart',
            'value' => 'Значение',
        ];
    }
}
