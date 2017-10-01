<?php

class ModelToolImage extends Model
{

    public function resize($filename, $width, $height)
    {
        if (!is_file(DIR_IMAGE . $filename) || substr(str_replace('\\', '/', realpath(DIR_IMAGE) . DIRECTORY_SEPARATOR . $filename), 0, strlen(DIR_IMAGE . $filename)) != str_replace('\\', '/', DIR_IMAGE . $filename)) {
            return;
        }

        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        $image_old = $filename;
        $new_image = utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . (int)$width . 'x' . (int)$height . '.' . $extension;

        if (!is_file(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . $new_image) || (filemtime(DIR_IMAGE . $image_old) > filemtime(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . $new_image))) {

            ob_start();
            list($width_orig, $height_orig, $image_type) = getimagesize(DIR_IMAGE . $image_old);
            $resize_warning = ob_get_clean();
            if($resize_warning) {
                $image_old =
                $this->log->write("Cannot resize image $filename. Error: $resize_warning");
            }

            if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) {
                return DIR_IMAGE . $image_old;
            }

            if (!is_dir(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . dirname($new_image))) {
                @mkdir(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . dirname($new_image), $this->config->get('directory_permission', 0777), true);
            }

            if ($width_orig != $width || $height_orig != $height) {
                $image = new Image(DIR_IMAGE . $image_old);
                $image->resize($width, $height);
                $image->save(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . $new_image);
            } else {
                copy(DIR_IMAGE . $image_old, $this->config->get('image_cache_path') . $new_image);
            }
        }

        $new_image = str_replace(' ', '%20', $new_image); // fix bug when attach image on email (gmail.com). it is automatic changing space " " to +

        return $this->url->getImageUrl($new_image);
    }

    /**
     * Function to resize image with one given max size.
     *
     * @param $filename
     * @param $maxsize
     * @return string|void
     */
    public function onesize($filename, $maxsize)
    {
        if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
            return;
        }

        $info = pathinfo($filename);
        $extension = $info['extension'];

        $old_image = $filename;
        $new_image = substr($filename, 0, strrpos($filename, '.')) . '-max-' . $maxsize . '.' . $extension;

        if (!file_exists(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . $new_image)) || filesize(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . $new_image) < 1) {

            if (!is_dir(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . dirname($new_image))) {
                @mkdir(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . dirname($new_image), $this->config->get('directory_permission', 0777), true);
            }

            $image = new Image(DIR_IMAGE . $old_image);
            $image->onesize($maxsize);
            $image->save(DIR_PUBLIC . '/' . $this->config->get('image_cache_path') . $new_image);
        }

        return $this->url->getImageUrl($new_image);
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