<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Rangoip\Controller;

require "vendor/autoload.php";

use Zend\Session\Config\SessionConfigr;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Db\Adapter\Adapter;
use Rangoip\Model\Entity\Rangoip;
use Zend\MVC\Exception;

class RangoipController extends AbstractActionController {

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
		$rangoips = new Rangoip($this->dbAdapter);
		$datos = $rangoips->buscar($cod);
		return $datos;
	}

	public function registrarAction() {
		$error = 0;
		$msj = "";
		try {
			$id = $this->getRequest()->getPost('txtId');
			$rangoinicial =$this->getRequest()->getPost('txtRangoInicial');
			$rangofinal = $this->getRequest()->getPost('txtRangoFinal');
			$id_area = $this->getRequest()->getPost('id_area');

			$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
			$rangoips = new Rangoip($this->dbAdapter);
			if ($id != '') {
				$modificar = $rangoips->modificar($id,$rangoinicial,$rangofinal,$id_area);
				$msj = $this->mensaje($modificar, 1);
			} else {
				$insert = $rangoips->insertar($rangoinicial,$rangofinal,$id_area);
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
					$msj = $msj . "<br/><strong>MENSAJE:</strong> El registro ingresado '" . $rangoinicial . "' o  '".$rangofinal."', ya se encuentra en la base de datos.";
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
			
			$id = $this->getRequest()->getPost('cod');
			$vigencia = $this->getRequest()->getPost('vigencia');
			$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
			$rangoips = new Rangoip($this->dbAdapter);
		
		   			
	        $eliminar = $rangoips->eliminar($id,$vigencia);
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


	public function rangoipAction() {
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$rangoips = new Rangoip($this->dbAdapter);
		$lista = $rangoips->lista();
		$viewModel = new ViewModel(array(
			"rangoips" => $lista
		));
		return $viewModel;
	}



}





