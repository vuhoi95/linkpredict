<?php

use yii\db\Migration;

class m170506_155703_create_author_table_temp extends Migration
{
    public function up()
    {
        $this->createTable('authortemp', [
            'id_author' => $this->primaryKey(),
            'author' => $this->string(255),
        ]);
    }

    public function down()
    {
        echo "m170506_155703_create_author_table_temp cannot be reverted.\n";

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
