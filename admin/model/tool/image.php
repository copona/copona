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
        $image_new = 'image/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;

        if (!is_file(DIR_CACHE . $image_new) || (filectime(DIR_IMAGE . $image_old) > filectime(DIR_CACHE . $image_new))) {
            list($width_orig, $height_orig, $image_type) = getimagesize(DIR_IMAGE . $image_old);

            if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) {
                return DIR_IMAGE . $image_old;
            }

            $path = '';

            $directories = explode('/', dirname($image_new));

            foreach ($directories as $directory) {
                $path = $path . '/' . $directory;

                if (!is_dir(DIR_CACHE . $path)) {
                    @mkdir(DIR_CACHE . $path, $this->config->get('directory_permission', '0777'));
                }
            }

            if ($width_orig != $width || $height_orig != $height) {
                $image = new Image(DIR_IMAGE . $image_old);
                $image->resize($width, $height);
                $image->save(DIR_CACHE . $image_new);
            } else {
                copy(DIR_IMAGE . $image_old, DIR_CACHE . $image_new);
            }
        }

        if ($this->request->server['HTTPS']) {
            return HTTPS_CATALOG . PATH_CACHE . $image_new;
        } else {
            return HTTP_CATALOG . PATH_CACHE . $image_new;
        }
    }
}