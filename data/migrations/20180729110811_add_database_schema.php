<?php

use Phinx\Migration\AbstractMigration;

class AddDatabaseSchema extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $users = $this->table('users');
        $users
            ->addColumn('uuid', 'char', ['limit' => 40, 'null' => false])
            ->addColumn('name', 'string', ['limit' => 150, 'null' => false])
            ->addColumn('email', 'string', ['limit' => 100, 'null' => true])
            ->addIndex(['email'], ['unique' => true])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('status', 'enum', ['values' => ['active', 'disabled'], 'default' => 'active'])
            ->create();

        $specialOffers = $this->table('special_offers');
        $specialOffers
            ->addColumn('name', 'string', ['limit' => 150, 'null' => false])
            ->addColumn('discount', 'decimal', ['scale' => 2, 'precision' => 15, 'default' => 0.00, 'null' => false])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('status', 'enum', ['values' => ['active', 'disabled'], 'default' => 'active'])
            ->create();

        $voucher = $this->table('vouchers');
        $voucher
            ->addColumn('code', 'string', ['limit' => 150, 'null' => false])
            ->addColumn('user_id','integer', ['limit' => 11, 'null' => false])
            ->addColumn('special_offer_id','integer', ['limit' => 11, 'null' => false])
            ->addColumn('expiry_date', 'timestamp', ['null' => false])
            ->addColumn('expire_interval','integer', ['limit' => 11, 'null' => false])
            ->addColumn('is_used','boolean', ['default' => false])
            ->addColumn('date_of_usage','timestamp', ['null' => true])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('status', 'enum', ['values' => ['active', 'disabled'], 'default' => 'active'])
            ->create();

        $voucher
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'NO_ACTION', 'update' => 'CASCADE'])
            ->addForeignKey('special_offer_id', 'special_offers', 'id', ['delete' => 'NO_ACTION', 'update' => 'CASCADE'])
            ->addIndex(['user_id'])
            ->addIndex(['special_offer_id'])
            ->update();

    }
}
