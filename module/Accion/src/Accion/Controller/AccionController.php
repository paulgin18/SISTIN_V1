<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Accion\Controller;

require "vendor/autoload.php";

use Zend\Session\Config\SessionConfigr;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Db\Adapter\Adapter;
use Accion\Model\Entity\Accion;
use Zend\MVC\Exception;

class AccionController extends AbstractActionController {

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
	}

	public function buscar($cod) {
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$acciones = new Accion($this->dbAdapter);
		$datos = $acciones->buscar($cod);
		return $datos;
	}

	public function registrarAction() {
		try {
			$tipo =$this->getRequest()->getPost('txtTipo');
			$vigencia = $this->getRequest()->getPost('chkVigencia');
			$descripcion = $this->getRequest()->getPost('txtDescripcion');
			$id = $this->getRequest()->getPost('txtId');
			$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
			$acciones = new Accion($this->dbAdapter);
			if ($id != '') {
				$insert = $acciones->modificar($id,$descripcion,$tipo, $vigencia );
			} else {
				$insert = $acciones->insertar($tipo,$descripcion);
			}
			$msj=$this->mensaje($insert);
						
		} catch (\Exception $e) {
			$msj = 'Error: ' . $e->getMessage();
		}
		$response = new JsonModel(
				array('msj' => $msj)
		);
		$response->setTerminal(true);
		return $response;
	}
	

	public function eliminarAction(){
	    try {
			
			$id = $this->params()->fromRoute('id');
			$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
			$acciones = new Accion($this->dbAdapter);
			$eliminar = $acciones->eliminar($id);
			
			$msj =$this->mensajeEliminar($eliminar);
			

		} catch (\Exception $e) {
			$msj = 'Error: ' . $e->getMessage();
		}
		$response = new JsonModel(
				array('msj' => $msj)
		);
		$response->setTerminal(true);
		return $response;

	}



	public function mensaje($insert){
		if ($insert == true) {
				$msj = 'REGISTRADO CORRECTAMENTE';
			} else {
				$msj = 'PROBLEMAS';
			}
			return $msj;
	}


public function mensajeEliminar($eliminar){
		if ($eliminar == true) {
				$msj = 'ELIMINADO CORRECTAMENTE';
			} else {
				$msj = 'PROBLEMAS';
			}
			return $msj;
	}

	public function accionAction() {
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$acciones = new Accion($this->dbAdapter);
		$lista = $accciones->lista();
		$viewModel = new ViewModel(array(
			"acciones" => $lista
		));
		return $viewModel;
	}

}





