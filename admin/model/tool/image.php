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

    /**
     * Function to crop an image with given dimensions. What doesn/t fit will be cut off.
     *
     * @param        $filename
     * @param        $width
     * @param        $height
     * @param bool   $watermark
     * @param string $position
     * @return string|void
     */
    public function cropsize($filename, $width, $height, $watermark = false, $position = 'middle')
    {
        if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
            return;
        }

        $info = pathinfo($filename);
        $extension = $info['extension'];

        $old_image = $filename;
        $new_image = substr($filename, 0, strrpos($filename, '.')) . '-cr-' . $width . 'x' . $height . '.' . $extension;

        if (!file_exists(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . $new_image)) || filesize(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . $new_image) < 1) {

            if (!is_dir(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . dirname($new_image))) {
                @mkdir(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . dirname($new_image), $this->config->get('directory_permission', 0777), true);
            }

            $image = new Image(DIR_IMAGE . $old_image);
            $image->cropsize($width, $height);
            if ($watermark) {
                $image->addwatermark($position);
            }

            $image->save(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . $new_image);
        }

        return $this->url->getImageUrl($new_image);
    }

    public function propsize($filename, $width, $height, $type = "", $watermark = false, $position = 'middle')
    {
        if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
            return;
        }

        $info = pathinfo($filename);

        $extension = $info['extension'];

        $old_image = $filename;
        $new_image = utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-ps-' . $width . 'x' . $height . $type . '.' . $extension;

        if (!file_exists(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . $new_image)) || filesize(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . $new_image) < 1) {

            if (!is_dir(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . dirname($new_image))) {
                @mkdir(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . dirname($new_image), $this->config->get('directory_permission', 0777), true);
            }

            ob_start();
            list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $old_image);
            $resize_warning = ob_get_clean();
            if($resize_warning) {
                $this->log->write("Cannot resize image $filename. Error: $resize_warning");
            }

            if ($width_orig != $width || $height_orig != $height) {
                $image = new Image(DIR_IMAGE . $old_image);
                $image->propsize($width, $height, $type);
                if ($watermark) {
                    $image->addwatermark($position);
                }

                $image->save(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . $new_image);
            } else {
                copy(DIR_IMAGE . $old_image, $this->config->get('image_cache_path') . $new_image);
            }
        }

        return $this->url->getImageUrl($new_image);
    }

    public function downsize($filename, $width, $height, $type = "", $watermark = false, $position = 'middle')
    {
        if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
            return;
        }

        $info = pathinfo($filename);

        $extension = $info['extension'];

        $old_image = $filename;
        $new_image = utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-ds-' . $width . 'x' . $height . $type . '.' . $extension;

        if (!file_exists(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . $new_image)) || filesize(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . $new_image) < 1) {

            if (!is_dir(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . dirname($new_image))) {
                @mkdir(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . dirname($new_image), $this->config->get('directory_permission', 0777), true);
            }

            list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $old_image);

            if ($width_orig != $width || $height_orig != $height) {
                $image = new Image(DIR_IMAGE . $old_image);
                $image->downsize($width, $height, $type);
                if ($watermark) {
                    $image->addwatermark($position);
                }


                $image->save(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . $new_image);
            } else {
                copy(DIR_IMAGE . $old_image, $this->config->get('image_cache_path') . $new_image);
            }
        }

        // $new_image = implode('/', array_map('rawurlencode', explode('/', $new_image)));

        return $this->url->getImageUrl($new_image);
    }
    
}