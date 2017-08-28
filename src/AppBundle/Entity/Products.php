<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="products")
 */

class Products {

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id	 
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length = 40)
	 */
	private $product_name;

	/**
	 * @ORM\Column(type="text")
	 */
	private $description;

	/**
	 * @ORM\ManyToOne(targetEntity = "Category", inversedBy="products")
	 * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
	 */
	private $category;

	public function getId() {
		return $this->id;
	}

	public function setProductname($prod_name) {
		$this->product_name = $prod_name;
		return $this;
	}

	public function getProductname() {
		return $this->product_name;
	}

	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}

	public function getDescription() {
		return $this->description;
	}

	public function setCategory($category) {
		$this->category = $category;
		return $this;
	}

	public function getCategory() {
		return $this->category;
	}

}


?>