<?php
namespace Modulo\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
 
//Componentes de validación
use Zend\Validator;
use Zend\I18n\Validator as I18nValidator;
 
//Adaptador de la db
use Zend\Db\Adapter\Adapter;
 
//Componente para cifrar contraseñas
use Zend\Crypt\Password\Bcrypt;
 
//Incluir modelos
use Modulo\Model\Entity\UsuariosModel;
 
//Incluir formularios
use Modulo\Form\AddUsuario;
 
class CrudController extends AbstractActionController{
    private $dbAdapter;
     
    public function __construct(){
    }
     
    public function indexAction(){
        /*Siempre que necesitemos trabajar con el modelo hay que hacer esto
          solamente le estamos pasando al modelo la conexión */
        $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
        $usuarios=new UsuariosModel($this->dbAdapter);
     
        $lista=$usuarios->getUsuarios();
 
        return new ViewModel(
                array(
                    "lista"=>$lista
                ));
    }
     
    public function addAction(){
        $form=new AddUsuario("form");
        $vista=array("form"=>$form);
        if($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if($form->isValid()){
                //Cargamos el modelo
                $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
                $usuarios=new UsuariosModel($this->dbAdapter);
                 
                //Recogemos los datos del formulario
                $email=$this->request->getPost("email");
                 
                /*
                Ciframos la contraseña 
                para la maxima seguridad le aplicamos un salt
                y hacemos el hash del hash 5 veces 
                (por defecto vienen mas de 10 pero es mas lento)
                */
                $bcrypt = new Bcrypt(array(
                                'salt' => 'aleatorio_salt_pruebas_victor',
                                'cost' => 5));
                $securePass = $bcrypt->create($this->request->getPost("password"));
                 
                $password=$securePass;
                $nombre=$this->request->getPost("nombre");
                $apellido=$this->request->getPost("apellido");
                 
                //Insertamos en la bd
                $insert=$usuarios->addUsuario($email, $password, $nombre, $apellido);
                 
                //Mensajes flash $this->flashMessenger()->addMenssage("mensaje");
                if($insert==true){
                    $this->flashMessenger()->setNamespace("add_correcto")->addMessage("Usuario añadido correctamente");
                    return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/crud/');
                }else{
                    $this->flashMessenger()->setNamespace("duplicado")->addMessage("Usuario duplicado mete otro");
                    return $this->redirect()->refresh();
                }
            }else{
                $err=$form->getMessages();
                $vista=array("form"=>$form,'url'=>$this->getRequest()->getBaseUrl(),"error"=>$err);
            }
        }
        return new ViewModel($vista); 
    }
     
    public function verAction(){
         $id=$this->params()->fromRoute("id",null);
         $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
         $usuarios=new UsuariosModel($this->dbAdapter);
          
         $usuario=$usuarios->getUnUsuario($id);
         if($usuario){
             return new ViewModel(
                 array(
                    "id"      => $id,
                    "usuario" => $usuario
                )); 
         }else{
             return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/crud/');
         } 
    }
     
    public function modificarAction(){
        $id=$this->params()->fromRoute("id",null);
        $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
 
        $usuarios=new UsuariosModel($this->dbAdapter);         
        $usuario=$usuarios->getUnUsuario($id);
 
        $form=new AddUsuario("form");
        $form->setData($usuario);
         
        $vista=array("form"=>$form);
        if($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            // if($form->isValid()){
                //Recogemos los datos del formulario
                $email=$this->request->getPost("email");
                $bcrypt = new Bcrypt(array(
                                'salt' => 'aleatorio_salt_pruebas_victor',
                                'cost' => 5));
                $securePass = $bcrypt->create($this->request->getPost("password"));
                 
                $password=$securePass;
                $nombre=$this->request->getPost("nombre");
                $apellido=$this->request->getPost("apellido");
                 
                //Insertamos en la bd
                $update=$usuarios->updateUsuario($id,$email, $password, $nombre, $apellido);
                if($update==true){
                    $this->flashMessenger()->setNamespace("add_correcto")->addMessage("Usuario modificado correctamente");
                    return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/crud/');
                }else{
                    $this->flashMessenger()->setNamespace("duplicado")->addMessage("El usuario se ha modificado");
                    return $this->redirect()->refresh();
                }
            // }else{
            //     $err=$form->getMessages();
            //     $vista=array("form"=>$form,'url'=>$this->getRequest()->getBaseUrl(),"error"=>$err);
            // }
        }





         return new ViewModel(array(
                    "id"      => $id,
                    "usuario" => $usuario
                )); 
    }




     
    public function eliminarAction(){
        $id=$this->params()->fromRoute("id",null);
        $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
        $usuarios=new UsuariosModel($this->dbAdapter);
        $delete=$usuarios->deleteUsuario($id);
          
       return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/crud/');
    }
    // public function eliminarAction(){
    //     $id=$this->params()->fromRoute("id",null);
    //     $this->dbAdapter=$this->getServiceLocator()->get('Zend\Db\Adapter');
    //     $usuarios=new UsuariosModel($this->dbAdapter);
    //     $delete=$usuarios->deleteUsuario($id);
    //     if($delete==true){
    //         $this->flashMessenger()->setNamespace("eliminado")->addMessage("Usuario eliminado correctamente");
    //     }else{
    //         $this->flashMessenger()->setNamespace("eliminado")->addMessage("El usuario no a podido ser eliminado");
    //     }   
    //    return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/crud/');
    // }

     
}