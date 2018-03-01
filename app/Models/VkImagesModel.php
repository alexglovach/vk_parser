<?php

namespace App\Models;

class VkImagesModel extends BaseModel
{
    const TABLE = 'vk_images';

    public function insertImages($photos, $ownerId)
    {
        foreach ($photos['response'] as $key => $value) {

            if (gettype($value) != 'integer') {
                $val = $value['src_big'];
                var_dump($val);
            $image_hash = crc32($val);
            $found = $this->selectFirst([['hash', '=', $image_hash]]);
            if (!$found) {
                $this->insert([
                    'vk_id' => $ownerId,
                    'image_src' => $val,
                    'hash' => $image_hash,
                ]);
                echo "hash OK - added to db\n";
            } else {
                echo "hash used in db \n";
            }
            }
        }
        echo "added to database \n";
    }
}