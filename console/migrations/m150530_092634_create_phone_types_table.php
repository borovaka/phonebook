<?php

use yii\db\Schema;
use yii\db\Migration;

class m150530_092634_create_phone_types_table extends Migration
{
    public function up()
    {
        $this->createTable('phone_types', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
        ]);
    }

    public function down()
    {
        $this->dropTable('phone_types');
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
