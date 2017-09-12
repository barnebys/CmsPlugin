<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

namespace BitBag\CmsPlugin\Entity;

use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Resource\Model\ToggleableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;

/**
 * @author Patryk Drapik <patryk.drapik@bitbag.pl>
 */
class Block implements BlockInterface
{
    use ToggleableTrait;
    use ProductAssociationTrait;
    use TranslatableTrait {
        __construct as protected initializeTranslationsCollection;
    }

    public function __construct()
    {
        $this->initializeTranslationsCollection();
        $this->initializeProductsCollection();
    }

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $type;

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * {@inheritdoc}
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getBlockTranslation()->getName();
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->getBlockTranslation()->setName($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        return $this->getBlockTranslation()->getContent();
    }

    /**
     * {@inheritdoc}
     */
    public function setContent($content)
    {
        $this->getBlockTranslation()->setContent($content);
    }

    /**
     * {@inheritdoc}
     */
    public function getImage()
    {
        return $this->getBlockTranslation()->getImage();
    }

    /**
     * {@inheritdoc}
     */
    public function setImage(ImageInterface $image)
    {
        $this->getBlockTranslation()->setImage($image);
    }

    /**
     * {@inheritdoc}
     */
    public function getLink()
    {
        return $this->getBlockTranslation()->getLink();
    }

    /**
     * {@inheritdoc}
     */
    public function setLink($link)
    {
        $this->getBlockTranslation()->setLink($link);
    }

    /**
     * @return BlockTranslationInterface|TranslationInterface
     */
    protected function getBlockTranslation()
    {
        return $this->getTranslation();
    }

    /**
     * {@inheritdoc}
     */
    protected function createTranslation()
    {
        return new BlockTranslation();
    }
}