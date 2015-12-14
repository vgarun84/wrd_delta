<?php
/**
 * Created by PhpStorm.
 * User: Arun N
 * Date: 12/9/2015
 * Time: 7:49 PM
 */

require __DIR__ . '/config/bootstrap.php';


use Forms\FormCreator as FormCreator;
use Forms\FormValidation as FormValidation;

$str_message_error = "";
$a_posted_data = array();

if ( !empty($_POST['save_frm1']))
{
    $form_validation = new FormValidation();
    $a_posted_data = $_POST;
    extract($_POST);

    $config = array(
        array(
            'field'   => 'full_name',
            'label'   => 'Full Name',
            'rules'   => array('required', 'min_length[5]', 'max_length[12]'),
            'errors'  => array('Full Name should not be empty', 'Full name should be minmux of 5 len', 'Full name max len is 12')
        ),
        array(
            'field'   => 'phone',
            'label'   => 'Phone',
            'rules'   => array('required', 'numeric', 'exact_length[11]'),
            'errors'  => array('Phone number should not be empty', 'Phone number should be numeric', 'Phone number is exact 11 digit')
        ),
        array(
            'field'   => 'email',
            'label'   => 'Email',
            'rules'   => array('required', 'valid_email'),
            'errors'  => array('Email should not be empty', 'Please enter valid email address')
        ),
        array(
            'field'   => 'url',
            'label'   => 'Web Site URL',
            'rules'   => array('required', 'valid_url'),
            'errors'  => array('Web Site URL should not be empty', 'Web Site URL should be in valid format')
        ),
        array(
            'field'   => 'user_password',
            'label'   => 'Password',
            'rules'   => array('required', 'min_length[5]'),
            'errors'  => array('Password should not be empty', 'Password should be minimum 5 length')
        )
    );

    $form_validation->set_rules($config);

    if ($form_validation->run() == FALSE)
    {
        $str_message_error = $form_validation->error_string('<li style="margin-left: 25px">', '</li>');
    }
    else
    {
    }
}

$str_form = FormCreator::getInstance()
    ->form_fieldset('Form1')
    ->form_open('index.php', array(
        'method' => 'POST',
        'name'   => 'form1',
        'id'     => 'id_form1',
        'class'  => 'form-horizontal'
    ))
    ->form_label("Full Name" , '', array(
        'class'  => 'control-label'
    ))
    ->form_input(array(
        'name'        => 'full_name',
        'id'          => 'full_name',
        'class'       => 'form-control',
        'placeholder' => 'Full Name',
        'maxlength'   => '13',
        'minlength'   => '5',
        'value'       => @$full_name
    ))
    ->form_label("Phone (is Number)")
    ->form_input(array(
        'type'        => 'text',
        'name'        => 'phone',
        'id'          => 'phone',
        'class'       => 'form-control',
        'placeholder' => 'Phone',
        'maxlength'   => '11',
        'value'       => @$phone
    ))
    ->form_label("Email")
    ->form_input(array(
        'type'        => 'text',
        'name'        => 'email',
        'id'          => 'email',
        'class'       => 'form-control',
        'placeholder' => 'Email',
        'maxlength'   => '80',
        'value'       => @$email
    ))
    ->form_label("Web Site URL")
    ->form_input(array(
        'type'        => 'text',
        'name'        => 'url',
        'id'          => 'url',
        'class'       => 'form-control',
        'placeholder' => 'Phone',
        'maxlength'   => '100',
        'value'       => @$url
    ))
    ->form_label("Password")
    ->form_password(array(
        'name'        => 'user_password',
        'id'          => 'user_password',
        'placeholder' => 'Password',
        'class'       => 'form-control',
        'minlength'   => '3',
        'maxlength'   => '10',
        'value'       => ''
    ))
    ->form_label("Gender")
    ->form_dropdown(
        array
        (
            'name'  => 'gender',
            'id'    => 'gender',
            'class' => 'form-control',
        ),
        array
        (
            ''      => 'Select',
            'male'  => 'Male',
            'female'=> 'Female'
        )
    )
    ->form_label("You Are from US", '', array
        (
            'class'  => 'control-label',
            'style'  => 'margin-right:20px'
        )
    )
    ->form_radio(
        array
        (
            'name'    => 'you_are_from',
            'id'      => 'you_are_from_yes',
            'class'   => 'radio-inline',
            'style'   => 'margin-right:5px;clear: both'
        ),
        'yes',
        TRUE,
        '',
        '<b>Yes</b>'
    )
    ->form_radio(
        array
        (
            'name'    => 'you_are_from',
            'id'      => 'you_are_from_no',
            'class'   => 'radio-inline',
            'style'  => 'margin-right:5px;clear: both'
        ),
        'no',
        false,
        '',
        '<b  style="margin-right:5px;clear: both">No</b>'
    )
    ->form_label('')
    ->form_label('Comment', '', array
        (
            'class'  => 'control-label',
            'style'  => 'clear:both'
        )
    )
    ->form_textarea(array(
        'id'    => 'comment',
        'name'  => 'comment',
        'class' => 'form-control',
        'rows'  => '5',
        'value' => @$comment
    ))
    ->form_label("Accept New Letter")
    ->form_checkbox(array(
        'name'    => 'newsletter',
        'id'      => 'newsletter',
        'value'   => 'accept',
        'class'   => 'checkbox',
        'checked' => TRUE
    ))
    ->form_submit(array('name' => 'save_frm1', 'id' => 'save_frm1' , 'class' => 'btn btn-primary'), 'Submit')
    ->form_close()
    ->form_fieldset_close()
    ->return_form();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Form Page1</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
    <h1>User Form</h1>
    <p>This form was gnerator using FormCreator Base class created</p>

    <?php if (!empty($str_message_error)):?>
    <ul class="alert alert-danger">
          <?php echo $str_message_error;?>
    </ul>
    <?php endif;?>

    <?php if(!empty($a_posted_data)):?>
    <ul>
        <pre>
            <?php print_r($a_posted_data);?>
        </pre>
    </ul>
   <?php endif;?>

    <?php
      echo $str_form;
    ?>
</div>
</body>
</html>

