<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Situacion\Controller;

require "vendor/autoload.php";

use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Db\Adapter\Adapter;
use Situacion\Model\Entity\Situacion;
use Zend\MVC\Exception;

class SituacionController extends AbstractActionController {

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
		$situaciones = new Situacion($this->dbAdapter);
		$datos = $situaciones->buscar($cod);
		return $datos;
	}



	public function registrarsituacionAction() {
		$error = 0;
		$msj = "";
		try {
			
			$descripcion = $this->getRequest()->getPost('txtDescripcion');
			$id = $this->getRequest()->getPost('txtIdSituacion');
			$id_dispositivo = $this->getRequest()->getPost('txtIdDispositivo');
			$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
			$situaciones = new Situacion($this->dbAdapter); 
			if ($id != '') {
				$modificar = $situaciones->modificar($descripcion, $id_dispositivo,$id);
				$msj = $this->mensaje($modificar, 1);
			} else {
				$insertar = $situaciones->insertar($descripcion,$id_dispositivo);
				$msj = $this->mensaje($insertar, 0);
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

	public function listadosituacionesAction() {
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$situaciones = new Situacion($this->dbAdapter);
		$listadosituaciones = $situaciones->lista();
		$viewModel = new ViewModel(array("situaciones" => $listadosituaciones));
		return $viewModel;
	}




	public function eliminarAction() {
		$error=0;$tipoConsulta = 0;
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$situaciones = new Situacion($this->dbAdapter);
		$cod = $this->getRequest()->getPost('cod');
		$vigencia = $this->getRequest()->getPost('vigencia');
		$eliminar = $situaciones->eliminar($cod,$vigencia);
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
