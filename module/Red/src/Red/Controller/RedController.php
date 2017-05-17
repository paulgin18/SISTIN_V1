<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Red\Controller;

require "vendor/autoload.php";

use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Db\Adapter\Adapter;
use Red\Model\Entity\Red;
use Unidad\Model\Entity\Unidad;
use Zend\MVC\Exception;

class RedController extends AbstractActionController {

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

	//metodo relacionado con el tipo de formulario que se abre
	//puede ser el formulario de Insertar o Modificar 
	public function formAction() {
		$id = $this->params()->fromRoute("id", null); // editar o insertar 0=insertar y 1=modificar 
		$cod = $this->params()->fromRoute("cod", null); //recupera el id de bd
		
		if ($id !== null) {
			if ($id == 0 ) {

				//ESTABLECE UNA CONEXION CON LA BD Y OBTIENE LA LISTA
		$this->dbAdapter = $this->getServiceLocator() -> get('Zend\Db\Adapter');		
		$unidades = new Unidad($this->dbAdapter);
		$lista = $unidades->lista();
				//RETORNA UN NUEVO MODELO AGREGANDO LA VARIABLE UNIDADES Q CONTIENE LA LISTA
				return new ViewModel(array('mantenimiento' => 'Crear',
					'textBoton' => 'Guardar',
					"unidades" => $lista,
					'datos' => null));

			} else {
				//condicion para editar un registro cod= es el id de la base de datos
				if ($id == 1 && $cod>0) {
				$this->dbAdapter = $this->getServiceLocator() -> get('Zend\Db\Adapter');		
				$unidades = new Unidad($this->dbAdapter);
				$lista = $unidades->lista();

				$datos = $this->buscarRed($cod);

				return new ViewModel(
					    // la variable mantenimiento de la vista, cambia a Modificar
						array('mantenimiento' => 'Modificar',
					'textBoton' => 'Actualizar',
					"unidades" => $lista, //contiene una lista de las unidades ejecutoras
					'datos' => $datos)); // contiene un registro buscado de las redes
				}
			}
		}
	}

     
	public function buscarRed($cod) {
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$redes = new Red($this->dbAdapter);
		$datos = $redes->buscar($cod);
		return $datos;
	}
	

	public function buscarUnidad(){
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$unid=ades|  new Unidad($this->dbAdapter);
		$datos = $redes->buscar($cod);
		return $datos;
	}

	//metodo encargado de la accion de Insertar o Actualizar se relaciona 
	// con el formulario 
	public function registrarAction() {
		$error = 0;
		$msj = "";
		try {
			
			$descripcion = $this->getRequest()->getPost('txtDescripcion');
			$id = $this->getRequest()->getPost('txtId');
			$cmbUnidad = $this->getRequest()->getPost('cmbUnidad');
			$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
			$redes = new Red($this->dbAdapter);
			if ($id != '') {
				$modificar = $redes->modificar($id,$descripcion);
				$msj = $this->mensaje($modificar, 1);
			} else {
				$insert = $redes->insertar($descripcion,$cmbUnidad);
				$msj = $this->mensaje($insert, 0);

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

	public function eliminarAction(){
	    $error=0;$tipoConsulta = 0;
			
			$id = $this->getRequest()->getPost('txtId');
			$vigencia = $this->getRequest()->getPost('txtVigencia');
			$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
			$redes = new Red($this->dbAdapter);
		
		   			
$eliminar = $redes->eliminar($id,$vigencia);
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


	public function redAction() {
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$redes = new Red($this->dbAdapter);
		$lista = $redes->lista();
		$viewModel = new ViewModel(array(
			"redes" => $lista
		));
		return $viewModel;
	}

}
