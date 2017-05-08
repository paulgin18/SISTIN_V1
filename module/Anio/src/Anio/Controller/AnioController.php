<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Anio\Controller;

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

class AnioController extends AbstractActionController {

	public function indexAction() {

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
		$anios = new Anio($this->dbAdapter);
		$datos = $anios->buscar($cod);
		return $datos;
	}

	public function registraranioAction() {
		try {
			$numero = $this->getRequest()->getPost('txtAnio');
			$descripcion = $this->getRequest()->getPost('txtDescripcion');
			$id = $this->getRequest()->getPost('txtId');
			
			$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
			$anios = new Anio($this->dbAdapter);
			
			if ($id != '') {
				$insert = $anios->modificar($descripcion, $numero, $id);
			} else {
				$insert = $anios->insertar($descripcion, $numero);
			}
			if ($insert == true) {
				$msj = 'REGISTRADO CORRECTAMENTE';
			} else {
				$msj = 'PROBLEMAS';
			}
			
		} catch (\Exception $e) {
			$msj = 'Error: ' . $e->getMessage();
		}
		$response = new JsonModel(
				array('msj' => $msj)
		);
		$response->setTerminal(true);
		return $response;
	}

	public function listadoaniosAction() {
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$anios = new Anio($this->dbAdapter);
		$listadoanios = $anios->getAnios();
		//DEVOLVR A A VISTA
		$viewModel = new ViewModel(array(
			"anios" => $listadoanios
		));
		return $viewModel;
	}

}
