<?php

namespace app\models\presents;

use Yii;

/**
 * This is the model class for table "presents_objects".
 *
 * @property int $id
 * @property string $name
 * @property int $present_id Юзер
 *
 * @property Presents $present
 */
class PresentsObjects extends \yii\db\ActiveRecord implements PresentsInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'presents_objects';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'present_id'], 'required'],
            [['present_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['present_id'], 'unique'],
            [['present_id'], 'exist', 'skipOnError' => true, 'targetClass' => Presents::className(), 'targetAttribute' => ['present_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'present_id' => 'Юзер',
        ];
    }

    /**
     * Gets query for [[Present]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPresent()
    {
        return $this->hasOne(Presents::className(), ['id' => 'present_id']);
    }

    public function send()
    {
        return true;
    }
}
