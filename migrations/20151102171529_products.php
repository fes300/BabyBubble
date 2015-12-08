<?php

use Phinx\Migration\AbstractMigration;

class Products extends AbstractMigration
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
        $users = $this->table('products', array('id' => false, 'primary_key' => array('uuid')));
        $users->addColumn('uuid', 'uuid')
            ->addColumn('name', 'string', array('limit'=>255))
            ->addColumn('description', 'string', array('limit'=>500))
            ->addColumn('duration', 'float')
            ->addColumn('active', 'boolean')
            ->addColumn('created', 'timestamp')
            ->addColumn('updated', 'timestamp')
            ->addIndex(array('name'))
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
