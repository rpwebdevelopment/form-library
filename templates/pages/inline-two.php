<h1>Form Generation Inline Method Two</h1>
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
echo $form->open();
echo $form->text('first_name', 'first_name', 'First Name:', true);
echo $form->text('last_name', 'last_name', 'Last Name:', true);
echo $form->email('email_address', 'email_address', 'Email Address:', true);
echo $form->number('age', 'age', 'Age:', true, ['min' => '5', 'max' => '120']);
echo $form->select($genders, 'gender', 'gender', 'Gender:', true);
echo $form->textarea('message', 'message', 'Message:', 5);
echo $form->checkbox('newsletter_opt_in', 'newsletter_opt_in', 'Please check to opt-in to our Newsletter');
echo $form->radioGroup('service', 'service', $services, [], 'Services:');
echo $form->submit();
