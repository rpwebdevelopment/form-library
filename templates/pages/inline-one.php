<h1>Form Generation Inline Method One</h1>
<p>
    <b>PLEASE NOTE:</b> No data handling has been added to this form,
    it is simply here for demo purposes
</p>
<?php
/**
 * Created by PhpStorm.
 * User: rporter
 * Date: 29/10/2020
 * Time: 12:46
 */
$genders = [
    '' => 'Please Select',
    'male' => 'Male',
    'female' => 'Female',
    'other' => 'Other'
];

$services = [
    'Development',
    'Design',
    'Optimisation',
    'Paid Search'
];

$form = new \FormLibrary\App\FormBuilder();
$form->text('first_name', 'first_name', 'First Name:', true);
$form->text('last_name', 'last_name', 'Last Name:', true);
$form->email('email_address', 'email_address', 'Email Address:', true);
$form->number('age', 'age', 'Age:', true, ['min' => '5', 'max' => '120']);
$form->select($genders, 'gender', 'gender', 'Gender:', true);
$form->textarea('message', 'message', 'Message:', 5);
$form->checkbox('newsletter_opt_in', 'newsletter_opt_in', 'Please check to opt-in to our Newsletter');
$form->radioGroup('service', 'service', $services, [], 'Services:');
$form->submit();
echo $form->write();
