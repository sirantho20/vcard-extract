#!/usr/bin/php
<?php
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 2/11/15
 * Time: 4:38 PM
 */
require_once 'Getvcard.php';
if(!$file = @file('vcards.txt', FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES))
{
    echo 'You must first execute the file getpath.sh'.PHP_EOL;
}
else
{


foreach($file as $path)
{
    $cur = substr($path,0,-12);
    if(is_file(trim($cur)))
    {
        //$obj = (new Getvcard($cur))->insertContact();
        if(unlink($cur))
        {
            echo 'deleted '.$cur.PHP_EOL;
        }
    }
    else
    {
        echo 'file not found'.PHP_EOL;
    }
}

}