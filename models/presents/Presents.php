<?php

namespace app\models\presents;

use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use Yii;

/**
 * This is the model class for table "presents".
 *
 * @property int $id
 * @property int $type
 * @property int $is_send
 * @property int $is_refuse_prise
 * @property int $user_id Юзер

 * @property PresentsObjects|PresentsMoney $presentType
 */
class Presents extends \yii\db\ActiveRecord
{
    const TYPE_MONEY = 1;
    const TYPE_BONUS = 2;
    const TYPE_OBJECT = 3;

    const MIN_SUM = 100;
    const MAX_SUM = 1000;

    const LIMIT_MONEY = 100000;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'presents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'is_send', 'is_refuse_prise', 'user_id'], 'integer'],
            ['type', 'in', 'range' => [self::TYPE_MONEY, self::TYPE_OBJECT, self::TYPE_BONUS]],
            [['user_id'], 'required'],
            [['user_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'is_send' => 'Is Send',
            'is_refuse_prise' => 'Is Refuse Prise',
            'user_id' => 'Юзер',
        ];
    }


    public function getFirstPresent()
    {
        return Presents::find()->andWhere(['user_id'=>Yii::$app->user->getId()])->one();
    }


    /**
     * Отправка подарка
     * @return bool
     */
    public function send()
    {
        if(!$this->presentType){
            return false;
        }

        $is_money = $this->type == self::TYPE_MONEY;

        if($this->presentType->send($is_money)){
            $this->is_send = 1;

            return $this->validate() && $this->save();
        }

        return false;
    }

    public function refusePrise()
    {
        if($this->type != self::TYPE_OBJECT){
            return false;
        }

        $this->is_refuse_prise = 1;
        return $this->validate() && $this->save();
    }

    public function convertToBonuses()
    {
        if($this->type == self::TYPE_MONEY){
            $this->type = self::TYPE_BONUS;

            return $this->presentType->convertToBonuses() && $this->validate() && $this->save();
        }

        return false;
    }


    /**
     * Генерируем подарок
     *
     * @return Presents|null
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function generatePresent()
    {
        $model = new self();
        $model->type = rand(1, 3);

        //Change type when over limit
        if($model->type==self::TYPE_MONEY && !$this->checkMoneyLimit()){
            $model->type = self::TYPE_BONUS;
        }

        $model->user_id = Yii::$app->user->getId();

        if ($model->validate() && $model->save()) {
            // link type model or delete base model
            if (!$model->createPresentTypeModel()) {
                $model->delete();
                return null;
            }
        } else {
            return null;
        }

        return $model;
    }

    /**
     * Проверка лимиту денег
     * @return bool
     */
    public function checkMoneyLimit()
    {
        $sum = PresentsMoney::find()->sum('sum');
        return self::LIMIT_MONEY >= $sum;
    }

    /**
     * Генерируем подарок в зависимости от типа
     * @return bool|null
     */
    private function createPresentTypeModel()
    {
        $type = $this->type;
        if (!$type) {
            return null;
        }

        $model = new PresentsMoney();

        if ($type == self::TYPE_OBJECT) {
            $model = PresentsObjects::find()
                ->andWhere(['present_id'=>null])
                ->one();
        } else {
            $model->sum = rand(self::MIN_SUM, self::MAX_SUM);
        }

        $model->present_id = $this->id;

        return $model->validate() && $model->save();
    }

    /**
     * @return PresentsMoney|PresentsObjects|null
     */
    public function getPresentType()
    {
        if ($this->type == self::TYPE_OBJECT) {
            return PresentsObjects::findOne(['present_id'=>$this->id]);
        }

        return PresentsMoney::findOne(['present_id'=>$this->id]);
    }

    public function getText()
    {
        $text = '';

        $is_send = $this->is_send ? " - отправлено" : '';
        $is_refuse_prise = $this->is_refuse_prise ? " - отказ" : '';

        switch ($this->type){
            case self::TYPE_OBJECT:
                return $this->presentType->name.$is_send.$is_refuse_prise;
            case self::TYPE_MONEY:
                return "Сума: {$this->presentType->sum} $is_send $is_refuse_prise";
            case self::TYPE_BONUS:
                return "Бонусы {$this->presentType->sum} $is_send $is_refuse_prise";
        }

        return $text;
    }
}
