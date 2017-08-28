<?php
	
namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="category")
 */

class Category {

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
 	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length = 20)
     */
	private $name_cat;

	/**
     * @ORM\OneToMany(targetEntity="Products", mappedBy="category")
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }
	
	public function getId() {
		return $this->id;
	}

	public function setNamecat($name_cat) {
		$this->name_cat = $name_cat;
		return $this;
	}

	public function getNamecat() {
		return $this->name_cat;
	}

	public function addProduct($product) {
		$this->products[] = $product;
		return $this;
	}

	public function getProducts() {
		return $this->products;
	}

	/*public function __get($name) {
		return $this->name;
	}*/
}

?>