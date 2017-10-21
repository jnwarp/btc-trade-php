<?php

class Login
{
    public $accountId;
    public $accountInfo;
    public $loggedId;

    public function __construct($close_session = true)
    {
        // resume session if one exists
        if (isset($_COOKIE["PHPSESSID"])) session_start();

        $this->loadLoginInfo();

        // automatically close session so other scripts can run
        if ($close_session) session_write_close();
    }

    public function createLoginSession($email, $password)
    {
        $accounts = new Accounts();
        $account_id = $accounts->validateAccount($email, $password);

        // account is not valid, end function
        if ($account_id === false) return false;

        // create a new session and store a cookie
        if (!isset($_COOKIE["PHPSESSID"])) session_start();

        // save the account id in the session info
        $_SESSION['account_id'] = $account_id;

        return true;
    }

    public function destroyLoginSession()
    {
        session_destroy();
    }

    public function loadLoginInfo()
    {
        // remember the account id from session
        $this->accountId = (isset($_SESSION['account_id'])) ?
            $_SESSION['account_id'] : 0;

        // retrieve account information
        $accounts = new Accounts();
        $this->accountInfo = $accounts->getAccountInfo($this->accountId);

        // check if user should still be logged in
        if ($this->accountId > 0 && $this->accountInfo !== false) {
            $this->loggedIn = true;
        } else {
            $this->loggedIn = false;
        }
    }

    public function requireLoggedIn($logged_in = true, $redirect = false)
    {
        if ($this->loggedIn != $logged_in) {
            // set the response header
            if ($redirect === true) {
                header("Location: /");
            } elseif($redirect === false) {
                header("HTTP/1.1 500 Internal Server Error");
            } else {
                header("Location: /$redirect");
            }

            // end the connection
            die();
        }
    }
}