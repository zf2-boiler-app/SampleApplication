<?php
namespace Blog\Entity;
/**
 * @ORM\Entity(repositoryClass="Blog\Repository\PostRepository")
 * @ORM\Table(name="posts")
 */
class PostEntity{

    /**
     * @var int
     * @ORM\post_id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $post_id;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $post_title;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $post_content;

    /**
     * Constructor
     */
    public function __construct(){}

    /**
     * @return int
     */
    public function getPostId(){
        return $this->post_id;
    }

    /**
     * @param string $sContent
     * @return \Blog\Entity\PostEntity
     */
    public function setPostTitle($sTitle){
    	if(!is_scalar($sTitle))throw new \Exception('Post title expects scalar value, "'.gettype($sTitle).'" given');
    	$this->post_title = (string)$sTitle;
    	return $this;
    }

    /**
     * @return string
     */
    public function getPostTitle(){
    	return $this->post_title;
    }
    /**
     * @param string $sContent
     * @return \Blog\Entity\PostEntity
     */
    public function setPostContent($sContent){
        if(!is_scalar($sContent))throw new \Exception('Post content expects scalar value, "'.gettype($sContent).'" given');
    	$this->post_content = (string)$sContent;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostContent(){
        return $this->post_content;
    }
}