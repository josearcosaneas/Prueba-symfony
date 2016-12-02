<?php

namespace JAA\SeguroBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JAA\SeguroBundle\Entity\Usuario;
use JAA\SeguroBundle\Entity\Ficha;
use JAA\SeguroBundle\Entity\Contact;
use JAA\SeguroBundle\Form\Type\FichaType;
use JAA\SeguroBundle\Form\Type\SearchType;
use JAA\SeguroBundle\Form\Type\CombotiposType;
use JAA\SeguroBundle\Form\Type\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Doctrine\ORM\Tools\Pagination\Paginator;

class FichaController extends Controller {

    private $session;
/***************************************************************/
    public function __construct() {
        //Cargamos el componente de sesion en todos los metodos
        $this->session = new Session();
    }
/***************************************************************/
    public function contactAction(){
		$enquiry = new Contact();
		$form = $this->createForm(new ContactType(), $enquiry);

		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);

			if ($form->isValid()) {
				// realiza alguna acción, como enviar un correo electrónico

				// Redirige - Esto es importante para prevenir que el usuario
				// reenvíe el formulario si actualiza la página
				return $this->redirect($this->generateUrl('contact'));
			}
		}

		return $this->render('JAASeguroBundle:Default:contact.html.twig', array(
			'form' => $form->createView()
		));
	}	
/***************************************************************/
	public function aboutAction(){
        return $this->render('JAASeguroBundle:Ficha:about.html.twig');
    }

/***************************************************************/
    public function index2Action() {

        //Renderizamos la vista
        return $this->render('JAASeguroBundle:Ficha:index2.html.twig');
    }
/***************************************************************/
	public function indexAction($page) {
        //Entity Manager
        $em = $this->getDoctrine()->getManager();
        
        //Repositorios de entidades a utilizar
        $TipofichaRepository=$em->getRepository("JAASeguroBundle:Tipoficha");
        $FichaRepository=$em->getRepository("JAASeguroBundle:Ficha");
        
        //Conseguir todas los tipos de ficha
        $Tipoficha=$TipofichaRepository->findAll();
        
        //Conseguir todos los fichas paginadas
        $pageSize=3;

        $paginator=$FichaRepository->getPaginateFicha($pageSize,$page);
        $totalItems = count($paginator);
		$pagesCount = ceil($totalItems / $pageSize);
    
        //Renderizamos la vista
        return $this->render('JAASeguroBundle:Ficha:index.html.twig', array(
            "Tipoficha"=>$Tipoficha,
            "Ficha"     => $paginator,
            "totalItems" => $totalItems,
            "pagesCount" => $pagesCount
        ));
    }
/***************************************************************/
	public function index3Action($page,Request $request) {
        //Entity Manager
        $em = $this->getDoctrine()->getManager();
        //Repositorios de entidades a utilizar
        $TipofichaRepository=$em->getRepository("JAASeguroBundle:Tipoficha");
        $FichaRepository=$em->getRepository("JAASeguroBundle:Ficha");
        $user=$this->get('security.context')->getToken()->getUser();

		$form = $this->createForm(new CombotiposType());
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $tipoficha_id = $form->get('tipoficha')->getData();
            $tipoficha=$TipofichaRepository->find($tipoficha_id);
           		
		}	
		if ($form->isValid()) {
            
			if (strcmp($tipoficha->name, 'Todos') == 0){
						
				$Tipoficha=$TipofichaRepository->findAll();
				
				//Conseguir todos los fichas paginadas
				$pageSize=3;

				$paginator=$FichaRepository->getPaginate1Ficha($pageSize,$page,$user);
				$totalItems = count($paginator);
				
				$pagesCount = ceil($totalItems / $pageSize);
			
				//Renderizamos la vista
				return $this->render('JAASeguroBundle:Ficha:index.html.twig', array(
					"form" => $form->createView(),
					"Tipoficha"=>$Tipoficha,
					"Ficha"     => $paginator,
					"totalItems" => $totalItems,
					"pagesCount" => $pagesCount
				));
								
			}else{
				$Tipoficha=$TipofichaRepository->findAll();
				
				//Conseguir todos los fichas paginadas
				$pageSize=3;

				$paginator=$FichaRepository->getPaginate2Ficha($pageSize,$page,$user,$tipoficha_id);
				$totalItems = count($paginator);
				$pagesCount = ceil($totalItems / $pageSize);
			
				//Renderizamos la vista
				return $this->render('JAASeguroBundle:Ficha:index4.html.twig', array(
					"form" => $form->createView(),
					"Tipoficha"=>$Tipoficha,
					"Ficha"     => $paginator,
					"totalItems" => $totalItems,
					"pagesCount" => $pagesCount
				));
			}
        }else{
        
       
			$Tipoficha=$TipofichaRepository->findAll();
			
			//Conseguir todos los fichas paginadas
			$pageSize=3;

			$paginator=$FichaRepository->getPaginate1Ficha($pageSize,$page,$user);
			$totalItems = count($paginator);
			
			$pagesCount = ceil($totalItems / $pageSize);
		
			//Renderizamos la vista
			return $this->render('JAASeguroBundle:Ficha:index.html.twig', array(
				"form" => $form->createView(),
				"Tipoficha"=>$Tipoficha,
				"Ficha"     => $paginator,
				"totalItems" => $totalItems,
				"pagesCount" => $pagesCount
			));
		}
    }
		
