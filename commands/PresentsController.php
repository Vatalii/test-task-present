<?php
namespace app\commands;

use app\models\presents\Presents;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\ArrayHelper;

class PresentsController extends Controller
{
    public function actionIndex($n = 10)
    {
        $presents = Presents::find()
            ->andWhere(['is_send'=>false,'is_refuse_prise'=>false]);

        foreach ($presents->batch($n) as $batch) {
            echo "\n";

            foreach ($batch as $present){
                if($present->send()){
                    echo ".";
                }
            }
        }


        return ExitCode::OK;
    }
}
