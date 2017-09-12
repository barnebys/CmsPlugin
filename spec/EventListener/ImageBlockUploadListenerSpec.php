<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

namespace spec\BitBag\CmsPlugin\EventListener;

use BitBag\CmsPlugin\Entity\BlockInterface;
use BitBag\CmsPlugin\Entity\BlockTranslationInterface;
use BitBag\CmsPlugin\EventListener\ImageBlockUploadListener;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Core\Uploader\ImageUploaderInterface;

/**
 * @author Mikołaj Król <mikolaj.krol@bitbag.pl>
 */
final class ImageBlockUploadListenerSpec extends ObjectBehavior
{
    public function let(ImageUploaderInterface $imageUploader)
    {
        $this->beConstructedWith($imageUploader);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ImageBlockUploadListener::class);
    }

    function it_does_not_upload_if_not_block_instance(
        ResourceControllerEvent $event,
        BlockInterface $block
    )
    {
        $event->getSubject()->willReturn(Argument::any());

        $block->getType()->shouldNotBeCalled();
    }

    function it_does_not_upload_if_not_image_Block(
        ResourceControllerEvent $event,
        BlockInterface $block
    )
    {
        $event->getSubject()->willReturn($block);
        $block->getType()->willReturn(Argument::any());

        $block->getTranslations()->shouldNotBeCalled();
    }

    function it_upload_image_for_each_translations(
        ResourceControllerEvent $event,
        BlockInterface $block,
        BlockTranslationInterface $blockTranslation,
        ImageInterface $image,
        ImageUploaderInterface $imageUploader
    )
    {
        $event->getSubject()->willReturn($block);
        $block->getType()->willReturn('image');
        $block->getTranslations()->willReturn(new ArrayCollection([$blockTranslation->getWrappedObject()]));
        $blockTranslation->getImage()->willReturn($image);
        $image->hasFile()->willReturn(true);

        $imageUploader->upload($image)->shouldBeCalled();
        $this->uploadImage($event);
    }
}
