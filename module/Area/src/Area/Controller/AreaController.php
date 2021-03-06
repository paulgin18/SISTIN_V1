<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Area\Controller;

require "vendor/autoload.php";

use Zend\Session\Config\SessionConfigr;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Db\Adapter\Adapter;
use Area\Model\Entity\Area;
use Unidad\Model\Entity\Unidad;
use Zend\MVC\Exception;

class AreaController extends AbstractActionController {

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
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$unidad = new Unidad($this->dbAdapter);
		$lista_unidad_organica = $unidad->lista_unidad_organica();
		if ($id !== null) {
			if ($id == 0 ) {
				return new ViewModel(array('mantenimiento' => 'Crear',
					'textBoton' => 'Guardar',
					'unidades_organicas'=>$lista_unidad_organica,
					'datos' => null));
			} else {
				if ($id == 1 && $cod>0) {
				$datos = $this->buscar($cod);
				return new ViewModel(
						array('mantenimiento' => 'Modificar',
					'textBoton' => 'Actualizar',
					'unidades_organicas'=>$lista_unidad_organica,
					'datos' => $datos));
				}
			}
		}
	}

	public function buscarAreaAction() {
		$descripcion = $this->getRequest()->getQuery('term');
		//$tipo = $this->getRequest()->getQuery('tipo');
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$areas = new Area($this->dbAdapter);
		$items = $areas->buscarArea($descripcion);
		$response = new JsonModel(
				$items
		);
		$response->setTerminal(true);
		return $response;
	}
	
	public function buscar($cod) {
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$areas = new Area($this->dbAdapter);
		$datos = $areas->buscar($cod);
		return $datos;
	}
	
	public function buscarAreaCmbAction() {
		$descripcion = $this->getRequest()->getQuery('term');
		$unidadEjecutora =11; // MODIFICA CON SESIONES
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$area = new Area($this->dbAdapter);
		$items = $area->buscarAreaCmb($descripcion,$unidadEjecutora);
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
			$id_uni_org =$this->getRequest()->getPost('id_uni_org');
			
			$descripcion = $this->getRequest()->getPost('txtDescripcion');
			$id = $this->getRequest()->getPost('txtId_Area');
			$cnx = $this->getRequest()->getPost('cnx');
			$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');

			$areas = new Area($this->dbAdapter);
			if ($id != '') {
				$modificar = $areas->modificar($id,$descripcion,$id_uni_org,$cnx);
				$msj = $this->mensaje($modificar, 1);
			} else {
				$insert = $areas->insertar($descripcion,$id_uni_org,$cnx);
				$msj = $this->mensaje($insert, 0);
			}
			//$msj=$this->mensaje($insert);
						
		} catch (\Exception $e) {
			$error = 1;
			$codError = explode("(", $e->getMessage());
			$codError = explode("-", $codError[1]);
			$msj = "<h3 style='color:#ca2727'> ALERTA!</h3><hr>";
			switch ($codError[0]) {
				case 23505:
					$msj = $msj . "<br/><strong>MENSAJE:</strong> El registro ingresado '" . $numero . "', ya se encuentra en la base de datos.";
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


	public function eliminarAction()
	{
	        $error=0;$tipoConsulta = 0;
			
			$id = $this->getRequest()->getPost('txtId');
			$vigencia = $this->getRequest()->getPost('txtVigencia');
			$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
			$areas = new Area($this->dbAdapter);
		
		   			
	        $eliminar = $areas->eliminar($id,$vigencia);
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

	

	public function areaAction() {
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$areas = new Area($this->dbAdapter);
		$lista = $areas->lista();
		$viewModel = new ViewModel(array(
			"areas" => $lista
		));
		return $viewModel;
	}



}





