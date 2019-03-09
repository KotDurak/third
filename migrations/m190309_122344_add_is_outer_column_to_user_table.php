<?php

use yii\db\Migration;

/**
 * Handles adding is_outer to table `{{%user}}`.
 */
class m190309_122344_add_is_outer_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'is_outer', $this->integer()->unique());

        $this->insert('{{%user}}', [
            'name' => 'Сотрудник',
            'surname' => 'Внешний',
            'email' => 'outer@tt.com',
            'is_outer'  => 1,
            'is_root'   => 0,
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('user', ['is_outer' => 1]);
        $this->dropColumn('{{%user}}', 'is_outer');
    }
}
