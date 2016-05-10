<?php

use yii\db\Migration;

class m160328_053959_create_table_comments extends Migration
{
    public function up()
    {
        $this->createTable('{{%comments}}', [
            'id' => 'pk',
            'object'=>'varchar(100)',
            'object_id'=>'int(10)',
            'text'=>'text',
            'note'=>'varchar(255)',
            'user_id'=>'int(10)',
            'date_create' => 'datetime',
            'date_update'=>'datetime',
            'status'=>'int(10)',
            'parent_id'=>'int(10)'
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%comments}}');
    }
}
