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
//Componentes de autenticación
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;

//Incluir modelos

//Incluir formularios
use Usuario\Form\LoginForm;

class UsuarioController extends AbstractActionController {

	private $dbAdapter;
	private $auth;

	public function __construct() {
		//Cargamos el servicio de autenticación en el constructor
		$this->auth = new AuthenticationService();
	}

	public function indexAction() {
		//Vamos a utilizar otros métodos
		return new ViewModel();
	}

	public function loginAction() {
		//
		$auth = $this->auth;
		$identi = $auth->getStorage()->read();
		if ($identi != false && $identi != null) {
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/usuarios/dentro');
		}

		//DbAdapter
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');

		//Creamos el formulario de login
		$form = new LoginForm("form");

		//Si nos llegan datos por post
		if ($this->getRequest()->isPost()) {

			/* Creamos la autenticación a la que le pasamos:
			  1. La conexión a la base de datos
			  2. La tabla de la base de datos
			  3. El campo de la bd que hará de username
			  4. El campo de la bd que hará de contraseña
			 */
			$authAdapter = new AuthAdapter($this->dbAdapter, 'usuarios', 'email', 'password'
			);

			/*
			  Podemos hacer lo mismo de esta manera:
			  $authAdapter = new AuthAdapter($dbAdapter);
			  $authAdapter
			  ->setTableName('users')
			  ->setIdentityColumn('username')
			  ->setCredentialColumn('password');
			 */

			/*
			  En el caso de que la contraseña en la db este cifrada
			  tenemos que utilizar el mismo algoritmo de cifrado
			 */
			$bcrypt = new Bcrypt(array(
				'salt' => 'aleatorio_salt_pruebas_victor',
				'cost' => 5));

			$securePass = $bcrypt->create($this->request->getPost("password"));

			//Establecemos como datos a autenticar los que nos llegan del formulario
			$authAdapter->setIdentity($this->getRequest()->getPost("email"))
					->setCredential($securePass);


			//Le decimos al servicio de autenticación que el adaptador
			$auth->setAdapter($authAdapter);

			//Le decimos al servicio de autenticación que lleve a cabo la identificacion
			$result = $auth->authenticate();

			//Si el resultado del login es falso, es decir no son correctas las credenciales
			if ($authAdapter->getResultRowObject() == false) {

				//Crea un mensaje flash y redirige
				$this->flashMessenger()->addMessage("Credenciales incorrectas, intentalo de nuevo");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/usuarios/login');
			} else {

				// Le decimos al servicio que guarde en una sesión 
				// el resultado del login cuando es correcto
				$auth->getStorage()->write($authAdapter->getResultRowObject());

				//Nos redirige a una pagina interior
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/usuarios/dentro');
			}
		}

		return new ViewModel(
				array("form" => $form)
		);
	}

	public function dentroAction() {
		//Leemos el contenido de la sesión
		$identi = $this->auth->getStorage()->read();

		if ($identi != false && $identi != null) {
			$datos = $identi;
		} else {
			$datos = "No estas identificado";
		}

		return new ViewModel(
				array("datos" => $datos)
		);
	}

	public function cerrarAction() {
		//Cerramos la sesión borrando los datos de la sesión.
		$this->auth->clearIdentity();
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/usuarios/login');
	}

}
