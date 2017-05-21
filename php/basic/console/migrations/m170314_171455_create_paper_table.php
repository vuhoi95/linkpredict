<?php

use yii\db\Migration;

/**
 * Handles the creation of table `paper`.
 */
class m170314_171455_create_paper_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('paper', [
            'id_paper' => $this->primaryKey(),
            'paper' => $this->string(255),
            'abstracts' => $this->text(),
            'title' => $this->text(),
            'year' => $this->integer(11),
            'authors' => $this->text(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('paper');
    }
}
