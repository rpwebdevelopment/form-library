<?php
/**
 * Created by PhpStorm.
 * User: rporter
 * Date: 30/10/2020
 * Time: 09:26
 */

namespace FileLibrary\Src;


class FormSubmissions extends Model
{

    protected $table = 'form_submissions';

    public $schema = [
        'first_name' => 'string',
        'last_name' => 'string',
        'email_address' => 'string',
        'gender' => 'enum:male,female,other',
        'age' => 'int',
        'message' => 'string',
        'newsletter_opt_in' => 'int',
        'service' => 'enum:design,development,optimisation,paid search'
    ];

    public function __construct()
    {
        parent::__construct();
    }

}
