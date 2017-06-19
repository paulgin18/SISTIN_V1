<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

require "vendor/autoload.php";

use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Usuario\Controller\UsuarioController;
use Usuario\Entity\Usuario;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController {

	public function indexAction() {
		$usuario = new UsuarioController();
		$datos = $usuario->sesion();
		$sessionConfig = new SessionConfig();
		$sessionConfig->setOptions(array(
			'remember_me_seconds' => 1800,
			'use_cookies' => true,
			'cookie_httponly' => true
				)
		);
		$sessionManager = new SessionManager($sessionConfig);
		$sessionManager->start();
		Container::setDefaultManager($sessionManager);
		if (!is_string($datos)) {
			 $sesion=new Container('sesion');
			 !$sesion->datos?$sesion->datos=$datos:"";
			
			return new ViewModel(array("datos" => $sesion->datos));
		} else {
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/usuario/usuario/login');
		}
	}

}

