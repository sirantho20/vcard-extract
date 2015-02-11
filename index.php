#!/usr/bin/php
<?php
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 2/11/15
 * Time: 1:27 PM
 */
$f = file('/var/vmail/vmail1/petroniacity.com/d/b/e/dbediako-2014.12.17.23.32.13/Maildir/.Emailed Contacts/cur/vcontact',FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
print_r(array_slice($f,5));