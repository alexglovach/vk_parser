<?php


use Phinx\Migration\AbstractMigration;

class TableVkImages extends AbstractMigration
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
        $this->table('vk_images')
            ->addColumn('vk_id', 'integer', ['null' => false])
            ->addColumn('image_src', 'string', ['null' => true, 'limit' => 100])
            ->addColumn('hash', 'integer', ['null' => false, 'signed' => false])
            ->addIndex(['vk_id', 'hash'])
            ->save();
    }
}
