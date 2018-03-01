<?php


namespace App\Commands;

use App\Vk\Photo;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;


class PhotosParserCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('photos:parser');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {



        $this->queue->consume('get_photos', 'photos', function ($data) {

            $photoHelper = new Photo();
            $photosData = $photoHelper->getByUser($data[0],$data[1],$data[2]);

            $this->vkImagesModel->insertImages($photosData,$data[0]);
        });


        // [$vkId, $count, $offset]
    }
}