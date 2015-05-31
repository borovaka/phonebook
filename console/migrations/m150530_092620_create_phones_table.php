<?php

use yii\db\Schema;
use yii\db\Migration;

class m150530_092620_create_phones_table extends Migration
{
    public function up()
    {
        $this->createTable('phones', [
            'id' => Schema::TYPE_PK,
            'phone' => Schema::TYPE_STRING . ' NOT NULL',
            'id_contact' => Schema::TYPE_INTEGER,
            'id_type' => Schema::TYPE_INTEGER,
            'created_at' => Schema::TYPE_DATETIME,
            'updated_at' => Schema::TYPE_DATETIME,
        ]);

        $this->createIndex('id_type','phones','id_type');
        $this->addForeignKey('on_delete_contact','phones','id_contact','contacts','id','CASCADE');

    }

    public function down()
    {
        $this->dropTable('phones');
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
