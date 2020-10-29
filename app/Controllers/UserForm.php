<?php
/**
 * Created by PhpStorm.
 * User: rporter
 * Date: 29/10/2020
 * Time: 09:45
 */

namespace FormLibrary\App\Controllers;

use FormLibrary\App\Abstracts\AbstractForm;
use FormLibrary\App\FormBuilder;

class UserForm extends AbstractForm
{

    public $html = '';
    
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
        $form = new FormBuilder();
        $form->text('first_name', 'first_name', 'First Name:', true);
        $form->text('last_name', 'last_name', 'Last Name:', true, []);
        $form->email('email_address', 'email_address', 'Email Address:', true);
        $form->number('age', 'age', 'Age:', true, ['min' => '5', 'max' => '120']);
        $form->select($this->genders, 'gender', 'gender', 'Gender:', true);
        $form->textarea('message', 'message', 'Message:', 5);
        $form->checkbox('newsletter_opt_in', 'newsletter_opt_in', 'Please check to opt-in to our Newsletter');
        $form->radioGroup('service', 'service', $this->services);
        $form->submit();
        $this->html = $form->write();
    }

    public function handle(array $request = [])
    {

    }
}
