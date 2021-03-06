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
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


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
	*@Route("/editcategory/{id}", name="categoryeditor")
	*
	*/
	public function categoryEditorAction(Request $request, $id=null) {
		//add new category

		$em = $this->getDoctrine()->getManager();

		if($id !== null) {
			$category = $this->getDoctrine()->getRepository(Category::class)->find($id);
		} else { 
			$category = new Category;
		}

		$submitLabel = ($id === null) ? 'save' : 'update';
		$title = ($id === null) ? 'New Category' : 'Update Category';

		$form = $this->createFormBuilder($category)
					 ->setMethod('post')
					 ->add('name_cat', TextType::class)
					 ->add('save', SubmitType::class, ['label' => $submitLabel, 'attr' => ['class' => 'btn btn-primary']])
					 ->GetForm();

		$form->handleRequest($request);

		if($form->isSubmitted()) {
			if($id === null) {
				$em->persist($category);
			}
			$em->flush();
			return $this->redirectToRoute("home");
		}		

		if (isset($request->query->get('form')['removeCategory'])) {
			$em->remove($category);
			$em->flush();
			return $this->redirectToRoute('home');					
		}

		 return $this->render('lucky/new_category.html.twig', array('form' => $form->createView(), 'title' => $title));
	}



	// /**
	// *
	// *@Route("/editproduct", name="producteditor")
	// *
	// */
	// public function productEditorAction(Request $request) {
	// 	//add new product

	// 	$em = $this->getDoctrine()->getManager();
	// 	$repos = $this->getDoctrine()->getRepository(Category::class);
	// 	$category = $repos->findAll();

	// 		$formCat = [];
	// 		foreach($category as $cat) {
	// 			$formCat[$cat->getNameCat()] = $cat;
	// 		}

	// 	$product = new Products;

	// 	$form = $this->createFormBuilder($product)
	// 				 ->add('category', EntityType::class, array('class' => 'AppBundle:Category', 'choice_label' => 'name_cat', 'multiple'=> true))
	// 				 ->add('product_name', TextType::class)
	// 				 ->add('description', TextareaType::class)
	// 				 ->add('save', SubmitType::class, ['label' => 'save', 'attr' => ['class' => 'btn btn-primary']])
	// 				 ->GetForm();

	// 	 $form->handleRequest($request);
	// 	 	if($form->isSubmitted()) {
	// 	 		$em->persist($product);
	// 	 		$em->flush();
	// 	 	return $this->redirectToRoute("producteditor");
	// 	 	}
    	
 //    	return $this->render('lucky/new_product.html.twig', array('form' => $form->createView(), 'category' => $category, 'label' => 'New product'));

	// }


/**
 * @Route("/categorylist/{slug}", name="categorylist")
 */
public function categorylistAction($slug) {
	//render list of products in selected category

	$em = $this-> getDoctrine()->getManager();
	$repos = $this->getDoctrine()->getRepository(Category::class);	
	$category = $repos->find($slug);

	$products = $category->getProducts();

	$form = $this->createFormBuilder($category)
				 ->setAction($this->generateUrl('categoryeditor', ['id' => $slug]))
				 ->setmethod('get')
				 ->add('updateCategory', SubmitType::class, ['label' => 'Update Category', 'attr' => ['class' =>'btn btn-success']])
				 ->add('removeCategory', SubmitType::class, ['label' => 'Remove Category', 'attr' => ['class' =>'btn btn-danger']])
				 ->GetForm();

	return $this->render('lucky/categoryList.html.twig', ['category' => $category, 'products' => $products, 'form' => $form->createView()]);

}

/**
 * @Route("/productabout/{slug}", name="productabout")
 */
public function productAboutAction($slug) {
	//info about product
	
	$repos = $this->getDoctrine()->getRepository(Products::class);
	$product = $repos->find($slug);
	$category = $product->getCategory();

	$form = $this->createFormBuilder($product)
				 ->setAction($this->generateUrl('prodedit', ['id' => $slug]))
				 ->setMethod('get')
				 ->add('updateProduct', SubmitType::class, ['label' => 'Update Product', 'attr' => ['class' => 'btn btn-success']])
				 ->add('removeProduct', SubmitType::class, ['label' => 'Remove Product', 'attr' => ['class' => 'btn btn-danger']])
				 ->GetForm();
	
	return $this->render('lucky/productabout.html.twig', ['product' => $product, 'category' => $category, 'form' => $form->createView()]);

}



/** 
 * @Route("/prodedit/{id}", name = "prodedit")
 */

	public function productEditAction(Request $request, $id=null) {
		//edit exists product
					
		$em = $this->getDoctrine()->getManager();
		$repos = $this->getDoctrine()->getRepository(Category::class);
		$category = $repos->findAll();
		if($id !== null){
			$product = $this->getDoctrine()->getRepository(Products::class)->find($id);
		} else {
			$product = new Products;
		}

		$submitLabel = ($id === null) ? 'save' : 'update';
						
		$form = $this->createFormBuilder($product)
					 ->setMethod('post')
					 ->add('category', EntityType::class, array('class' => 'AppBundle:Category', 'choice_label' => 'name_cat', 'multiple'=> true))
					 ->add('product_name', TextType::class)
					 ->add('description', TextareaType::class)
					 ->add('productImage', FileType::class, ['data_class' => null])
					 ->add('save', SubmitType::class, ['label' => $submitLabel, 'attr' => ['class' => 'btn btn-success']])
				     ->GetForm();	

		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()) {
			$file = $product->getProductImage();
			$fileName = $product->getProductName() . '.' . $file->guessExtension();
			//$fileName = md5(uniqid()) . '.' . $file->guessExtension();
			$file->move($this->getParameter('img_dir'), $fileName);
			$product->setProductImage($fileName);
			if($id === null) {
				$em->persist($product);
			}
			$em->flush();
			$id = $product->getId();
			return $this->redirectToRoute('productabout', ['slug' => $id]);
		}

		if (isset($request->query->get('form')['removeProduct'])) {
			$em->remove($product);
			$em->flush();
			return $this->redirectToRoute('home');					
		}
		
		return $this->render('lucky/new_product.html.twig', array('form' => $form->createView(), 'category' => $category, 'product' => $product, 'label' => 'Edit product'));
		
		
	}

}