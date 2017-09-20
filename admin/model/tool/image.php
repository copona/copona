<?php

class ModelToolImage extends Model
{
    public function resize($filename, $width, $height)
    {
        if (!is_file(DIR_IMAGE . $filename) || substr(str_replace('\\', '/', realpath(DIR_IMAGE . $filename)), 0, strlen(DIR_IMAGE)) != str_replace('\\', '/', DIR_IMAGE)) {
            return;
        }

        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        $image_old = $filename;
        $image_new = utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;

        if (!is_file(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . $image_new) || (filectime(DIR_IMAGE . $image_old) > filectime(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . $image_new))) {
            list($width_orig, $height_orig, $image_type) = @getimagesize(DIR_IMAGE . $image_old);

            // TODO: PDF files could have their own thumb
            if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) {
                //TODO: Carefule! Recursion!?
                return $this->resize( 'no_image.png', $width, $height);
                //return DIR_IMAGE . $image_old;
            }

            if (!is_dir(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . dirname($image_new))) {
                @mkdir(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . dirname($image_new), $this->config->get('directory_permission', 0777), true);
            }

            if ($width_orig != $width || $height_orig != $height) {
                $image = new Image(DIR_IMAGE . $image_old);
                $image->resize($width, $height);
                $image->save(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . $image_new);
            } else {
                copy(DIR_IMAGE . $image_old, DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . $image_new);
            }
        }

        return $this->url->getImageUrl($image_new);
    }
}