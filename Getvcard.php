<?php
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 2/11/15
 * Time: 1:27 PM
 */
class Getvcard {
    public $path; // = '/var/vmail/vmail1/petroniacity.com/d/b/e/dbediako-2014.12.17.23.32.13/Maildir/.Emailed Contacts/cur/vcontact';
    public $pdo;
    public $vcard;
    public $content;
    public $db_host;
    public $db_user;
    public $db_pass;
    public $db_name;

    public function __construct($path)
    {
        $this->path = $path;
        $this->pdo = new PDO(sprintf("mysql:dbname=%s;host=%s",$this->db_name,$this->db_host),$this->db_user, $this->db_pass);
        $vc = $content = file($this->path, FILE_SKIP_EMPTY_LINES);
        $this->vcard = array_slice($vc,5);
        $this->content = array_slice(file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES),5);
        //print_r($this->content).PHP_EOL;
        //echo $this->getUserID();
    }

    public function getUserEmail(){

        $path_arr = explode('-',$this->path);
        $step1 = explode('/',substr($path_arr[0],18));

        $email = $step1[4].'@'.$step1[0];

        return $email;
    }

    public function getUserID()
    {
        $smt = $this->pdo->prepare("select user_id from users where username = :email");
        $smt->bindParam(':email', $this->getUserEmail());
        $smt->execute();
        $arr = $smt->fetch(PDO::FETCH_BOTH);

        return $arr[0];
    }

    public function insertContact(){

        $user_id = $this->getUserID();
        //$vcard = $this->vcard;

        //contact email
        $contact_email = substr($this->content[4],20);

        //Extract names
        $part = explode(':',$this->content[2]);
        $arr = explode(' ',$part[1]);
        $full_name = $part[1];
        if(count($arr) > 1)
        {
            $first_name = $arr[0];
            $last_name = $arr[1];
        }
        else
        {
            $first_name = '';
            $last_name = '';
        }

        // vcard column
        $vcolumn = '';
        foreach($this->content as $line)
        {
            $vcolumn .= $line.PHP_EOL;
        }

        $words = $contact_email.' '.$first_name.' '.$last_name;
        try {
        $command = $this->pdo->prepare("insert into collected_contacts values('',now(),0,
        :name,
        :email,
        :firstname,
        :surname,
        :vcard,
        :words,
        :userid
        )");

        $command->bindParam(':name', $full_name);
        $command->bindParam(':email', $contact_email);
        $command->bindParam(':firstname', $first_name);
        $command->bindParam(':surname', $last_name);
        $command->bindParam(':vcard', $vcolumn);
        $command->bindParam(':words', $words);
        $command->bindParam(':userid', $user_id);

        if($command->execute())
        {
            echo $contact_email.' added for user '.$user_id.PHP_EOL;
        }
            //echo $command->queryString;
        }
        catch(PDOException $e)
        {
            echo $e->getMessage().PHP_EOL;
        }
    }

}