<?php

namespace application\controllers;


interface Users
{
    function redirectIfNotLoggedIn();

    function redirectIfNoPermission();
}