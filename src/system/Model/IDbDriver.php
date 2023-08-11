<?php

namespace App\system\Model;

interface IDbDriver
{
    public function setConnection($host, $user, $pass, $dbname);
    public function query($sql);
    public function getInsDb();
}
