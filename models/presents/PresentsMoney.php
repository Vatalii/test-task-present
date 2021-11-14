<?php

namespace app\models\presents;

use http\Client;
use Yii;

/**
 * This is the model class for table "presents_money".
 *
 * @property int $id
 * @property int $sum
 * @property int $present_id Юзер
 *
 * @property Presents $present
 */
class PresentsMoney extends \yii\db\ActiveRecord implements PresentsInterface
{

    const K = 10;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'presents_money';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sum', 'present_id'], 'required'],
            [['sum', 'present_id'], 'integer'],
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
            'sum' => 'Sum',
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
        return $this->present->type == Presents::TYPE_MONEY ? $this->sendMoney() : $this->sendBonuses();
    }

    public function sendMoney()
    {
//        $client = new Client();
//
//        $response = $client->createRequest()
//            ->setMethod('POST')
//            ->setUrl('https://example.com/')
//            ->setData(['name' => 'Money'])
//            ->send();
//        if ($response->isOk) {
//            return true;
//        }else{
//            return false;
//        }

        return true;

    }

    public function sendBonuses()
    {
//        $client = new Client();
//
//        $response = $client->createRequest()
//            ->setMethod('POST')
//            ->setUrl('https://example.com/')
//            ->setData(['name' => 'Bonuses'])
//            ->send();
//        if ($response->isOk) {
//            return true;
//        }else{
//            return false;
//        }
        return true;
    }

    public function convertToBonuses($rollBack=false)
    {
        $this->sum = (int)($this->sum * self::K);
        return $this->validate() && $this->save();
    }
}
