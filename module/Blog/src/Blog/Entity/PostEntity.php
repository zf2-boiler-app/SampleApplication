<?php
namespace Blog\Entity;
/**
 * @\Doctrine\ORM\Mapping\Entity(repositoryClass="\Blog\Repository\PostRepository")
 * @\Doctrine\ORM\Mapping\Table(name="posts")
 */
class PostEntity extends \Database\Entity\AbstractEntity{
    /**
     * @var int
     * @\Doctrine\ORM\Mapping\Id
     * @\Doctrine\ORM\Mapping\Column(type="integer")
     * @\Doctrine\ORM\Mapping\GeneratedValue(strategy="AUTO")
     */
    protected $post_id;

    /**
     * @var \User\Entity\UserEntity
     * @\Doctrine\ORM\Mapping\ManyToOne(targetEntity="User\Entity\UserEntity")
     * @\Doctrine\ORM\Mapping\JoinColumn(referencedColumnName="user_id")
     */
    protected $post_author;

    /**
     * @var string
     * @\Doctrine\ORM\Mapping\Column(type="text")
     */
    protected $post_title;

    /**
     * @var string
     * @\Doctrine\ORM\Mapping\Column(type="text")
     */
    protected $post_content;

    /**
     * @return int
     */
    public function getPostId(){
        return $this->post_id;
    }

    /**
     * @param \User\Entity\UserEntity $oAuthor
     * @return \Blog\Entity\PostEntity
     */
    public function setPostAuthor(\User\Entity\UserEntity $oAuthor){
    	$this->post_author = $oAuthor;
    	return $this;
    }

    /**
     * @return \User\Entity\UserEntity
     */
    public function getPostAuthor(){
		return $this->post_author;
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
     * @return string
     */
    public function getPostTitleForUrl(){
    	return join('-',array_filter(explode(' ',$this->post_title)));
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

    /**
     * Retrieve first $iStrLenght chars of post content
     * @return string
     */
    public function getPostContentPart($iStrLenght = 300){
    	$sPostContent = html_entity_decode(strip_tags(preg_replace('/<br[^>]>/','&nbsp;',$this->post_content)));
    	if(mb_strlen($sPostContent) > $iStrLenght)$sPostContent = mb_substr($sPostContent, 0,$iStrLenght).'...';
    	return $sPostContent;
    }
}