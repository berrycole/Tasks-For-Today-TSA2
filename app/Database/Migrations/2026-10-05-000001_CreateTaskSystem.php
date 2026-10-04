<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class CreateTaskSystem extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'username' => ['type' => 'VARCHAR', 'constraint' => 50],
            'full_name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'email' => ['type' => 'VARCHAR', 'constraint' => 254],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('username');
        $this->forge->createTable('users');
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => 150],
            'description' => ['type' => 'TEXT'],
            'task_date' => ['type' => 'DATE'],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'pending'],
            'is_archived' => ['type' => 'BOOLEAN', 'default' => false],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['is_archived', 'task_date']);
        $this->forge->createTable('tasks');
    }
    public function down()
    {
        $this->forge->dropTable('tasks');
        $this->forge->dropTable('users');
    }
}
