<?php
namespace App\Controller;

class ContactController
{
    public function send()
    {
        require_once __DIR__.'/../../templates/contact.php';
    }
}