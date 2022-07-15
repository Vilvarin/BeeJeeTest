<?php
/** @var \App\Models\TodoItem $items */
/** @var \App\PageQuery $query */
/** @var \App\Paginator $pager */
/** @var array $oldInput */
/** @var array $validationErrors */
/** @var bool $isAdmin */
/** @var bool $taskCreated */

$tableView = new \App\View('todo_table');
$formView = new \App\View('todo_form');
?>

<div class="row mb-3">
    <div class="col">
        <h1>Todos</h1>
    </div>

    <div class="col">
        <?php if ($isAdmin) { ?>
            <a class="float-end btn btn-secondary" href="/logout">Logout</a>
        <?php } else { ?>
            <a class="float-end btn btn-secondary" href="/login">Login</a>
        <?php } ?>
    </div>
</div>

<div class="row mb-3">
    <?= $tableView->render([
        'items' => $items,
        'pager' => $pager,
        'query' => $query,
        'isAdmin' => $isAdmin,
    ]); ?>
</div>

<?php if ($taskCreated) { ?>
    <div class="row">
        <div class="text-success">
            Task was successfully created
        </div>
    </div>
<?php } ?>

<div class="row">
    <?= $formView->render([
        'oldInput' => $oldInput,
        'validationErrors' => $validationErrors,
        'query' => $query,
    ]); ?>
</div>
