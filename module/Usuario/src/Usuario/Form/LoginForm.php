<?php
namespace Usuario\Form;
 
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Form\Factory;
 
class LoginForm extends Form
{
    public function __construct($name = null)
     {
        parent::__construct($name);
		   
        //Definimos los filtros del formulario, instanciamos el objeto de validaciones
      //  $this->setInputFilter(new \Usuario\Form\Validator());
         
      // $this->setInputFilter(new \Modulo\Form\AddUsuarioValidator());
         
       $this->setAttributes(array(
            //'action' => $this->url.'/modulo/recibirformulario',
            'action'=>"",
            'method' => 'post'
        ));
         
        $this->add(array(
            'name' => 'username',
            'attributes' => array(
                'type' => 'text',
				'placeholder'=>'Usuario',
                'class' => 'input form-control',
                'required'=>'required',
				'style'=>'text-transform: uppercase',
            ),
			
			
        ));
         
         $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type' => 'password',
				'placeholder'=>'Contraseña',
                'class' => 'input form-control',
                'required'=>'required',
				'style'=>'text-transform: uppercase',
            )
        ));
          
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(     
                'type' => 'submit',
                'value' => 'Entrar',
                'title' => 'Entrar',
                'class' => 'btn btn-success'
            ),
        ));
  
     }
}
 
?>