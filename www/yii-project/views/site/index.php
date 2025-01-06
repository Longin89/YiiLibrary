<?php

use yii\helpers\Html;

/** @var yii\web\View $this */

$this->title = 'My Books Database';
?>

<h1 class="display-3 fw-semibold my-5 text-center">База данных книг на основе Yii2</h1>
<div class="container d-flex flex-column align-items-center row-gap-5">
    <div class="btn-group w-25 text-uppercase">
        <?= Html::a('Книги', ['/books/index'], ['class' => 'btn btn-success btn-block']) ?>
    </div>

    <div class="btn-group w-25 text-uppercase">
        <?= Html::a('Авторы', ['/authors/index'], ['class' => 'btn btn-primary btn-block']) ?>
    </div>

    <div class="btn-group w-25 text-uppercase">
        <?= Html::a('Жанры', ['/genres/index'], ['class' => 'btn btn-secondary btn-block']) ?>
    </div>
</div>

<div class="body-content">
    <h2 class="text-center my-5">Данное приложение предоставляет базовый набор CRUD-операций с базой данных книг.</h2>
    <span class="bg-success text-white p-2 rounded-1 text-uppercase fw-semibold d-inline-block">Книги</span><p class="d-inline-block mx-1">содержат в себе список книг в базе данных, а так-же функционал для операций с книгами. Имеется возможность фильтрации согласно ТЗ</p>
    <br>
    <span class="bg-primary text-white p-2 rounded-1 text-uppercase fw-semibold d-inline-block">Авторы</span><p class="d-inline-block mx-1">содержат в себе список авторов, форму для их добавления и редактирования.</p>
    <br>
    <span class="bg-secondary text-white p-2 rounded-1 text-uppercase fw-semibold d-inline-block">Жанры</span><p class="d-inline-block mx-1">содержат в себе список жанров, форму для их добавления и редактирования.</p>
</div>