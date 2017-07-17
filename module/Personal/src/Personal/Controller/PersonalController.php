<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Personal\Controller;

require "vendor/autoload.php";

use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Db\Adapter\Adapter;
use Personal\Model\Entity\Personal;
use Zend\MVC\Exception;

class PersonalController extends AbstractActionController {

	public function indexAction() {


		//INICIO VARIABLES DE SESION
		// $sessionConfig = new SessionConfig();
		// $sessionConfig->setOptions(array(
		//     'remember_me_seconds' => 180,
		//     'use_cookies' => true,
		//     'cookie_httponly' => true
		//     )
		// );
		// $sessionManager = new SessionManager($sessionConfig);
		// $sessionManager->start();
		// Container::setDefaultManager($sessionManager);
		// $session = new Container('userdata');
		// $session->username = 'paul';
		// $session->sis_userid = '1';
		//FIN VARIABLES DE SESION
//        $session = $session->username;
//        return new ViewModel(array('session' => $session ));
	}

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
		$personals = new Personal($this->dbAdapter);
		$datos = $personals->buscar($cod);
		return $datos;
	}
	public function buscapersonalsigaAction() {
		$descripcion = $this->getRequest()->getQuery('term');
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$personal= new Personal($this->dbAdapter);
		$items = $personal->buscarPersonalsiga($descripcion);
		$response = new JsonModel(
				$items
		);
		$response->setTerminal(true);
		return $response;
	}

	public function buscarFuncionarioAction() {
		$descripcion = $this->getRequest()->getQuery('term');
		$sesion=new Container('sesion');
		$unidad_ejecutora = $sesion->datos->id_unidad_ejecutora;
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$personal = new Personal($this->dbAdapter);
		$items = $personal->buscarFuncionario($descripcion, $unidad_ejecutora);
		$response = new JsonModel(
				$items
		);
		$response->setTerminal(true);
		return $response;
	}


	public function registrarAction() {
		$error = 0;
		$msj = "";
		try {
			$id = $this->getRequest()->getPost('txtId');			
			$nombre = $this->getRequest()->getPost('txtNombre');
			$apellido_paterno = $this->getRequest()->getPost('txtApellidoPaterno');
			$apellido_materno = $this->getRequest()->getPost('txtApellidoMaterno');
			$dni = $this->getRequest()->getPost('txtDNI');
			$id_area = $this->getRequest()->getPost('id_area');
			$responsable= $this->getRequest()->getPost('responsable');
			$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
			$personal = new Personal($this->dbAdapter);


			if ($id != '') {
				$modificar= $personal->modificar($id, $nombre,$apellido_paterno,$apellido_materno,$dni,$id_area,$responsable);
				$msj = $this->mensaje($modificar, 1);
			} else {
				$insert = $personal->insertar($nombre,$apellido_paterno,$apellido_materno,$dni,$id_area,$responsable);
				$msj = $this->mensaje($insert, 0);
			}
		} catch (\Exception $e) {
			$error = 1;
			$codError = explode("(", $e->getMessage());
			$codError = explode("-", $codError[1]);
			$msj = "<h3 style='color:#ca2727'> ALERTA!</h3><hr>";
			switch ($codError[0]) {
				case 23505:
					$msj = $msj . "<br/><strong>MENSAJE:</strong> El registro ingresado '" . $dni. "', ya se encuentra en la base de datos.";
					break;
			
				default:
					$error = explode("DETAIL:", $codError[2]);

					$msj = $msj . "<strong>CODIGO:</strong>" . $codError[0] . "<br/><br/><strong>MENSAJE</strong> " . strtoupper($error[0]);
					break;
			}
		}
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

	public function personalAction() {
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$personals = new Personal($this->dbAdapter);
		$lista = $personals->lista();
		$viewModel = new ViewModel(array(
			"personals" => $lista
		));
		return $viewModel;
	}

	public function eliminarAction() {
		$error = 0;$tipoConsulta=0;
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$personals = new Personal($this->dbAdapter);
		$cod = $this->getRequest()->getPost('cod');
		$vigencia = $this->getRequest()->getPost('vigencia');
		$vigencia=="false" ? $tipoConsulta=2:$tipoConsulta=3;
		$eliminar = $personals->eliminar($cod, $vigencia);
		$msj = $this->mensaje($eliminar, $tipoConsulta);
		$response = new JsonModel(array('msj' => $msj, 'error' => $error));
		$response->setTerminal(true);
		return $response;
	}

}
