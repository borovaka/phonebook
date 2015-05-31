<?php

use yii\db\Schema;
use yii\db\Migration;

class m150530_092612_create_contacts_table extends Migration
{
    public function up()
    {

        $this->createTable('contacts', [
            'id' => Schema::TYPE_PK,
            'first_name' => Schema::TYPE_STRING . ' NOT NULL',
            'last_name' => Schema::TYPE_STRING,
            'created_at' => Schema::TYPE_DATETIME,
            'updated_at' => Schema::TYPE_DATETIME,
        ]);

    }

    public function down()
    {
        $this->dropTable('contacts');
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
