<?php


namespace App\Commands;

use App\Vk\Photo;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;


class ProcessFileCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('process:file')
            ->addArgument('id', InputArgument::REQUIRED, 'Please, enter path to file.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = fopen($input->getArgument('id'),'r');
        $vkIds = fgetcsv($file,',');
        $photoHelper = new Photo();
        foreach($vkIds as $val) {
            if($val){
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
}