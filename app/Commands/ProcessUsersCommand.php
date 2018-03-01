<?php


namespace App\Commands;

use App\Vk\Photo;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;


class ProcessUsersCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('process:users')
            ->addArgument('id', InputArgument::REQUIRED, 'Please, input vk users ids separated by comma!');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $vkIds = explode(',',$input->getArgument('id'));
        $photoHelper = new Photo();
        var_dump($vkIds);
        foreach($vkIds as $val) {
            $limit = 200;
            $offset = 0;
            $all = $photoHelper->getCountByUser($val);
            while ($offset < $all) {
                $count = $limit;
                if ($offset + $limit > $all) {
                    $count = $all - $offset;
                }
                var_dump([$val, $count, $offset]);
                $this->queue->publish('get_photos', 'photos', [$val, $count, $offset]);
                $offset += $limit;
            }
        }
    }
}