<?php

namespace JAA\SeguroBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JAA\SeguroBundle\Entity\Usuario;
use JAA\SeguroBundle\Form\Type\RegistroType;
use JAA\SeguroBundle\Form\Type\ResetType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DefaultController extends Controller {

    private $session;

// Funciones del controlador
/***************************************************************/
    public function __construct() {
        //Cargamos el componente de sesion en todos los metodos
        $this->session = new Session();
    }

/***************************************************************/
	public function changeAction(Request $request){
		
	    var_dump($request->getLocale());
 
		return $this->render('default/index.html.twig', array(
		  'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..'),
		  'locale' => $request->getLocale(),
		));	
		
	}
/***************************************************************/
    public function indexAction(Request $request) {
        //Instanciamos el obejeto usuario
        $user = new Usuario();
        
        //Creamos el formulario de Registro
        $registro_form = $this->createForm(new RegistroType(), $user);

        //Recogemos los datos
        $registro_form->handleRequest($request);

        //Si el formulario está enviado
        if ($registro_form->isSubmitted()) {
            //Consigue los datos
            $nombre = $registro_form->get('name')->getData();
            $apellidos = $registro_form->get('surname')->getData();
            $email = $registro_form->get('email')->getData();

            //Cifra la contraseña
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword($registro_form->get('password')->getData(), $user->getSalt());

            //Seteamos los atributos
            $user->setName($nombre);
            $user->setSurname($apellidos);
            $user->setEmail($email);
            $user->setPassword($password);
            $user->setDescription("Describete");
            $user->setImage("null");
            $user->setRole("ROLE_USUARIO");
        }

        //Si el formulario es valido
        if ($registro_form->isValid()) {
            
            $repositorio = $this->getDoctrine()->getRepository('JAASeguroBundle:Usuario');

            $email_existe = false;

            if ($email_existe == false) {
                //Guarda el usuario en base de datos
                $em = $this->getDoctrine()->getManager();
                $persist = $em->persist($user);
                $flush = $em->flush();
                
                //generar flasdata
                $this->session->getFlashBag()->add('info', '¡Enhorabuena! Te has registrado correctamente');
                
                //Redirigir
                return $this->redirect($this->generateURL('inicio'));
            } else {
                //genera una sesion flasdata
                $this->session->getFlashBag()->add('info', 'Email o nick duplicado intentalo de nuevo');
            }
        }

        //Categorias
        $em = $this->getDoctrine()->getEntityManager();
        $categoryRepository=$em->getRepository("JAASeguroBundle:Tipoficha");
        $categories=$categoryRepository->findAll();

        //Renderizar vista y pasar formulario
        return $this->render('JAASeguroBundle:Default:registro.html.twig', 
                array('registro_form' => $registro_form->createView(),
                    "categories"=>$categories
                ));
    }

/***************************************************************/
    public function profileAction(Request $request) {
        //Instanciamos el obejeto usuario
        $user=$this->get('security.context')->getToken()->getUser();
        
        //Creamos el formulario de Registro
        $registro_form = $this->createForm(new RegistroType(), $user);

        //Recogemos los datos
        $registro_form->handleRequest($request);

        //Si el formulario está enviado
        if ($registro_form->isSubmitted()) {
            //Consigue los datos
            $nombre = $registro_form->get('name')->getData();
            $apellidos = $registro_form->get('surname')->getData();
            $email = $registro_form->get('email')->getData();

            //Cifra la contraseña
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword($registro_form->get('password')->getData(), $user->getSalt());

            //Seteamos los atributos
            $user->setName($nombre);
            $user->setSurname($apellidos);
            $user->setEmail($email);
            $user->setPassword($password);
            $user->setDescription("Describete");
            $user->setImage("default_avatar.png");
            $user->setRole("ROLE_USUARIO");
        }

        //Si el formulario es valido
        if ($registro_form->isValid()) {
            
            $repositorio = $this->getDoctrine()->getRepository('JAASeguroBundle:Usuario');

            $email_existe = false;

            if ($email_existe == false) {
                //Guarda el usuario en base de datos
                $em = $this->getDoctrine()->getManager();
                $persist = $em->persist($user);
                $flush = $em->flush();
                
                //generar flasdata
                $this->session->getFlashBag()->add('info', '¡Enhorabuena! Te has registrado correctamente');
                
                //Redirigir
                return $this->redirect($this->generateURL('inicio'));
            } else {
                //genera una sesion flasdata
                $this->session->getFlashBag()->add('info', 'Email o nick duplicado intentalo de nuevo');
            }
        }

        //Categorias
        $em = $this->getDoctrine()->getEntityManager();
        $categoryRepository=$em->getRepository("JAASeguroBundle:Tipoficha");
        $categories=$categoryRepository->findAll();

        //Renderizar vista y pasar formulario
        return $this->render('JAASeguroBundle:Default:registro.html.twig', 
                array('registro_form' => $registro_form->createView(),
                    "categories"=>$categories
                ));
    }
    
/***************************************************************/
    public function resetAction (Request $request){
		//Creamos el formulario de Registro
        $reset_form = $this->createForm(new ResetType());
        //Recogemos los datos
        $reset_form->handleRequest($request);
		if ($reset_form->isSubmitted()) {
			$email = $reset_form->get('email')->getData();
			if(!$email){
				return false;				
			}
			else{//// Puede haber errores  ->getEntityManager
				$repository = $this->getDoctrine()->getRepository('JAASeguroBundle:Usuario');
				// Usuario con el email depositado
				$user = findOneByEmail($email);					
				// Generamos una nueva contraseña				
				$pass = rand(5,15);	
				//Cifra la contraseña
		        $factory = $this->get('security.encoder_factory');
		        $encoder = $factory->getEncoder($user);
		        $password = $encoder->encodePassword($pass, $user->getSalt());					
				$user->setPassword($password);
			}
				
		  }
		if($reset_form->isValid()){
				$em = $this->getDoctrine()->getManager();
                $persist = $em->persist($user);
                $flush = $em->flush();	
				//generar flasdata
                $this->session->getFlashBag()->add('info', 'Se ha enviado un mensaje a su correo electrónico.');	
				// Enviamos un mensaje al correo del usuario				
				$mensaje = '<html>
				  <head>
					<title>Restablece tu contraseña</title>
				 </head>
				 <body>
				   <p>Hemos recibido una petición para restablecer la contraseña de tu cuenta.</p>
				   <p>Su nueva contraseña es: '.$pass.'
				 </body>
				</html>';
			 
			   $cabeceras = 'MIME-Version: 1.0' . "\r\n";
			   $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			   $cabeceras .= 'From: admin <admin@server.com>' . "\r\n";
			   // Se envia el correo al usuario
			   mail($email, "Recuperar contraseña", $mensaje, $cabeceras);	
		} 
		return $this->render('JAASeguroBundle:Default:reset.html.twig',  array('reset_form' => $reset_form->createView()));
    }

/***************************************************************/

    public function loginAction(Request $request){ 
        // Si la autenticación falla que nos lleve a registro 
		// Si no falla la autentificación lo llevamos a /author_id
        if($this->session->get(SecurityContext::AUTHENTICATION_ERROR)){
           $this->session->getFlashBag()->add('login', 'Introduce unas credenciales correctas o registrese');
           return $this->redirect($this->generateURL('home'));
        }else{
            if($this->get('security.context')->isGranted('ROLE_USUARIO')){
                return $this->redirect($this->generateURL('home'));
            }
        }
    }

}
