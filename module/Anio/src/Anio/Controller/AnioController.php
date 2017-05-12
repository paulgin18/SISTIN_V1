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
		$anios = new Anio($this->dbAdapter);
		$datos = $anios->buscar($cod);
		return $datos;
	}

	public function registraranioAction() {
		$error = 0;
		$msj = "";
		try {
			$numero = $this->getRequest()->getPost('txtAnio');
			$descripcion = $this->getRequest()->getPost('txtDescripcion');
			$id = $this->getRequest()->getPost('txtId');

			$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
			$anios = new Anio($this->dbAdapter); //Instanciar
			if ($id != '') {
				$modificar = $anios->modificar($descripcion, $numero, $id);
				$msj = $this->mensaje($modificar, 1);
			} else {
				$insertar = $anios->insertar($descripcion, $numero);
				$msj = $this->mensaje($insertar, 0);
			}
		} catch (\Exception $e) {
			$error = 1;
			$codError = explode("(", $e->getMessage());
			$codError = explode("-", $codError[1]);
			$msj = "<h3 style='color:#ca2727'> ALERTA!</h3><hr>";
			switch ($codError[0]) {
				case 23505:
					$msj = $msj . "<br/><strong>MENSAJE:</strong> El registro ingresado '" . $numero . "', ya se encuentra en la base de datos.";
					break;
				case 23514:
					$msj = $msj . "<br/><strong>MENSAJE:</strong> El año '" . $numero . "' debe ser mayor que 2017.";
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

	public function listadoaniosAction() {
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$anios = new Anio($this->dbAdapter);
		$listadoanios = $anios->lista();
		$viewModel = new ViewModel(array("anios" => $listadoanios));
		return $viewModel;
	}

	public function eliminarAction() {
		$error = 0;
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$anios = new Anio($this->dbAdapter);
		$cod = $this->getRequest()->getPost('cod');
		$eliminar = $anios->eliminar($cod);
		$msj = $this->mensaje($eliminar, 2);
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
			}
		} else {
			$msj = "NO SE HA REALIZADO LA ACCION, CONSULTE CON EL ADMINISTRADOR O VUELVA A INTENTARLO";
		}
		return $msj;
	}

}
