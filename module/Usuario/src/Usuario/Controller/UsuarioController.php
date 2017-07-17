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
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl());
		}
		$form = new LoginForm("form");
		$request = $this->getRequest();
		if ($this->getRequest()->isPost()) {
			$user = $request->getPost('username');
			$pass = $request->getPost('password');
			$bcrypt = new Bcrypt();
		 //$securePass = $bcrypt->create($pass);
			

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
					//var_dump($securePass);
					//var_dump($pass);
					//var_dump($passBd);
					//var_dump($bcrypt->verify($pass, $passBd));
//break;

					$authAdapter = new AuthAdapter($this->dbAdapter, 'usuario', 'usuario', 'password');
					$authAdapter->setIdentity($datos['usuario'])
							->setCredential($datos['password']);
					$auth->setAdapter($authAdapter);
					$auth->authenticate($authAdapter);
					$auth->getStorage()->write($authAdapter->getResultRowObject());
					//return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/usuario/usuario/dentro');
					//return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() );
					return $this->redirect()->toUrl($this->getRequest()->getBaseUrl());
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
		$numero = new Container('numero');
		$session->getManager()->destroy();
		$numero->getManager()->destroy();
		var_dump($session->datos);
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/usuario/usuario/login');
	}

	////
	//MODULO DE LISTADO DE USUARIOS
	public function formAction() {
		$id = $this->params()->fromRoute("id", null);
		$cod = $this->params()->fromRoute("cod", null);
		if ($id !== null) {
			if ($id == 0) {
				return new ViewModel(array('mantenimiento' => 'Crear',
					'textBoton' => 'Guardar',
					'datos' => null));
			} else {
				if ($id == 1 && $cod > 0) {
					$datos = $this->buscar($cod);
					return new ViewModel(
							array('mantenimiento' => 'Modificar',
						'textBoton' => 'Actualizar',
						'datos' => $datos));
				}
			}
		}
	}

	public function buscar($cod) {
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$anios = new Anio($this->dbAdapter);
		$datos = $anios->buscar($cod);
		return $datos;
	}

	public function registrarAction() {
		$error = 0;
		$msj = "";
		try {
			$numero = $this->getRequest()->getPost('txtAnio');
			$descripcion = $this->getRequest()->getPost('txtDescripcion');
			$id = $this->getRequest()->getPost('txtId');

			$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
			$anios = new Anio($this->dbAdapter); 
			if ($id != '') {
				$modificar = $anios->modificar($descripcion, $numero, $id);
				$msj = $this->mensaje($modificar, 1);
			} else {
				$insertar = $anios->insertar($descripcion, $numero);
				$msj = $this->mensaje($insertar, 0);
			}
		} catch (\Exception $e) {
			$error = 1;
			$codError = explode("(", $e->getMessage());
			$codError = explode("-", $codError[1]);
			$msj = "<h3 style='color:#ca2727'> ALERTA!</h3><hr>";
			switch ($codError[0]) {
				case 23505:
					$msj = $msj . "<br/><strong>MENSAJE:</strong> El registro ingresado '" . $numero . "', ya se encuentra en la base de datos.";
					break;
				case 23514:
					$msj = $msj . "<br/><strong>MENSAJE:</strong> El año '" . $numero . "' debe ser mayor que 2017.";
					break;
				default:
					$error = explode("DETAIL:", $codError[2]);

					$msj = $msj . "<strong>CODIGO:</strong>" . $codError[0] . "<br/><br/><strong>MENSAJE</strong> " . strtoupper($error[0]);
					break;
			}
//			Statement could not be executed (23505 - 7 - ERROR: llave duplicada viola restricción de
//				unicidad «idx_anio_descripcion» DETAIL: Ya existe la llave (numero, vigencia)=(2017, t).)
//			
		}
		$response = new JsonModel(array('msj' => $msj, 'error' => $error));
		$response->setTerminal(true);
		return $response;
	}

	public function usuarioAction() {
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$usuarios = new Usuario($this->dbAdapter);
		$listadousuarios = $usuarios->lista();
		$viewModel = new ViewModel(array("usuarios" => $listadousuarios));
		return $viewModel;
	}

	public function fechaAction() {
		
		$viewModel = new ViewModel();
		return $viewModel;
	}


	public function eliminarAction() {
		$error=0;$tipoConsulta = 0;
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$anios = new Anio($this->dbAdapter);
		$cod = $this->getRequest()->getPost('cod');
		$vigencia = $this->getRequest()->getPost('vigencia');
		$eliminar = $anios->eliminar($cod,$vigencia);
		$vigencia=="false" ? $tipoConsulta=2:$tipoConsulta=3;
		$msj = $this->mensaje($eliminar, $tipoConsulta);
		$response = new JsonModel(array('msj' => $msj, 'error' => $error));
		$response->setTerminal(true);
		return $response;
	}

	public function mensaje($valorConsulta, $tipoConsulta) {
		if ($valorConsulta == true) {
			switch ($tipoConsulta) {
				case 0:
					$msj = "REGISTRADO CORRECTAMENTE";
					break;
				case 1:
					$msj = "MODIFICADO CORRECTAMENTE";
					break;
				case 2:
					$msj = "ELIMINADO CORRECTAMENTE";
					break;
				case 3:
					$msj = "ACTIVADO CORRECTAMENTE";
					break;
			}
		} else {
			$msj = "NO SE HA REALIZADO LA ACCION, CONSULTE CON EL ADMINISTRADOR O VUELVA A INTENTARLO";
		}
		return $msj;
	}
	//FIN DE MODULO DE LISTADO  DE USUARIOS

}
