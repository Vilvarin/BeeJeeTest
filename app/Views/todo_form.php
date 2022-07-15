<?php
/** @var array $oldInput */
/** @var array $validationErrors */
/** @var \App\PageQuery $query */
?>

<?php $formClass = empty($validationErrors) ? 'd-none' : ''; ?>

<form id="add-task-form"
      action="/<?= $query->getQueryString() ?>"
      method="POST"
      class="container <?= $formClass ?>"
>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label" for="name-field">Name</label>
            <input id="name-field" class="form-control" type="text" name="name" value="<?= $oldInput['name'] ?>">

            <?php if (isset($validationErrors['name'])) { ?>
                <div class="text-danger form-text">
                    <?= $validationErrors['name'] ?>
                </div>
            <?php } ?>
        </div>

        <div class="col">
            <label class="form-label" for="email-field">Email</label>
            <input id="email-field" class="form-control" type="email" name="email" value="<?= $oldInput['email'] ?>">

            <?php if (isset($validationErrors['email'])) { ?>
                <div class="text-danger form-texts">
                    <?= $validationErrors['email'] ?>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label" for="content-field">Message</label>

            <textarea id="content-field" class="form-control" name="content"><?= $oldInput['content'] ?></textarea>

            <?php if (isset($validationErrors['content'])) { ?>
                <div class="text-danger form-text">
                    <?= $validationErrors['content'] ?>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
</form>

<?php $btnClass = empty($validationErrors) ? '' : 'd-none'; ?>
<button type="button" class="add-task <?= $btnClass ?> btn btn-primary">
    Add task
</button>
