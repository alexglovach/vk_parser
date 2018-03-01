<?php


namespace App\Commands;


use Symfony\Component\Console\Command\Command;

/**
 * @property \App\Helpers\Queue queue
 * @property \App\Models\VkImagesModel vkImagesModel
 */
class BaseCommand extends Command
{
    private $container;

    public function setContainer($container)
    {
        $this->container = $container;
        return $this;
    }

    public function __get($name)
    {
        if ($this->container->has($name)) {
            return $this->container->get($name);
        }
    }
}