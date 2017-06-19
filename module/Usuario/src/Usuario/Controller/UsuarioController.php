<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Usuario\Controller;

require "vendor/autoload.php";

use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Db\Adapter\Adapter;
use Anio\Model\Entity\Anio;
use Zend\MVC\Exception;
use Zend\Validator;
use Zend\I18n\Validator as I18nValidator;
use Zend\Crypt\Password\Bcrypt;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Usuario\Form\LoginForm;
use Usuario\Model\Entity\Usuario;

class UsuarioController extends AbstractActionController {

	private $auth;
	public function __construct() {
		$this->auth = new AuthenticationService();
	}

	public function indexAction() {
		return new ViewModel();
	}

	public function loginAction() {
		
		$auth = $this->auth;
		$identi = $auth->getStorage()->read();
		if ($identi != false && $identi != null) {
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() );
		}
		$form = new LoginForm("form");
		$request = $this->getRequest();
		if ($this->getRequest()->isPost()) {
			$user = $request->getPost('username');
			$pass = $request->getPost('password');
			$bcrypt = new Bcrypt();
//echo $securePass = $bcrypt->create($pass);
//
//$securePass = $securePass;
//$password = 'admin';//pass arriba;
//
//if ($bcrypt->verify($password, $securePass)) {
//    echo "The password is correct! \n";
//} else {
//    echo "The password is NOT correct.\n";
//}

			$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
			$usuario = new Usuario($this->dbAdapter);
			$datos = $usuario->login($user, $pass);
			if ($datos['usuario'] != null) {
				$passBd = $datos['password'];
				if ($bcrypt->verify($pass, $passBd)) {
					$authAdapter = new AuthAdapter($this->dbAdapter, 'usuario', 'usuario', 'password');
					$authAdapter->setIdentity($datos['usuario'])
							->setCredential($datos['password']);
					$auth->setAdapter($authAdapter);
					$auth->authenticate($authAdapter);
					$auth->getStorage()->write($authAdapter->getResultRowObject());
					//return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/usuario/usuario/dentro');
					//return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() );
					return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() );
				} else {
					$this->flashMessenger()->addMessage("Credenciales incorrectas, intentalo de nuevo");
					return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/usuario/usuario/login');
				}
			} else {
				$this->flashMessenger()->addMessage("Credenciales incorrectas, intentalo de nuevo");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/usuario/usuario/login');
			}
		}

		return new ViewModel(
				array("form" => $form)
		);
	}

		public function sesion() {
		$identi = $this->auth->getStorage()->read();
		if ($identi != false && $identi != null) {
			$datos = $identi;
		} else {
			$datos = "No estas identificado";
			//return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/usuario/usuario/login');
		}
		return $datos;
	}
	
	public function dentroAction() {
		$identi = $this->auth->getStorage()->read();
		if ($identi != false && $identi != null) {
			$datos = $identi;
		} else {
			$datos = "No estas identificado";
			//return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/usuario/usuario/login');
		}

		return new ViewModel(
				array("datos" => $datos)
		);
	}

	public function cerrarAction() {
		
		//$this->auth->clearIdentity();
		$session = new Container('session');
 $session->getManager()->destroy();
 var_dump($session->datos);
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/usuario/usuario/login');
	}
}
