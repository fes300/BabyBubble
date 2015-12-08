<?php

use Phinx\Migration\AbstractMigration;

class Clients extends AbstractMigration
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
        $users = $this->table('clients', array('id' => false, 'primary_key' => array('uuid')));
        $users->addColumn('uuid', 'uuid')
            ->addColumn('first_name', 'string', array('limit'=>255))
            ->addColumn('last_name', 'string', array('limit'=>255))
            ->addColumn('tutors', 'json')
            ->addColumn('address', 'string', array('limit'=>255, 'null'=>true))
            ->addColumn('phone', 'string', array('limit'=>255, 'null'=>true))
            ->addColumn('email', 'string', array('limit'=>255, 'null'=>true))
            ->addColumn('birthday', 'timestamp')
            ->addColumn('medical_conditions', 'string', array('limit'=>1000))
            ->addColumn('first_contact_info', 'string', array('limit'=>1000))
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
