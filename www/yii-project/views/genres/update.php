<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Books */

$this->title = 'Редактировать автора';
?>
<h1 class="fw-semibold">Редактировать жанр</h1>

<!-- Форма обновления жанра -->

<?php $form = ActiveForm::begin(); ?>
<?= Html::hiddenInput('_csrf', Yii::$app->request->getCsrfToken(), ['bypass-url-check' => true]) ?>

<?= $form->field($genres, 'genre_name')->textInput()->label('Имя жанра'); ?>

<div class="form-group text-center">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success mx-2']) ?>
    <span class="mb-3"><?= Html::a('Назад', ['/genres/index'], ['class' => 'btn btn-danger mx-2']) ?></span>
</div>

<?php ActiveForm::end(); ?>