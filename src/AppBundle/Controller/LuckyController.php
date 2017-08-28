<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Category;
use AppBundle\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;



class LuckyController extends Controller{

	/**
	*@Route("/", name="home")
	*/
	public function homeAction() {

		$repos = $this->getDoctrine()->getRepository(Category::class);
		$category = $repos->findAll();

		return $this->render('lucky/index.html.twig', ['category' => $category]);
	}



	/**
	 *
	 *@Route("/editcatalog", name="catalog_editor")
	 *
	 */
	public function editcatalogAction() {
		return $this->render('lucky/editcatalog.html.twig');
	}
	

	/**
	*
	*@Route("/editcategory", name="categoryeditor")
	*
	*/
	public function categoryEditorAction(Request $request) {

		$em = $this->getDoctrine()->getManager();
		$category = new Category;

		$form = $this->createFormBuilder($category)->add('name_cat', TextType::class)->add('save', SubmitType::class, ['label' => 'save'])->GetForm();

		$form->handleRequest($request);

			if($form->isSubmitted()) {
			
				$em->persist($category);
				$em->flush();

				return $this->redirectToRoute("home");
			}
		

		 return $this->render('lucky/new_category.html.twig', array('form' => $form->createView(),));
	}

	/**
	*
	*@Route("/editproduct", name="producteditor")
	*
	*/
	public function productEditorAction(Request $request) {

		$em = $this->getDoctrine()->getManager();
		$repos = $this->getDoctrine()->getRepository(Category::class);
		$category = $repos->findAll();

		$product = new Products;

		$form = $this->createFormBuilder($product)->add('product_name', TextType::class)->add('description', TextareaType::class)->add('save', SubmitType::class, ['label' => 'save'])->GetForm();


		 $form->handleRequest($request);

		 	if($form->isSubmitted()) {

		 		$catId = $_POST['category'];
		 		$category = $repos->find($catId);
		 		$product->setCategory($category);

		 		
		 		$em->persist($product);
		 		$em->flush();
		 	return $this->redirectToRoute("producteditor");
		 	}

    	
    	return $this->render('lucky/new_product.html.twig', array('form' => $form->createView(), 'category' => $category));


	}


/**
 * @Route("/categorylist/{slug}", name="categorylist")
 */
public function categorylistAction($slug) {

	$em = $this-> getDoctrine()->getManager();
	$repos = $this->getDoctrine()->getRepository(Category::class);	
	$category = $repos->find($slug);

	$products = $category->getProducts();
	

	return $this->render('lucky/categoryList.html.twig', ['category' => $category, 'products' => $products]);

}

/**
 * @Route("/productabout/{slug}", name="productabout")
 */
public function productAboutAction($slug) {
	
	$repos = $this->getDoctrine()->getRepository(Products::class);
	$product = $repos->find($slug);
	$category = $product->getCategory();

	return $this->render('lucky/productabout.html.twig', ['product' => $product, 'category' => $category]);

}


}