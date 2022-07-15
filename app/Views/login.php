<?php
/** @var array<string, string> $validationErrors */
/** @var array<string, string> $oldInput */
?>

<div class="row mb-3">
    <h1>Login</h1>
</div>

<div class="row">
    <form id="login-form" method="POST" action="/login">
        <div class="mb-3">
            <label class="form-label" for="username-field">Login name</label>
            <input class="form-control" type="text" id="username-field" name="username" value="<?= $oldInput['username'] ?>" />

            <?php if (isset($validationErrors['username'])) { ?>
                <div class="form-text text-danger">
                    <?= $validationErrors['username'] ?>
                </div>
            <?php } ?>
        </div>

        <div class="mb-3">
            <label class="form-label" for="password-field">Password</label>
            <input class="form-control" type="password" id="password-field" name="password" />

            <?php if (isset($validationErrors['password'])) { ?>
                <div class="form-text text-danger">
                    <?= $validationErrors['password'] ?>
                </div>
            <?php } ?>
        </div>

        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>
