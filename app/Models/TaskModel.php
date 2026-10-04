<?php
namespace App\Models;
use CodeIgniter\Model;
class TaskModel extends Model
{
    protected $table = 'tasks';
    protected $allowedFields = ['title', 'description', 'task_date', 'status', 'is_archived'];
    protected $useTimestamps = true;
    public function active(): self { return $this->where('is_archived', 0); }
}
