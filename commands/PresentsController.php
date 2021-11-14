<?php
namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;

class PresentsController extends Controller
{
    public function actionIndex($n = 10)
    {
        echo $n . "\n";

        return ExitCode::OK;
    }
}
