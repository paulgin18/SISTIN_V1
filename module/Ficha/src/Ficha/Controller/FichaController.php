<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Ficha\Controller;

require "vendor/autoload.php";

use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Db\Adapter\Adapter;
use Ficha\Model\Entity\Ficha;
use Marca\Model\Entity\Marca;
use Zend\MVC\Exception;

class FichaController extends AbstractActionController {

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
		$fichas = new Ficha($this->dbAdapter);
		$datos = $fichas->buscar($cod);
		return $datos;
	}

	public function registrarAction() {
		try {
			$descripcion = $this->getRequest()->getPost('txtDescripcion');
			$tipo = $this->getRequest()->getPost('txtTipo');
			$ficha = $this->getRequest()->getPost('chkFicha');
			$vigencia = $this->getRequest()->getPost('chkVigencia');
			$id_marca = $this->getRequest()->getPost('txtIdMarca');
			$id_modelo = $this->getRequest()->getPost('txtIdModelo');
			$id = $this->getRequest()->getPost('txtId');
			$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
			$fichas = new Ficha($this->dbAdapter);
			if ($id != '') {
				$insert = $fichas->modificar($id, $descripcion, $tipo, $ficha, $vigencia, $id_marca, $id_modelo);
			} else {
				$insert = $fichas->insertar($descripcion, $tipo, $ficha, $id_marca, $id_modelo);
			}
			$msj = $this->mensaje($insert);
		} catch (\Exception $e) {
			$msj = 'Error: ' . $e->getMessage();
		}
		$response = new JsonModel(
				array('msj' => $msj)
		);
		$response->setTerminal(true);
		return $response;
	}

	public function mensaje($insert) {
		if ($insert == true) {
			$msj = 'REGISTRADO CORRECTAMENTE';
		} else {
			$msj = 'PROBLEMAS';
		}
		return $msj;
	}

	public function fichaAction() {
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$fichas = new Ficha($this->dbAdapter);
		$lista = $fichas->lista();
		$viewModel = new ViewModel(array(
			"fichas" => $lista
		));
		return $viewModel;
	}

	public function buscarMarcaAction() {
		$descripcion = $this->getRequest()->getQuery('term');
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$marcas = new Marca($this->dbAdapter);
		$items = $marcas->buscarMarca($descripcion);
		$response = new JsonModel(
				$items
		);
		$response->setTerminal(true);
		return $response;
	}

}
