<?php
/**
 * Created by PhpStorm.
 * User: rporter
 * Date: 29/10/2020
 * Time: 09:45
 */

namespace FormLibrary\Src\Controllers;

use FileLibrary\Src\FormValidator;
use FormLibrary\Src\Abstracts\AbstractForm;
use FormLibrary\Src\FormBuilder;

class UserForm extends AbstractForm
{
    public $template = 'home';
    
    public $rules = [
        'first_name' => [
            'required|min:1|max:120|string'
        ],
        'last_name' => [
            'required|min:1|max:120|string'
        ],
        'email_address' => [
            'required|min:1|max:120|email'
        ],
        'age' => [
            'required|int|above:4|below:121'
        ],
        'gender' => [
            'required|arr:male,female,other'
        ],
        'message' => [
            'string'
        ],
        'newsletter_opt_in' => [
            'checkbox:int'
        ],
        'service' => [
            'arr:development,design,optimisation,paid_search'
        ]
    ];

    public $request = [];
    
    public $errors = [];

    public $genders = [
        '' => 'Please Select',
        'male' => 'Male',
        'female' => 'Female',
        'other' => 'Other'
    ];
    
    public $services = [
        'Development',
        'Design',
        'Optimisation',
        'Paid Search'
    ];

    public function build()
    {
        parent::__construct();

        $form = new FormBuilder($this->errors['form']);
        $er = $this->errors;
        $rq = $this->request;

        $form->text($er['first_name'], $rq['first_name'], 'first_name', 'first_name', 'First Name:', true);
        $form->text($er['last_name'], $rq['last_name'], 'last_name', 'last_name', 'Last Name:', true);
        $form->email($er['email_address'], $rq['email_address'], 'email_address', 'email_address', 'Email Address:', true);
        $form->number($er['age'], $rq['age'], 'age', 'age', 'Age:', true, ['min' => '5', 'max' => '120']);
        $form->select($er['gender'], $rq['gender'], $this->genders, 'gender', 'gender', 'Gender:', true);
        $form->textarea($er['message'], $rq['message'], 'message', 'message', 'Message:', 5);
        $form->checkbox($er['newsletter_opt_in'], $rq['newsletter_opt_in'], 'newsletter_opt_in', 'newsletter_opt_in', 'Please check to opt-in to our Newsletter');
        $form->radioGroup($er['service'], $rq['service'], 'service', 'service', $this->services, 'Services:');
        $form->submit();
        $this->html = $form->write();
    }

    public function handle(array $request = [])
    {
        $validator = new FormValidator($this->rules);
        $validator->validate($request);
        if ($validator->successful) {
            $this->html = $validator->request;
            $this->template = 'success';
        } else {
            $this->request = $request;
            $this->errors = $validator->errors;
            $this->build();
        }
    }
}
