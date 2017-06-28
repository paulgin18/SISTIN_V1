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
use Docadquisicion\Model\Entity\Docadquisicion;
use Marca\Model\Entity\Marca;
use Anio\Model\Entity\Anio;
use Zend\MVC\Exception;
use Zend\File\Transfer\Adapter\Http;
 
class FichaController extends AbstractActionController {

	public function formAction() {
		$id = $this->params()->fromRoute("id", null);
		$cod = $this->params()->fromRoute("cod", null);
		if ($id !== null) {
			$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
			$docAdquisicion = new Docadquisicion($this->dbAdapter);
			$datosAdquisicion = $docAdquisicion->lista("true");
			$session = new Container('sesion');
			$numeroFicha = new Container('numero');
			if($session->datos!=null){
			if ($id == 0) {
				$fichas = new Ficha($this->dbAdapter);
				$numero=2;
				if($numeroFicha->datos==null){
					$numero = $fichas->obtenerNumero($session->datos->id_user,$session->datos->id_unidad_ejecutora);
					$numeroFicha->datos= (object)array("numero"=>$numero);
				
				}else{
					$numero=$numeroFicha->datos->numero;
				}
				//}while($numeroFicha->datos->numero!=null);
				
				return new ViewModel(array('mantenimiento' => 'Crear', 'textBoton' => 'Guardar',
					'datosAdquisicion' => $datosAdquisicion,
					'datos' => null,
					'nro'=>$numero));
			} else {
				if ($id == 1 && $cod > 0) {
					$datos = $this->buscar($cod);
					return new ViewModel(
							array('mantenimiento' => 'Modificar',
						'textBoton' => 'Actualizar',
						'datosAdquisicion' => $datosAdquisicion,
						'datos' => $datos));
				}
			}
			}else{
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/usuario/usuario/login');
			}
		}
	}

	public function buscar($cod) {
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$anios = new Anio($this->dbAdapter);
		$datos = $anios->buscar($cod);
		return $datos;
	}

