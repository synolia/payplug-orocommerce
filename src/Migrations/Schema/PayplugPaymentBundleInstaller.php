<?php

namespace Payplug\Bundle\PaymentBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class PayplugPaymentBundleInstaller implements Installation
{
    /**
     * {@inheritdoc}
     */
    public function getMigrationVersion()
    {
        return 'v1_0';
    }

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        /** Tables generation **/
        $this->alterOroIntegrationTransportTable($schema);
        $this->createPayplugShortLabelTable($schema);
        $this->createPayplugTransLabelTable($schema);

        /** Foreign keys generation **/
        $this->addPayplugShortLabelForeignKeys($schema);
        $this->addPayplugTransLabelForeignKeys($schema);
    }

    /**
     * Alter oro_integration_transport table
     *
     * @param Schema $schema
     */
    protected function alterOroIntegrationTransportTable(Schema $schema)
    {
        $table = $schema->getTable('oro_integration_transport');
        $table->addColumn('payplug_login', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('payplug_debug_mode', 'boolean', ['default' => '0', 'notnull' => false]);
        $table->addColumn('payplug_api_key_test', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('payplug_api_key_live', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('payplug_mode', 'string', ['notnull' => false, 'length' => 255]);
    }

    /**
     * Create payplug_short_label table
     *
     * @param Schema $schema
     */
    protected function createPayplugShortLabelTable(Schema $schema)
    {
        $table = $schema->createTable('payplug_short_label');
        $table->addColumn('transport_id', 'integer', []);
        $table->addColumn('localized_value_id', 'integer', []);
        $table->setPrimaryKey(['transport_id', 'localized_value_id']);
        $table->addUniqueIndex(['localized_value_id'], 'UNIQ_C176860AEB576E89');
        $table->addIndex(['transport_id'], 'IDX_C176860A9909C13F', []);
    }

    /**
     * Create payplug_trans_label table
     *
     * @param Schema $schema
     */
    protected function createPayplugTransLabelTable(Schema $schema)
    {
        $table = $schema->createTable('payplug_trans_label');
        $table->addColumn('transport_id', 'integer', []);
        $table->addColumn('localized_value_id', 'integer', []);
        $table->setPrimaryKey(['transport_id', 'localized_value_id']);
        $table->addUniqueIndex(['localized_value_id'], 'UNIQ_FEB043D0EB576E89');
        $table->addIndex(['transport_id'], 'IDX_FEB043D09909C13F', []);
    }

    /**
     * Add payplug_short_label foreign keys.
     *
     * @param Schema $schema
     */
    protected function addPayplugShortLabelForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('payplug_short_label');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_integration_transport'),
            ['transport_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_fallback_localization_val'),
            ['localized_value_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
    }

    /**
     * Add payplug_trans_label foreign keys.
     *
     * @param Schema $schema
     */
    protected function addPayplugTransLabelForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('payplug_trans_label');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_integration_transport'),
            ['transport_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_fallback_localization_val'),
            ['localized_value_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
    }
}
