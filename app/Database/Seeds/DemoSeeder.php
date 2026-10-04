<?php
namespace App\Database\Seeds;
use CodeIgniter\Database\Seeder;
class DemoSeeder extends Seeder
{
    public function run()
    {
        if ($this->db->table('users')->where('username', 'demo')->countAllResults() === 0) {
            $this->db->table('users')->insert(['username' => 'demo', 'full_name' => 'Demo Student',
                'email' => 'demo@example.com', 'password' => password_hash(env('DEMO_PASSWORD', 'TasksToday2026!'), PASSWORD_DEFAULT)]);
        }
        if ($this->db->table('tasks')->countAllResults() === 0) {
            foreach ([['Review project requirements', 'pending', 0], ['Build the task dashboard', 'in_progress', 0],
                ['Test login and task forms', 'pending', 1], ['Prepare the database migration', 'completed', 0]] as [$title, $status, $offset]) {
                $this->db->table('tasks')->insert(['title' => $title, 'description' => 'A sample task for your project workflow.',
                    'task_date' => date('Y-m-d', strtotime("+$offset days")), 'status' => $status, 'is_archived' => 0,
                    'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
            }
        }
    }
}
