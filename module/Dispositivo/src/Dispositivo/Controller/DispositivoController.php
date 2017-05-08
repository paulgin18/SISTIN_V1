<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Dispositivo\Controller;

require "vendor/autoload.php";

use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Db\Adapter\Adapter;
use Dispositivo\Model\Entity\Dispositivo;
use Marca\Model\Entity\Marca;
use Modelo\Model\Entity\Modelo;
use Zend\MVC\Exception;

class DispositivoController extends AbstractActionController {

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
		$dispositivos = new Dispositivo($this->dbAdapter);
		$datos = $dispositivos->buscar($cod);
		return $datos;
	}

	public function buscarDispositivoAction() {
		$descripcion = $this->getRequest()->getQuery('term');
		$tipo = $this->getRequest()->getQuery('tipo');
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$dispositivos = new Dispositivo($this->dbAdapter);
		$items = $dispositivos->buscarDispositivo($descripcion, $tipo);
		$response = new JsonModel(
				$items
		);
		$response->setTerminal(true);
		return $response;
	}

	public function bSofDispAction() {
		$descripcion = $this->getRequest()->getQuery('term');
		$tipo = $this->getRequest()->getQuery('tipo');
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$dispositivos = new Dispositivo($this->dbAdapter);
		$items = $dispositivos->bSofDisp($descripcion, $tipo);
		$response = new JsonModel(
				$items
		);
		$response->setTerminal(true);
		return $response;
	}

	public function bMaMoxDispAction() {
		$descripcion = $this->getRequest()->getQuery('term');
		$id_disp = $this->getRequest()->getQuery('id_disp');
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$dispositivos = new Dispositivo($this->dbAdapter);
		$items = $dispositivos->bMaMoxDisp($descripcion, $id_disp);
		$response = new JsonModel(
				$items
		);
		$response->setTerminal(true);
		return $response;
	}

	public function bMaxDispAction() {
		$descripcion = $this->getRequest()->getQuery('term');
		$idDisp = $this->getRequest()->getQuery('idDisp');
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$dispositivos = new Dispositivo($this->dbAdapter);
		$items = $dispositivos->bMaxDisp($descripcion, $idDisp);
		$response = new JsonModel(
				$items
		);
		$response->setTerminal(true);
		return $response;
	}

	public function registrarAction() {
		try {
			$descripcion = $this->getRequest()->getPost('txtDescripcion');
			$tipo = $this->getRequest()->getPost('cmbTipo');
			$ficha = $this->getRequest()->getPost('rbtFicha');
			$idDis = $this->getRequest()->getPost('txtIdDis');

			$idRoute = $this->params()->fromRoute("id", null);
			$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
			$dispositivos = new Dispositivo($this->dbAdapter);

			$mar_mod = $this->getRequest()->getPost('items_marca');
			if (( $idDis == '' || $idDis > 0) && $idRoute == 0) {

				$insert = $dispositivos->insertar($descripcion, $tipo, $ficha, $idDis, $mar_mod);
			} else {

				$insert = $dispositivos->insertar($descripcion, $tipo, $ficha, $idDis, $mar_mod);
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
			$msj = 'PROBLEMASMMMM' . $insert . "S";
		}
		return $msj;
	}

	public function dispositivoAction() {
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$dispositivos = new Dispositivo($this->dbAdapter);
		$lista = $dispositivos->lista();
		$viewModel = new ViewModel(array(
			"dispositivos" => $lista
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

	public function buscarModeloAction() {
		$descripcion = $this->getRequest()->getQuery('term');
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$modelos = new Modelo($this->dbAdapter);
		$items = $modelos->buscarModelo($descripcion);
		$response = new JsonModel(
				$items
		);
		$response->setTerminal(true);
		return $response;
	}

}
