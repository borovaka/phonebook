<?php

use frontend\modules\phonebook\models\PhoneType;
use yii\db\Schema;
use yii\db\Migration;

class m150530_102429_seed_phone_types_table extends Migration
{
    public function up()
    {
        $types = ['Личен','Служебен'];

        foreach($types as $type) {
            $phonetype = new PhoneType();
            $phonetype->name = $type;
            $phonetype->save();
        }
    }

    public function down()
    {
        $this->truncateTable('phone_types');
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
