<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace User\Controller;

require "vendor/autoload.php";

use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Db\Adapter\Adapter;
use User\Model\Entity\User;
use Zend\MVC\Exception;
use Zend\Crypt\Password\Bcrypt;


class UserController extends AbstractActionController {

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
		$users = new User($this->dbAdapter);
		$datos = $users->buscar($cod);
		return $datos;
	}

	public function registraruserAction() {
		$error = 0;
		$msj = "";
		try {
			$user = $this->getRequest()->getPost('txtUsuario');
			$password = $this->getRequest()->getPost('txtPassword');
			$bcrypt = new Bcrypt();
			$securePass = $bcrypt->create($password);
			$id_personal = $this->getRequest()->getPost('txtIdPersonal');
			$id_rol = $this->getRequest()->getPost('txtIdRol');
			$id_ejecutora = $this->getRequest()->getPost('txtIdEjecutora');
			$id_user = $this->getRequest()->getPost('txtIdUser');


			$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
			$users = new User($this->dbAdapter); 
			if ($id_user != '') {
				$modificar = $users->modificar($user, $securePass, $id_personal,$id_rol,$id_ejecutora,$id_user);
				$msj = $this->mensaje($modificar, 1);
			} else {
				$insertar = $users->insertar($user, $securePass, $id_personal,$id_rol,$id_ejecutora);
				$msj = $this->mensaje($insertar, 0);
			}
		} catch (\Exception $e) {
			$error = 1;
			$codError = explode("(", $e->getMessage());
			$codError = explode("-", $codError[1]);
			$msj = "<h3 style='color:#ca2727'> ALERTA!</h3><hr>";
			switch ($codError[0]) {
				case 23505:
					$msj = $msj . "<br/><strong>MENSAJE:</strong> El registro ingresado '" . $user . "', ya se encuentra en la base de datos.";
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

	public function userAction() {
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$users = new User($this->dbAdapter);
		$listadousers = $users->lista();
		$viewModel = new ViewModel(array("users" => $listadousers));
		return $viewModel;
	}


	public function eliminarAction() {
		$error=0;$tipoConsulta = 0;
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$users = new User($this->dbAdapter);
		$cod = $this->getRequest()->getPost('cod');
		$vigencia = $this->getRequest()->getPost('vigencia');
		$eliminar = $users->eliminar($cod,$vigencia);
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

}
