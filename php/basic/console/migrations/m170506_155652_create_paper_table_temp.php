<?php

use yii\db\Migration;

class m170506_155652_create_paper_table_temp extends Migration
{
    public function up()
    {
        $this->createTable('papertemp', [
            'id_paper' => $this->primaryKey(),
            'paper' => $this->string(255),
            'abstracts' => $this->text(),
            'title' => $this->text(),
            'year' => $this->integer(11),
            'authors' => $this->text(),
        ]);
    }

    public function down()
    {
        echo "m170506_155652_create_paper_table_temp cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
