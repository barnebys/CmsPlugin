<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\CmsPlugin\Exception;

/**
 * @author Patryk Drapik <patryk.drapik@bitbag.pl>
 */
final class TemplateTypeNotFound extends \Exception
{
    /**
     * @param string $type
     */
    public function __construct(string $type)
    {
        parent::__construct(sprintf('Template type "%s" was not found.', $type));
    }
}