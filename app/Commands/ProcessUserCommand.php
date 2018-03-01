<?php


namespace App\Commands;

use App\Vk\Photo;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;


class ProcessUserCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('process:user')
            ->addArgument('id', InputArgument::REQUIRED, 'Please, input vk user id!');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $vkId = $input->getArgument('id');
        $photoHelper = new Photo();
        $all = $photoHelper->getCountByUser($vkId);
        $limit = 200;
        $offset = 0;
        while ($offset < $all) {
            $count = $limit;
            if ($offset + $limit > $all) {
                $count = $all - $offset;
            }
            var_dump([$vkId, $count, $offset]);
            $this->queue->publish('get_photos', 'photos', [$vkId, $count, $offset]);

            $offset += $limit;
        }
    }
}