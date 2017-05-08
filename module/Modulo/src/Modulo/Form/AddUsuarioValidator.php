<?php
namespace Modulo\Form;
 
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator;
use Zend\I18n\Validator as I18nValidator;
 
class AddUsuarioValidator extends InputFilter{
   
    public function __construct(){
         
        $this->add(array(
            'name' => 'email',
            'required' => true,
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name'=>'EmailAddress',
                    'options'=> array(
                        'messages' => array(
                        \Zend\Validator\EmailAddress::INVALID_HOSTNAME=>'Email incorrecto',
                        ),
                    ),
                ),
        )));
         
         
        $this->add(array(
            'name' => 'nombre',
            'required' => true,
            'filters' => array(
                array('name' => 'HtmlEntities'),
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                 array( 
                    'name' => 'Alpha',
                     'options' => array(
                        'allowWhiteSpace'=>false,
                        'messages' => array(
                            I18nValidator\Alpha::INVALID=>'El nombre solo puede estar formado por letras',
                            I18nValidator\Alpha::NOT_ALPHA=>'El nombre solo puede estar formado por letras',
                            I18nValidator\Alpha::STRING_EMPTY=>'El nombre no puede estar vacio',
                            //I18nValidator\Alpha::NOT_ALNUM=>'Tu nombre esta mal',
                        ),
                     ),
                 ),
        )));
         
        $this->add(array(
            'name' => 'apellido',
            'required' => true,
            'filters' => array(
                array('name' => 'HtmlEntities'),
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                 array( 
                    'name' => 'Alpha',
                     'options' => array(
                        'allowWhiteSpace'=>true,
                        'messages' => array(
                            I18nValidator\Alpha::INVALID=>'Los apellido solo pueden estar formado por letras',
                            I18nValidator\Alpha::NOT_ALPHA=>'Los apellido solo pueden estar formado por letras',
                            I18nValidator\Alpha::STRING_EMPTY=>'Los apellido no pueden estar vacios',
                            //I18nValidator\Alpha::NOT_ALNUM=>'Tu nombre esta mal',
                        ),
                     ),
                 ),
        )));
  
        $this->add(array(
            'name' => 'password',
            'required' => true,
            'filters' => array(
                array('name' => 'HtmlEntities'),
                array('name' => 'StringTrim'),
            )
        ));
  
         
        }
    
}