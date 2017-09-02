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
     * @ORM\ManyToMany(targetEntity="Products", mappedBy="category")    
     */
    private $products;

       /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nameCat
     *
     * @param string $nameCat
     *
     * @return Category
     */
    public function setNameCat($nameCat)
    {
        $this->name_cat = $nameCat;

        return $this;
    }

    /**
     * Get nameCat
     *
     * @return string
     */
    public function getNameCat()
    {
        return $this->name_cat;
    }

    /**
     * Add product
     *
     * @param \AppBundle\Entity\Products $product
     *
     * @return Category
     */
    public function addProduct(\AppBundle\Entity\Products $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \AppBundle\Entity\Products $product
     */
    public function removeProduct(\AppBundle\Entity\Products $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }
}
