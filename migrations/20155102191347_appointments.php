<?php

use Phinx\Migration\AbstractMigration;

class Appointments extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     **/
    public function change()
    {
        $users = $this->table('appointments', array('id' => false, 'primary_key' => array('uuid')));
        $users->addColumn('uuid', 'uuid')
            ->addColumn('client_uuid', 'uuid')
            ->addColumn('client_name', 'string', array('limit'=>255))
            ->addColumn('product_uuid', 'uuid')
            ->addColumn('product_name', 'string', array('limit'=>255))
            ->addColumn('product_duration', 'float')
            ->addColumn('date', 'timestamp')
            ->addColumn('all_day', 'boolean', array('null'=>true))
            ->addColumn('created', 'timestamp')
            ->addColumn('updated', 'timestamp')
            ->create();
    }


    /**
     * Migrate Up.
     */
    public function up()
    {

    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}