/***************************************************************/   
    public function newAction(Request $request){
        //Entity Manager
        $em = $this->getDoctrine()->getManager();
        
        //Conseguir todas las categorias
        $TipofichaRepository=$em->getRepository("JAASeguroBundle:Tipoficha");
        $tipoficha=$TipofichaRepository->findAll();
        
        //instanciamos la entidad Ficha
        $ficha = new Ficha();

        //Creamos el formulario, asociado a la entidad
        $form = $this->createForm(new FichaType(), $ficha);
    
        //utilizamos el manejador de peticiones
        $form->handleRequest($request);

        //Si el formulario ha sido enviado
        if ($form->isSubmitted()) {
           
            //Metemos en variables los datos que llegan desde el formulario
            $title = $form->get('title')->getData();
            $description = $form->get('description')->getData();
            //$image = $form->get('image')->getData();
            $valor = $form->get('valor')->getData();
            
            //Conseguimos el objeto de la categoria
            $tipoficha_id = $form->get('tipoficha')->getData();
            $fichat=$TipofichaRepository->find($tipoficha_id);
            
            //Conseguimos el objeto del usuario identificado
            $user=$this->get('security.context')->getToken()->getUser();
            
            //Llamamos a los metodos set de la entidad y les metemos los valores del formulario
            $ficha->setTitle($title);
            $ficha->setDescription($description);
            $ficha->setImage('null');
            $ficha->setValor($valor);
            $ficha->setTipoficha($fichat);
            $ficha->setUsuario($user);
            
        }else{
            
        }
        //Si el formulario es valido tras aplicar la validacion de la entidad
        if ($form->isValid()) {
            //Subimos la foto
            $ficha->upload();            
            //Persistimos el objeto ficha
            $persist = $em->persist($ficha);
            //Guardamos en base de datos
            $flush = $em->flush();
           
            //Redirigimos a la Home
            return $this->redirect($this->generateURL('mis_fichas'));
        }else{
            //Si el formulario está enviado
            if ($form->isSubmitted()) {
                
                //Mensaje flash
                $this->session->getFlashBag()->add('new', 'Rellena correctamente el formulario');
            }
        }
		 $flush = $em->flush();
        //Renderizamos la vista
        return $this->render('JAASeguroBundle:Ficha:new.html.twig', array(
            "new_ficha_form" => $form->createView(),
            "categories"=>$tipoficha,
            "title"=>"Nueva ficha"
        ));
    }

/***************************************************************/    
    public function deleteAction($ficha){
        //Entity Manager
        $em = $this->getDoctrine()->getManager();
        
        //Repositorios de entidades a utilizar
        $fichaRepository=$em->getRepository("JAASeguroBundle:Ficha");

		$ficha=$fichaRepository->findOneBy(array("id"=>$ficha));
       
        $em->remove($ficha);
        
     
        //Persistimos el objeto ficha
        
            
        //Guardamos en base de datos
        $flush = $em->flush();
        //actualiza los cambios 
        
        return $this->redirect($this->generateURL('mis_fichas'));
    }
    
