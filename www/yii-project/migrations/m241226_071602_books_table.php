<?php

use yii\db\Migration;

/**
 * Class m241226_071602_books_table
 */
class m241226_071602_books_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable('{{%books}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'genre_id' => $this->integer()->notNull(),
            'year' => $this->integer()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci');

        $this->createIndex('books_year_index', 'books', ['year']);
        $this->createIndex('books_title_index', 'books', ['title']);

        $this->createTable('{{%authors}}', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci');

        $this->createIndex('authors_first_name_index', 'authors', ['first_name']);
        $this->createIndex('authors_last_name_index', 'authors', ['last_name']);


        $this->createTable('{{%genres}}', [
            'id' => $this->primaryKey(),
            'genre_name' => $this->string()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci');

        $this->createIndex('genres_genre_name_index', 'genres', ['genre_name']);

        $this->createTable('{{%links}}', [
            'id' => $this->primaryKey(),
            'book_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci');

        $this->addForeignKey(
            'fk_links_books',
            '{{%links}}',
            ['book_id'],
            '{{%books}}',
            ['id'],
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_links_authors',
            '{{%links}}',
            ['author_id'],
            '{{%authors}}',
            ['id'],
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('books_year_index', 'books');
        $this->dropIndex('books_title_index', 'books');
        $this->dropIndex('authors_first_name_index', 'authors');
        $this->dropIndex('authors_last_name_index', 'authors');
        $this->dropIndex('genres_genre_name_index', 'genres');
        $this->dropTable('books');
        $this->dropTable('authors');
        $this->dropTable('links');
        $this->dropTable('genres');
    }
}
