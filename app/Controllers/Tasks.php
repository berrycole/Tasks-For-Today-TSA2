<?php
namespace App\Controllers;
use App\Models\TaskModel;
use CodeIgniter\Exceptions\PageNotFoundException;
class Tasks extends BaseController
{
    public function index(): string
    {
        return view('tasks/index', ['title' => 'Task List', 'activePage' => 'tasks',
            'tasks' => (new TaskModel())->active()->orderBy('task_date')->orderBy('id', 'DESC')->findAll()]);
    }
    public function new(): string
    {
        return $this->form(['title' => '', 'description' => '', 'task_date' => date('Y-m-d'), 'status' => 'pending']);
    }
    public function create() { return $this->saveTask(); }
    public function edit(int $id): string { return $this->form($this->findActive($id)); }
    public function update(int $id)
    {
        $this->findActive($id);
        return $this->saveTask($id);
    }
    public function delete(int $id)
    {
        $this->findActive($id);
        (new TaskModel())->update($id, ['is_archived' => 1]);
        return redirect()->to(site_url('tasks'))->with('message', 'Task archived. Its record has been preserved.');
    }
    private function saveTask(?int $id = null)
    {
        $data = $this->request->getPost(['title', 'description', 'task_date', 'status']);
        foreach ($data as $key => $value) {
            if (is_string($value)) { $data[$key] = trim($value); }
        }
        $rules = ['title' => 'required|max_length[150]', 'description' => 'permit_empty|max_length[2000]',
            'task_date' => 'required|valid_date[Y-m-d]', 'status' => 'required|in_list[pending,in_progress,completed]'];
        if (! $this->validateData($data, $rules)) {
            $this->response->setStatusCode(422);
            $safe = array_map(static fn ($value) => is_string($value) ? $value : '', $data);
            return $this->form($safe + ($id === null ? [] : ['id' => $id]), $this->validator->getErrors());
        }
        $data['description'] ??= '';
        $model = new TaskModel();
        $id === null ? $model->insert($data) : $model->update($id, $data);
        return redirect()->to(site_url('tasks'))->with('message', $id === null ? 'Task created.' : 'Task updated.');
    }
    private function findActive(int $id): array
    {
        return (new TaskModel())->active()->find($id) ?? throw PageNotFoundException::forPageNotFound('Task not found.');
    }
    private function form(array $task, array $errors = []): string
    {
        return view('tasks/form', compact('task', 'errors') + ['title' => isset($task['id']) ? 'Edit Task' : 'New Task', 'activePage' => 'tasks']);
    }
}
