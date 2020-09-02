<?php

use yii\db\Migration;

/**
 * Class m200901_171257_start_tables
 */
class m200901_171257_start_tables extends Migration
{   

    public function up()
    {
        $this->createTable('campaigns', [
            'id'   => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'clicks' => $this->integer(),
            'leads' => $this->integer(),
            'conversion_rate' => $this->float(),
            'trafic_id'  => $this->integer(),
        ]);

        $this->createTable('traffic', [
            'id'   => $this->primaryKey(),
            'ts_name' => $this->string(255),
            'domain_name' => $this->string(255),
        ]);
        $this->createTable('update_log', [
            'id'   => $this->bigPrimaryKey(),
            'time' => $this->datetime()->notNull(),
        ]);

        $this->addForeignKey(
            'FK_traffic_campaigns', 
            'campaigns', 'trafic_id',                                
            'traffic', 'id', 
            'CASCADE', 
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200901_171257_start_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200901_171257_start_tables cannot be reverted.\n";

        return false;
    }
    */

}