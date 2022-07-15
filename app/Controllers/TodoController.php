<?php

namespace App\Controllers;

use App\Models\TodoItem;
use App\Paginator;
use App\Validator;
use Exception;

final class TodoController extends Controller
{
    /**
     * Render homepage
     * @return string|void
     */
    public function index()
    {
        return $this->renderTodosPage();
    }

    /**
     * Store todo item
     * @return string|void
     * @throws Exception
     */
    public function store()
    {
        $validationErrors = $this->validateBody();
        $taskCreated = false;

        if (empty($validationErrors)) {
            $this->insertTodoItem();
            $taskCreated = true;
        }

        return $this->renderTodosPage($validationErrors, $taskCreated);
    }

    /**
     * Update todo item
     * @return string|void
     * @throws Exception
     */
    public function update()
    {
        $updateData = [
            'id' => intval($this->body['id']),
            'content' => $this->body['content'],
            'status' => intval(isset($this->body['status']) && $this->body['status'] === 'true'),
        ];

        $this->updateTodoItem($updateData);

        return $this->renderTodosPage();
    }

    private function renderTodosPage(
        array $validationErrors = [],
        bool $taskCreated = false
    ) {
        $pager = new Paginator($this->query, $this->connection);
        $todoItems = $this->selectTodoItems($pager->offset);

        if (empty($validationErrors)) {
            $oldInput = [];
        } else {
            $oldInput = $this->body;
        }

        $isAdmin = $_SESSION['username'] === 'admin';

        return $this->render('todos', [
            'items' => $todoItems,
            'pager' => $pager,
            'query' => $this->query,
            'validationErrors' => $validationErrors,
            'oldInput' => $oldInput,
            'isAdmin' => $isAdmin,
            'taskCreated' => $taskCreated,
        ]);
    }

    private function insertTodoItem()
    {
        $stmt = $this->connection->prepare(
            'INSERT INTO todo_items(name, email, content) VALUES (?, ?, ?)'
        );

        if (!$stmt) {
            throw new Exception($this->connection->error);
        }

        $stmt->bind_param(
            'sss',
            $this->body['name'],
            $this->body['email'],
            $this->body['content']
        );

        $queryResult = $stmt->execute();

        if (!$queryResult) {
            throw new Exception($this->connection->error);
        }
    }

    private function updateTodoItem(array $data)
    {
        $stmt = $this->connection->prepare("
            UPDATE todo_items SET content=?, status=? WHERE id=?
        ");

        if (!$stmt) {
            throw new Exception($this->connection->error);
        }

        $stmt->bind_param(
            'sii',
            $data['content'],
            $data['status'],
            $data['id']
        );

        $queryResult = $stmt->execute();

        if (!$queryResult) {
            throw new Exception($this->connection->error);
        }
    }

    private function selectTodoItems(int $offset): array
    {
        $limit = Paginator::LIMIT;

        $orderBy = $this->query->hasKey('sortby')
            ? $this->query->getKey('sortby')
            : 'created_at';

        $sort = $this->query->hasKey('sort')
            ? $this->query->getKey('sort') === 'desc' ? 'DESC' : 'ASC'
            : 'ASC';

        $result = $this->connection->query("
            SELECT *
            FROM todo_items
            ORDER BY `$orderBy` $sort
            LIMIT $limit
            OFFSET $offset
        ");

        if (!$result) {
            throw new Exception($this->connection->error);
        }

        $todoItems = [];

        foreach ($result as $item) {
            $todoItems[] = new TodoItem($item);
        }

        return $todoItems;
    }

    private function validateBody()
    {
        $nameValidator = new Validator($this->body['name'], [
            'required',
            'isString',
            'min:2',
            'max:255',
        ]);

        $emailValidator = new Validator($this->body['email'], [
            'required',
            'isString',
            'email',
        ]);

        $contentValidator = new Validator($this->body['content'], [
            'required',
            'isString',
            'min:3'
        ]);

        return Validator::validateAll([
            'name' => $nameValidator,
            'email' => $emailValidator,
            'content' => $contentValidator,
        ]);
    }
}