/***************************************************************/   
    public function editAction(Request $request,$ficha){
        //Entity Manager
        $em = $this->getDoctrine()->getManager();
        
        //Repositorios de entidades a utilizar
        $fichaRepository=$em->getRepository("JAASeguroBundle:Ficha");
        $tipofichaRepository=$em->getRepository("JAASeguroBundle:TipoFicha");
        
        $tipoficha=$tipofichaRepository->findAll();
        
        $fichaE = $fichaRepository->findOneBy(array("id"=>$ficha));
       
                
        $form = $this->createForm(new FichaType(), $fichaE);
    
        $form->handleRequest($request);
		
		$tituloAntiguo = $fichaE->getTitle();
		$valorAntiguo = $fichaE->getValor();
		$descripcioAntigua = $fichaE->getDescription();
        //Si el formulario ha sido enviado
        if ($form->isSubmitted()) {
            
			$tituloAntiguo = $fichaE->getTitle();
			$valorAntiguo = $fichaE->getValor();
			$descripcioAntigua = $fichaE->getDescription();
			
            //Metemos en variables los datos que llegan desde el formulario
            $title = $form->get('title')->getData();
            $description = $form->get('description')->getData();
           // $image = $form->get('image')->getData();
            $valor = $form->get('valor')->getData();
            $tipoficha_id = $form->get('tipoficha')->getData();
            $tipoficha=$tipofichaRepository->find($tipoficha_id);
            $user=$this->get('security.context')->getToken()->getUser();
            
			
		
            //Llamamos a los metodos set de la entidad y les metemos los valores del formulario
            $fichaE->setTitle($title);
            $fichaE->setDescription($description);
            $fichaE->setImage("null");
            $fichaE->setValor($valor);
            $fichaE->setTipoficha($tipoficha);
            $fichaE->setUsuario($user);
            
        }
        
        //Si el formulario es valido tras aplicar la validacion de la entidad
		// actulizar la ficha y hacer flush para actualizar los cambios en la base de datos.        
		if ($form->isValid()) {
            
            $fichaE->upload();
            $persist = $em->persist($fichaE);
            $flush = $em->flush(); 
            
			           
            $fichaRepository=$em->getRepository("JAASeguroBundle:Ficha");
            $fichaE=$fichaRepository->findOneBy(array("id"=>$fichaE));
            


            //Mensaje flash
            
            //Redirigir a la home
            return $this->redirect($this->generateURL('mis_fichas'));
        }else{
            //Si el formulario está enviado
            if ($form->isSubmitted()) {
                
                //Mensaje flash
                $this->session->getFlashBag()->add('new', 'Rellena correctamente el formulario');
            }
        }
        
        //Renderizar vista
        return $this->render('JAASeguroBundle:Ficha:new.html.twig', array(
            "new_ficha_form" => $form->createView(),
            "categories"=>$tipoficha,
            "title"=>"Editar ficha",
			"description"=>$descripcioAntigua,
			"valor"=>$valorAntiguo
        ));
    }

/*****************************************************************/
	public function searchAction(Request $request)
    {

		$em = $this->getDoctrine()->getManager();
        $ficha = new Ficha();
        $search_form = $this->createForm(new SearchType());
		// Instanciamos al usuario
		$user=$this->get('security.context')->getToken()->getUser();	
        //utilizamos el manejador de peticiones
        $search_form->handleRequest($request);

        //Si el formulario ha sido enviado
        if ($search_form->isSubmitted()) {
         // Extraemos el contenido del campo  
         
            $title = $search_form->get('title')->getData();
        }
        //Si el formulario es valido tras aplicar la validacion de la entidad
        if ($search_form->isValid()) {
		    //Repositorios de entidades a utilizar
		    $TipofichaRepository=$em->getRepository("JAASeguroBundle:Tipoficha");
		    $FichaRepository=$em->getRepository("JAASeguroBundle:Ficha");
		    
		    //Conseguir todas los tipos de ficha
		    $Tipoficha=$TipofichaRepository->findAll();
		    
		    //Conseguir todos los fichas paginadas
		    $pageSize=3;

		    $paginator=$FichaRepository->getPaginate3Ficha($pageSize,$page,$user,$title);
		    $totalItems = count($paginator);
		    $totalItems=0;
			$pagesCount = ceil($totalItems / $pageSize);
		
		    //Renderizamos la vista
		    return $this->render('JAASeguroBundle:Ficha:index.html.twig', array(
		        "Tipoficha"=>$Tipoficha,
		        "Ficha"     => $paginator,
		        "totalItems" => $totalItems,
		        "pagesCount" => $pagesCount
		    )); 
		    }
		return $this->render('JAASeguroBundle:Ficha:search.html.twig', array(  
				"search_form" => $search_form->createView()));
 }

/*****************************************************************/
 
}
