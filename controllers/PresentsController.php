<?php

namespace app\controllers;

use app\models\presents\Presents;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PresentsController implements the CRUD actions for Presents model.
 */
class PresentsController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'only' => ['logout'],
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Presents models.
     * @return mixed
     */
    public function actionIndex()
    {
        $present = new Presents();

        return $this->render('index', ['present' => $present->getFirstPresent()]);
    }

    /**
     * Generate random present
     * @return \yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Presents();
        $model = $model->generatePresent();

        return $this->redirect(['index']);
    }

    public function actionSend()
    {
        $present = new Presents();
        $present = $present->getFirstPresent();
        if($present){
            $present->send();
        }

        return $this->redirect(['index']);
    }

    /**
     * Отказ
     *
     * @return \yii\web\Response
     */
    public function actionRefusePrise()
    {
        $present = new Presents();
        $present = $present->getFirstPresent();
        if($present){
            $present->refusePrise();
        }

        return $this->redirect(['index']);
    }

    /**
     * Конвертировать в бонусы
     * @return \yii\web\Response
     */
    public function actionConvertToBonuses()
    {
        $present = new Presents();
        $present = $present->getFirstPresent();
        if($present){
            $present->convertToBonuses();
        }

        return $this->redirect(['index']);
    }


    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Presents model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Presents the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Presents::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
