<?php
/** @var \App\Models\TodoItem[] $items */
/** @var \App\PageQuery $query */
/** @var \App\Paginator $pager */
/** @var bool $isAdmin */
?>

<?php if (count($items)) { ?>
    <table id="todo-table" class="table">
        <thead>
        <tr>
            <th>
                <a href="/<?= $query->getSortQueryString('name') ?>">
                    Name <?= $query->getSortDir('name') === 'asc' ? '&uarr;' : '&darr;' ?>
                </a>
            </th>

            <th>
                <a href="/<?= $query->getSortQueryString('email') ?>">
                    Email <?= $query->getSortDir('email') === 'asc' ? '&uarr;' : '&darr;' ?>
                </a>
            </th>

            <th>Message</th>

            <th>
                <a href="/<?= $query->getSortQueryString('status') ?>">
                    Status <?= $query->getSortDir('status') === 'asc' ? '&uarr;' : '&darr;' ?>
                </a>
            </th>

            <th>
                <a href="/<?= $query->getResetSortQueryString() ?>">
                    Reset filter
                </a>
            </th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($items as $item) { ?>
            <tr>
                <td><?= $item->name ?></td>
                <td><?= $item->email ?></td>

                <td>
                    <?php if ($isAdmin) { ?>
                        <textarea form="<?= $item->id ?>-edit-form" name="content"><?= $item->content ?></textarea>
                    <?php } else { ?>
                        <div><?= $item->content ?></div>
                    <?php } ?>

                    <?php if ($item->isEdited()) { ?>
                        <div class="admin-edited-mark text-muted">Edited by admin</div>
                    <?php } ?>
                </td>

                <td>
                    <?php if ($isAdmin) { ?>
                        <input type="checkbox"
                               name="status"
                               value="true"
                               form="<?= $item->id ?>-edit-form"
                               <?= $item->status ? 'checked="checked"' : '' ?>
                        />
                    <?php } else { ?>
                        <?= $item->status ? '&check;' : '&cross;' ?>
                    <?php } ?>
                </td>

                <td>
                    <?php if ($isAdmin) { ?>
                        <form id="<?= $item->id ?>-edit-form"
                              name="<?= $item->id ?>-edit-form"
                              method="POST"
                              action="/update"
                        >
                            <input type="hidden" name="id" value="<?= $item->id ?>" />
                            <button type="submit">Сохранить</button>
                        </form>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php if ($pager->hasPrevPage()) { ?>
            <div class="page-item">
                <a class="page-link" href="/<?= $query->getPrevPageQueryString($pager) ?>">&laquo;</a>
            </div>
        <?php } ?>

        <div class="page-item">
            <div class="page-link">Page: <?= $pager->currPage ?></div>
        </div>

        <?php if ($pager->hasNextPage()) { ?>
            <div class="page-item">
                <a class="page-link" href="/<?= $query->getNextPageQueryString($pager) ?>">&raquo;</a>
            </div>
        <?php } ?>
    </div>
<?php } else { ?>
    <div>List is empty</div>
<?php } ?>


