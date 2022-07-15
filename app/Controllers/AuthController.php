<?php

namespace App\Controllers;

use App\Validator;
use Exception;

final class AuthController extends Controller
{
    /**
     * Render login page
     * @return string|void
     * @throws Exception
     */
    public function index() {
        return $this->render('login', [
            'validationErrors' => [],
        ]);
    }

    /**
     * Check user requirements and login it
     * @return string|void
     * @throws Exception
     */
    public function login() {
        $userValidator = new Validator($this->body['username'], ['required', 'admin']);
        $passValidator = new Validator($this->body['password'], ['required', 'password']);

        $validationErrors = Validator::validateAll([
            'username' => $userValidator,
            'password' => $passValidator,
        ]);

        $failedRules = [
            'username' => $userValidator->getFailedRule(),
            'password' => $passValidator->getFailedRule(),
        ];

        if (empty($validationErrors)) {
            $_SESSION['username'] = 'admin';
            return $this->redirect('/');
        } else {
            if ($failedRules['username'] === 'required' && $failedRules['password'] !== 'required') {
                unset($validationErrors['password']);
            } elseif ($failedRules['password'] === 'required' && $failedRules['username'] !== 'required') {
                unset($validationErrors['username']);
            } elseif ($failedRules['username'] === 'required' && $failedRules['password'] === 'required') {
                // do nothing
            } else {
                $validationErrors['username'] = $this->invalidMessage();
                $validationErrors['password'] = $this->invalidMessage();
            }

            $oldInput = ['username' => $this->body['username']];

            return $this->render('login', [
                'validationErrors' => $validationErrors,
                'oldInput' => $oldInput,
            ]);
        }
    }

    /**
     * Logout current user
     * @return void
     */
    public function logout()
    {
        unset($_SESSION['username']);
        session_destroy();
        return $this->redirect('/');
    }

    private function invalidMessage(): string
    {
        return 'Incorrect username or password';
    }
}
