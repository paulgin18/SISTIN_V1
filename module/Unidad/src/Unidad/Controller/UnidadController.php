<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Unidad\Controller;

require "vendor/autoload.php";

use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Db\Adapter\Adapter;
use Unidad\Model\Entity\Unidad;
use Zend\MVC\Exception;

class UnidadController extends AbstractActionController {

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
			if ($id == 0 ) {
				return new ViewModel(array('mantenimiento' => 'Crear',
					'textBoton' => 'Guardar',
					'datos' => null));
			} else {
				if ($id == 1 && $cod>0) {
				$datos = $this->buscar($cod);
				return new ViewModel(
						array('mantenimiento' => 'Modificar',
					'textBoton' => 'Actualizar',
					'datos' => $datos));
				}
			}
		}
		//
		
		//
	
	}

	public function buscarUnidadCmbAction() {
		$descripcion = $this->getRequest()->getQuery('term');
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$unidad = new Unidad($this->dbAdapter);
		$items = $unidad->buscarUnidadCmb($descripcion);
		$response = new JsonModel(
				$items
		);
		$response->setTerminal(true);
		return $response;
	}
	
	public function buscar($cod) {
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$unidades = new Unidad($this->dbAdapter);
		$datos = $unidades->buscar($cod);
		return $datos;
	}

	public function registrarAction() {
		$error = 0;
			$msj = "";
		try {
			
			$descripcion = $this->getRequest()->getPost('txtDescripcion');
			$numero = $this->getRequest()->getPost('txtNumero');
			$jerarquia= $this->getRequest()->getPost('txtIdJerarquia');
			$id = $this->getRequest()->getPost('txtId');
			$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
			$unidades = new Unidad($this->dbAdapter);
			
			if ($id != '') {
				$modificar = $unidades->modificar($id,$descripcion,$numero, $jerarquia);
				$msj = $this->mensaje($modificar, 1);
			} else {
				$insert = $unidades->insertar($descripcion,$numero, $jerarquia);
				$msj = $this->mensaje($insert, 0);
			}
		} catch (\Exception $e) {
			$error = 1;
			$codError = explode("(", $e->getMessage());
			$codError = explode("-", $codError[1]);
			$msj = "<h3 style='color:#ca2727'> ALERTA!</h3><hr>";
			switch ($codError[0]) {
				case 23505:
					$msj = $msj . "<br/><strong>MENSAJE:</strong> El registro ingresado '" . $descripcion . "', ya se encuentra en la base de datos.";
					break;
				case 23514:
					$msj = $msj . "<br/><strong>MENSAJE:</strong> El año '" . $descripcion . "' debe ser mayor que 2017.";
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

	public function eliminarAction(){

	    	 $error=0;$tipoConsulta = 0;
			
			$id = $this->getRequest()->getPost('txtId');
			$vigencia = $this->getRequest()->getPost('txtVigencia');
			$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
			$unidades = new Unidad($this->dbAdapter);
		
		    $eliminar = $unidades->eliminar($id,$vigencia);
			//$this->mensajeEliminar($eliminar);
			

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



	public function unidadAction() {
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$unidades = new Unidad($this->dbAdapter);
		$lista = $unidades->lista();
		$viewModel = new ViewModel(array(
			"unidades" => $lista
		));
		return $viewModel;
	}

}
