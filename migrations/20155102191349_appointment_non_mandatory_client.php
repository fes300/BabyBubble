<?php

use Phinx\Migration\AbstractMigration;

class AppointmentNonMandatoryClient extends AbstractMigration
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
        $users = $this->table('appointments');
        $users->changeColumn('client_uuid', 'uuid', array('null'=>true))
            ->save();
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
