<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $present \app\models\presents\Presents */

$this->title = 'Presents';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="presents-index">

    <?php if (!$present): ?>
        <p>
            <?= Html::a('Получить подарок', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php else: ?>

        <h1>
            <?= $present->getText() ?>
        </h1>

        <?php if (!$present->is_send && !$present->is_refuse_prise): ?>
            <p>
                <?= Html::a('Забрать подарок', ['send'], ['class' => 'btn btn-success']) ?>
            </p>

            <?php if ($present->type == $present::TYPE_OBJECT): ?>
                <p>
                    <?= Html::a('Отказаться', ['refuse-prise'], ['class' => 'btn btn-danger']) ?>
                </p>
            <?php endif; ?>

            <?php if ($present->type == $present::TYPE_MONEY): ?>
                <p>
                    <?= Html::a("конвертировать в бонусы", ['convert-to-bonuses'], ['class' => 'btn btn-success']) ?>
                </p>
            <?php endif; ?>


        <?php endif; ?>


    <?php endif; ?>
</div>
