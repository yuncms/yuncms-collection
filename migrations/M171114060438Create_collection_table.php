<?php

namespace yuncms\collection\migrations;

use yii\db\Migration;

class M171114060438Create_collection_table extends Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /**
         * 用户收藏表
         */
        $this->createTable('{{%collections}}', [
            'id' => $this->primaryKey()->unsigned()->comment('ID'),
            'user_id' => $this->integer()->unsigned()->notNull()->comment('User Id'),
            'model_id' => $this->integer()->notNull()->comment('Model Id'),
            'model_class' => $this->string()->notNull()->comment('Model Class'),
            'subject' => $this->string()->comment('Subject'),
            'created_at' => $this->integer()->unsigned()->notNull()->comment('Created At'),
            'updated_at' => $this->integer()->unsigned()->notNull()->comment('Updated At'),
        ], $tableOptions);

        $this->createIndex('collections_index', '{{%collections}}', ['user_id', 'model_id', 'model_class'], true);
        $this->addForeignKey('{{%collections_fk_1}}', '{{%collections}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

    }

    public function safeDown()
    {
        $this->dropTable('{{%collections}}');
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M171114060438Create_collection_table cannot be reverted.\n";

        return false;
    }
    */
}