	public function registrarAction() {
		$error = 0;
		$msj = "";
		$session = new Container('sesion');
		try {

//	,txtIdEquipo, txtFechaAdquisicion,,
//		txtSeriePC,txtNroPatrimonio,txtIdSO,txtLicenciaSO,
//	
			$fecha_inst = $this->getRequest()->getPost('txtFechaInstalacion');
			


			$numero = $this->getRequest()->getPost('txtNroFicha');
			$fecha = $this->getRequest()->getPost('txtFecha');
			$nompc = $this->getRequest()->getPost('txtNomPc');
			
			$observacion = 'q';
			$id_user = $session->datos->id_user;;
			$fichapost = $this->getRequest()->getPost('ficha');
			$operativo = $this->getRequest()->getPost('chkOpOtros');
			$garantia = $this->getRequest()->getPost('chkGarantia');
			$anioGarantia = $this->getRequest()->getPost('txtAnioGarantia');
			$compatible = $this->getRequest()->getPost('chkCompatible');
			
			
			$tblPersonal= $this->getRequest()->getPost('tblPersonal');
			$tblMicroprocesador = $this->getRequest()->getPost('tblMicroprocesador');
			$tblDiscoDuro = $this->getRequest()->getPost('tblDiscoDuro');
			$tblMainboard = $this->getRequest()->getPost('tblMainboard');
			$tblRed = $this->getRequest()->getPost('tblRed');
			$tblRam = $this->getRequest()->getPost('tblRam');
			$tblSoft = $this->getRequest()->getPost('tblSoftware');
			$tblOtro = $this->getRequest()->getPost('tblOtrosComponentes');
			$tblUser = $this->getRequest()->getPost('tblUser');
			$tblFichaDisp = $this->getRequest()->getPost('tblFichaDisp');
			$tblFichaAd = $this->getRequest()->getPost('tblFichaAd');
			$tblArchivo = $this->getRequest()->getPost('tblArchivo');
			
			$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
			$ficha = new Ficha($this->dbAdapter);
//if ($id != '') {
//				$modificar = $ficha->modificar($descripcion, $numero, $id);
//				$msj = $this->mensaje($modificar, 1);
//			} else {
			$insertar = $ficha->insertar($fichapost, $numero, $fecha, $nompc, $observacion, 
					$id_user, $id_respfuncionario, $id_resppatrimonio, $tblMicroprocesador, 
					$tblDiscoDuro, $tblMainboard, $tblRam, $tblRed, $tblSoft, $tblOtro, $tblUser, 
					$tblFichaDisp, $tblFichaAd, $tblArchivo, $tblPersonal
					);
			
			$msj = $this->mensaje($insertar, 0);
//			}
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
					$msj = $msj . "<br/><strong>MENSAJE:</strong> La Ficha de  '" . $numero . "' debe ser mayor que 2017.";
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

	public function subirArchivoAction() {
		$error = 0;
		$msj = "";
		$adapter = new Http();
		$adapter->addValidator('Count', false, array('min' => 0, 'max' => 1))
				->addValidator('Size', false, array('max' => '5242880000000'))
				->addValidator('Extension', false, array('extension' => 'rar', 'case' => true));
		try {
			$adapter->setDestination("c:\\files");
			$files = $adapter->getFileInfo();
			foreach ($files as $fieldname => $fileinfo) {
				if (($adapter->isUploaded($fileinfo[name])) && ($adapter->isValid($fileinfo['name']))) {
					$adapter->receive($fileinfo[name]);
				}
			}

			$response = new JsonModel(array('msj' => $adapter->getMessages(), 'error' => '0'));
		} catch (\Exception $ex) {
			$error = $ex->getMessage();
		}
		$response->setTerminal(true);
		return $response;
	}

	function validateAllImg($upload) {
		$invalid = Array();
		foreach ($upload->getFileInfo() as $file => $info) {
			$upload->addValidator('Size', false, 256000)
					->addValidator('Extension', false, array('jpg', 'jpeg', 'gif', 'png'));
			if (!$upload->isValid($file))
				$invalid[] = $file;
		}
		return $invalid;
	}

	function uploadAllImg($upload) {
		foreach ($upload->getFileInfo() as $file => $info) {
			$upload->addFilter('Rename', array('target' => APPLICATION_PATH . '/public/img/uploads/page02/' . $info['name'], 'overwrite' => true));
			$upload->receive($file);
		}
	}

	public function eliminarAction() {
		$error = 0;
		$tipoConsulta = 0;
		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
		$anios = new Anio($this->dbAdapter);
		$cod = $this->getRequest()->getPost('cod');
		$vigencia = $this->getRequest()->getPost('vigencia');
		$eliminar = $anios->eliminar($cod, $vigencia);
		$vigencia == "false" ? $tipoConsulta = 2 : $tipoConsulta = 3;
		$msj = $this->mensaje($eliminar, $tipoConsulta);
		$response = new JsonModel(array('msj' => $msj, 'error' => $error));
		$response->setTerminal(true);
		return $response;
	}

	public function mensaje($valorConsulta, $tipoConsulta) {
		if ($valorConsulta == true) {
			switch ($tipoConsulta) {
				case 0:
					$msj = "REGISTRADO CORRECTAMENTE" . $valorConsulta;
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
			$msj = "NO SE HA REALIZADO LA ACCION, CONSULTE CON EL ADMINISTRADOR O VUELVA A INTENTARLO" . $valorConsulta . "a";
		}
		return $msj;
	}

	public function bserieAction() {
		$error = 0;
		$msj = "";
		try {
			$serie = $this->getRequest()->getPost('serie');
			$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
			$ficha = new Ficha($this->dbAdapter);
			$datos = $ficha->buscarSerie($serie);
		} catch (\Exception $e) {
			$error = 1;
			$codError = explode("(", $e->getMessage());
			$codError = explode("-", $codError[1]);
			$msj = "<h3 style = 'color:#ca2727'> ALERTA!</h3><hr>";
			$error = explode("DETAIL:", $codError[2]);
			$msj = $msj . "<strong>CODIGO:</strong>" . $codError[0] . "<br/><br/><strong>MENSAJE</strong> " . strtoupper($error[0]);
		}

		$response = new JsonModel(array('msj' => $datos['count'], 'error' => $error));
		$response->setTerminal(true);
		return $response;
	}

	public function fichaAction() {
		$session = new Container('sesion');
		if ($session->datos != null) {
			$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
			$fichas = new Ficha($this->dbAdapter);

			$lista = $fichas->lista();
			$viewModel = new ViewModel(array(
				"fichas" => $lista,
			));
			//var_dump($session->datos);
			return $viewModel;
		} else {
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/usuario/usuario/login');
		}
	}

}
