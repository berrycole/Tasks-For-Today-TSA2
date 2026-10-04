<?php
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use App\Models\TaskModel;

final class PagesTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION = [];
        $db = db_connect();
        if (! $db->tableExists('tasks')) {
            require_once APPPATH . 'Database/Migrations/2026-10-05-000001_CreateTaskSystem.php';
            (new App\Database\Migrations\CreateTaskSystem())->up();
        }
        $db->table('tasks')->emptyTable();
        $db->table('users')->emptyTable();
        (new App\Database\Seeds\DemoSeeder(config('Database')))->run();
    }

    public function testPublicPagesAndEscaping(): void
    {
        (new TaskModel())->insert(['title' => '<script>alert(1)</script>', 'description' => '', 'task_date' => date('Y-m-d'), 'status' => 'pending']);
        foreach (['/', '/tasks', '/profile', '/about', '/login'] as $path) {
            $result = $this->get($path);
            $result->assertStatus(200);
            $result->assertSee('Tasks for Today');
            $result->assertDontSee('<script>alert(1)</script>');
        }
        $this->get('/tasks')->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;');
    }

    public static function protectedRoutes(): array
    {
        return [['GET', '/tasks/new'], ['GET', '/tasks/1/edit'], ['POST', '/tasks'], ['POST', '/tasks/1'], ['POST', '/tasks/1/delete']];
    }

    /** @dataProvider protectedRoutes */
    public function testGuestsRedirectWithoutChangingRecords(string $method, string $path): void
    {
        $before = db_connect()->table('tasks')->get()->getResultArray();
        $this->call($method, $path, [csrf_token() => csrf_hash()])->assertRedirectTo(site_url('login'));
        $this->assertSame($before, db_connect()->table('tasks')->get()->getResultArray());
    }

    public function testLoginAndLogout(): void
    {
        $hash = db_connect()->table('users')->get()->getRowArray()['password'];
        $this->assertTrue(password_verify('TasksToday2026!', $hash));
        $bad = $this->post('/login', [csrf_token() => csrf_hash(), 'username' => 'demo', 'password' => 'WrongSecret']);
        $bad->assertSee('The username or password is incorrect.');
        $bad->assertDontSee('WrongSecret');
        $this->assertNotSame(true, session('isLoggedIn'));
        $this->post('/login', [csrf_token() => csrf_hash(), 'username' => 'demo', 'password' => 'TasksToday2026!'])->assertRedirectTo(site_url('tasks'));
        $this->assertTrue(session('isLoggedIn'));
        $this->assertNull(session('password'));
        $this->withSession(['isLoggedIn' => true, 'username' => 'demo'])->get('/tasks/new')->assertStatus(200);
        $this->post('/logout', [csrf_token() => csrf_hash()])->assertRedirectTo(site_url('login'));
        $this->assertNotSame(true, session('isLoggedIn'));
        $this->withSession([])->get('/tasks/new')->assertRedirectTo(site_url('login'));
    }

    public function testCreateUpdateAndSoftDelete(): void
    {
        $this->withSession(['isLoggedIn' => true, 'username' => 'demo']);
        $data = ['title' => 'My new task', 'description' => 'Useful details', 'task_date' => date('Y-m-d'), 'status' => 'pending'];
        $this->post('/tasks', $data + [csrf_token() => csrf_hash(), 'is_archived' => 1])->assertRedirectTo(site_url('tasks'));
        $task = (new TaskModel())->where('title', 'My new task')->first();
        $this->assertSame(0, (int) $task['is_archived']);
        $id = $task['id'];
        $this->get('/tasks/' . $id . '/edit')->assertSee('Useful details');
        $data['title'] = 'Updated task';
        $data['status'] = 'completed';
        $this->post('/tasks/' . $id, $data + [csrf_token() => csrf_hash()])->assertRedirectTo(site_url('tasks'));
        $this->assertSame('completed', (new TaskModel())->find($id)['status']);
        $this->get('/')->assertSee('Updated task');
        $count = (new TaskModel())->countAllResults();
        $this->post('/tasks/' . $id . '/delete', [csrf_token() => csrf_hash()])->assertRedirectTo(site_url('tasks'));
        $this->assertSame($count, (new TaskModel())->countAllResults());
        $this->assertSame(1, (int) (new TaskModel())->find($id)['is_archived']);
        foreach (['/', '/tasks'] as $path) { $this->get($path)->assertDontSee('Updated task'); }
    }

    public function testValidationDoesNotWriteInvalidData(): void
    {
        $this->withSession(['isLoggedIn' => true]);
        $count = (new TaskModel())->countAllResults();
        foreach ([['title' => '   ', 'task_date' => ''], ['title' => 'Valid title', 'task_date' => '2026-02-30'],
            ['title' => ['unexpected'], 'task_date' => '2026-10-05'], ['title' => str_repeat('a', 151), 'task_date' => '2026-10-05']] as $data) {
            $result = $this->post('/tasks', $data + ['description' => '', 'status' => 'pending', csrf_token() => csrf_hash()]);
            $result->assertStatus(422);
            $result->assertSee('Please correct the highlighted fields.');
        }
        $this->assertSame($count, (new TaskModel())->countAllResults());
    }

    public function testInvalidUpdatePreservesExistingTask(): void
    {
        $this->withSession(['isLoggedIn' => true]);
        $task = (new TaskModel())->first();
        $this->post('/tasks/' . $task['id'], ['title' => '', 'task_date' => '', 'status' => 'bad', csrf_token() => csrf_hash()])->assertStatus(422);
        $this->assertSame($task, (new TaskModel())->find($task['id']));
    }

    public function testArchivedTaskCannotBeEdited(): void
    {
        $this->withSession(['isLoggedIn' => true]);
        $task = (new TaskModel())->first();
        (new TaskModel())->update($task['id'], ['is_archived' => 1]);
        $this->expectException(CodeIgniter\Exceptions\PageNotFoundException::class);
        $this->get('/tasks/' . $task['id'] . '/edit');
    }

    public function testMissingTaskReturnsNotFound(): void
    {
        $this->withSession(['isLoggedIn' => true]);
        $this->expectException(CodeIgniter\Exceptions\PageNotFoundException::class);
        $this->post('/tasks/999999/delete', [csrf_token() => csrf_hash()]);
    }

    public function testCsrfRejectsMissingToken(): void
    {
        $this->withSession(['isLoggedIn' => true]);
        $this->expectException(CodeIgniter\Security\Exceptions\SecurityException::class);
        $this->post('/tasks', ['title' => 'Unsafe', 'task_date' => date('Y-m-d'), 'status' => 'pending']);
    }

    public function testGetCannotDelete(): void
    {
        $this->withSession(['isLoggedIn' => true]);
        $this->expectException(CodeIgniter\Exceptions\PageNotFoundException::class);
        $this->get('/tasks/1/delete');
    }
}



