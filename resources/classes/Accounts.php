<?php

class Accounts
{
    public $table;

    public function __construct()
    {

    }

    public function checkEmail($email, $query_db = true)
    {
        // check if the email given is valid
        if (!preg_match("/^([\w\.])+(@)+([\w])+(\.)+([\w])*$/", $email)) {
            return false;
        } elseif ($query_db = false) {
            return true;
        }

        // check if there is an account with the same email
        $connect = new Connect();
        $account_exists = count($connect->simpleSelect(
            'accounts',
            'email',
            $email,
            'account_id'
        ));
        $connect->close();

        return (!$account_exists);
    }

    public function checkPassword($password)
    {
        // password cannot be shorter than 8 characters
        if (strlen($password) < 8) return false;

        // check if password meets complexity requirements
        $complexity = 0;
        if (preg_match('/[0-9]/', $password)) $complexity++; // number
        if (preg_match('/[a-z]/', $password)) $complexity++; // lower case
        if (preg_match('/[A-Z]/', $password)) $complexity++; // upper case
        if (preg_match('/([\W])|(_)/', $password)) $complexity++; // special

        // must match any 3 of 4 complexity requirements
        return ($complexity > 2);
    }

    public function createAccount($email, $password)
    {
        // password must meet complexity requirements
        if (!$this->checkPassword($password)) return false;

        // email must meet validity checks
        if (!$this->checkEmail($email)) return false;

        // mitigate database breach by hashing password
        $password = password_hash($password, PASSWORD_DEFAULT);

        // add the new row to the database
        $connect = new Connect();
        $connect->simpleInsert(
            'accounts',
            [
                'email' => $email,
                'password' => $password,
                'account_enabled' => 1
            ]
        );
        $connect->close();

        return true;
    }

    public function deleteAccount($id)
    {
        $connect = new Connect();
        $connect->simpleDelete(
            'accounts',
            'account_id',
            $id
        );
        $connect->close();
    }

    public function editAccount($id, $email, $password)
    {
        // password must meet complexity requirements
        if ($password != '' && !$this->checkPassword($password)) return false;

        // email must meet validity checks
        if (!$this->checkEmail($email)) return false;

        $connect = new Connect();

        // update the email
        if ($email !== '') {
            $connect->simpleUpdate(
                'accounts',
                'email',
                $email,
                'account_id',
                $id
            );
        }

        // update the password
        if ($password !== '') {
            // mitigate database breach by hashing password
            $password = password_hash($password, PASSWORD_DEFAULT);

            $connect->simpleUpdate(
                'accounts',
                'password',
                $password,
                'account_id',
                $id
            );
        }

        $connect->close();

        return true;
    }

    public function getAccountInfo($id)
    {
        // get the account information
        $connect = new Connect();
        $account_info = $connect->simpleSelect('accounts', 'account_id', $id);
        $connect->close();

        // there must be login info available
        if ($account_info === []) return false;

        // remove password to avoid leak
        $account_info['password'] = '';

        return $account_info;
    }

    public function validateAccount($email, $password)
    {
        // get the account information
        $connect = new Connect();
        $account_info = $connect->simpleSelect('accounts', 'email', $email);
        $connect->close();

        // there must be login info available
        if ($account_info === []) return false;

        // email and password must match
        if ($account_info['email'] != $email) return false;

        // verify password if set
        if ($password !== false) {
            if ($account_info['password'] == '') return false;
            if (!password_verify($password, $account_info['password'])) {
                return false;
            }
        }

        return ($account_info['id']);
    }
}