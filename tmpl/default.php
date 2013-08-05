<?php
/**
 *
 *
 * @package   mod_formatic
 * copyright Lukman Hussein
 * @license GPL3
 */

// no direct access
defined('_JEXEC') or die;

require_once JPATH_ROOT . '/components/com_formatic/helpers/formatic.php';

$formid = $params->get('formid');


$doc = JFactory::getDocument();
$doc->addScript('http://code.jquery.com/jquery-1.9.1.js');
$doc->addScript('http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js');
$css = JUri::root() . 'modules/mod_formatic/tmpl/style/'.$style.'.css';
$doc->addStyleSheet($css);


$db=JFactory::getDBO();
$db->setQuery('SELECT * FROM `#__formatic` WHERE `id`='.$formid);

$forms = $db->loadObject();
?>


    <div class="formatic<?php echo $moduleclass_sfx ?>">
        <fieldset class="containatic">
        <?php   
            $parameters = json_decode($forms->parameters, true);
            $form = '<form ';
            foreach ($parameters as $name => $value) {

                if($name == 'post_entry'){
                    $rval = array(
                        'value'     => $value,
                        'format'    => 'input_hidden',
                        'name'      => $name
                    );
                    $redirect = @FormaticHelper::constructatic(0, $rval, false);
                } else {
                    $form .= " $name=\"$value\"";
                }
            }
            $form .= '>';
            $fields = json_decode($forms->fields, true);
            foreach ($fields as $key => $field) {
                $form .= @FormaticHelper::constructatic($key, $field, true);
            }
            if(isset($redirect)){
                $form .= $redirect;                
            }
            $form .= '</form>';
            echo $form;
            $target = ($parameters['id'] != '')? '#'.$parameters['id'] : '.'.$parameters['class'];
        ?>
        <script>
            $(document).ready(function() {
                $('<?php echo $target; ?>').validate({
                    submitHandler: function(form) {
                    $.ajax({
                        type: "POST",
                        url: $(form).prop('action'),
                        data: $(form).serialize(),
                        timeout: 3000,
                        async : false,
                        success: function() {
                            window.location.replace("<?php echo $parameters['post_entry']; ?>");
                        },
                        error: function() {
                            $('#status').html('Form has not been submitted. Please try again.')
                        }
                    });
                    return false;
                    }
                });
            }); 

            $.validator.addMethod("phone_us", function(phone_number, element) {  phone_number = phone_number.replace(/\s+/g, ""); 
            return this.optional(element) || phone_number.length > 9 && phone_number.match(/^(1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);}, "Please specify a valid phone number");
            
            $.validator.addMethod('phone_intl', function(value) { var numbers = value.split(/\d/).length - 1;
            return (10 <= numbers && numbers <= 20 && value.match(/^(\+){0,1}(\d|\s|\(|\)){10,20}$/)); }, 'Please enter a valid phone number');
            
            $.validator.addMethod("zip_code", function(zipcode, element) {
            return this.optional(element) || zipcode.match(/(^\d{5}(-\d{4})?$)|(^[ABCEGHJKLMNPRSTVXYabceghjklmnpstvxy]{1}\d{1}[A-Za-z]{1} ?\d{1}[A-Za-z]{1}\d{1})$/);
        }, "Please specify a valid postal/zip code");            
        </script>
        <div id="status"></div>
        </fieldset>

    </div>