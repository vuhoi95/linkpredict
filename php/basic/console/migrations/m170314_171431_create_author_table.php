<?php

use yii\db\Migration;

/**
 * Handles the creation of table `author`.
 */
class m170314_171431_create_author_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('author', [
            'id_author' => $this->primaryKey(),
            'author' => $this->string(255),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('author');
    }
}
