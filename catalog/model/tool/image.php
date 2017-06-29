<?php
class ModelToolImage extends Model {

    public function resize($filename, $width, $height) {


        if (!is_file(DIR_IMAGE . $filename) || substr(str_replace('\\', '/',realpath(DIR_IMAGE) . DIRECTORY_SEPARATOR . $filename), 0, strlen(DIR_IMAGE . $filename)) != str_replace('\\', '/', DIR_IMAGE . $filename)) {
            return;
        }

        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        $image_old = $filename;
        $image_new = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . (int)$width . 'x' . (int)$height . '.' . $extension;

        if (!is_file(DIR_IMAGE . $image_new) || (filectime(DIR_IMAGE . $image_old) > filectime(DIR_IMAGE . $image_new))) {
            list($width_orig, $height_orig, $image_type) = getimagesize(DIR_IMAGE . $image_old);

            if (!in_array($image_type, array( IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF ))) {
                return DIR_IMAGE . $image_old;
            }

            $path = '';

            $directories = explode('/', dirname($image_new));

            foreach ($directories as $directory) {
                $path = $path . '/' . $directory;

                if (!is_dir(DIR_IMAGE . $path)) {
                    @mkdir(DIR_IMAGE . $path, 0777);
                }
            }

            if ($width_orig != $width || $height_orig != $height) {
                $image = new Image(DIR_IMAGE . $image_old);
                $image->resize($width, $height);
                $image->save(DIR_IMAGE . $image_new);
            } else {
                copy(DIR_IMAGE . $image_old, DIR_IMAGE . $image_new);
            }
        }

        $image_new = str_replace(' ', '%20', $image_new); // fix bug when attach image on email (gmail.com). it is automatic changing space " " to +

        if ($this->request->server['HTTPS']) {
            return $this->config->get('config_ssl') . 'image/' . $image_new;
        } else {
            return $this->config->get('config_url') . 'image/' . $image_new;
        }
    }

    // Function to resize image with one given max size.
    public function onesize($filename, $maxsize) {

        if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
            return;
        }

        $info = pathinfo($filename);
        $extension = $info['extension'];

        $old_image = $filename;
        $new_image = 'cache/' . substr($filename, 0, strrpos($filename, '.')) . '-max-' . $maxsize . '.' . $extension;

        if (!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image)) || filesize(DIR_IMAGE . $new_image) < 1) {
            $path = '';

            $directories = explode('/', dirname(str_replace('../', '', $new_image)));

            foreach ($directories as $directory) {
                $path = $path . '/' . $directory;

                if (!file_exists(DIR_IMAGE . $path)) {
                    @mkdir(DIR_IMAGE . $path, 0777);
                }
            }

            $image = new Image(DIR_IMAGE . $old_image);
            $image->onesize($maxsize);
            $image->save(DIR_IMAGE . $new_image);
        }

        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {

            if (defined('HTTPS_IMAGE')) {
                return HTTPS_IMAGE . 'image/' . implode('/', array_map('rawurlencode', explode('/', $new_image)));
            } else {
                return $this->config->get('config_ssl') . 'image/' . implode('/', array_map('rawurlencode', explode('/', $new_image)));
                ;
            }
        } else {
            if (defined('HTTP_IMAGE')) {
                return HTTP_IMAGE . 'image/' . implode('/', array_map('rawurlencode', explode('/', $new_image)));
            } else {
                return $this->config->get('config_url') . 'image/' . implode('/', array_map('rawurlencode', explode('/', $new_image)));
                ;
            }
        }
    }

    // Function to crop an image with given dimensions. What doesn/t fit will be cut off.
    public function cropsize($filename, $width, $height, $watermark = false, $position = 'middle') {

        if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
            return;
        }

        $info = pathinfo($filename);
        $extension = $info['extension'];

        $old_image = $filename;
        $new_image = 'cache/' . substr($filename, 0, strrpos($filename, '.')) . '-cr-' . $width . 'x' . $height . '.' . $extension;

        if (!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image)) || filesize(DIR_IMAGE . $new_image) < 1) {
            $path = '';

            $directories = explode('/', dirname(str_replace('../', '', $new_image)));

            foreach ($directories as $directory) {
                $path = $path . '/' . $directory;

                if (!file_exists(DIR_IMAGE . $path)) {
                    @mkdir(DIR_IMAGE . $path, 0777);
                }
            }

            $image = new Image(DIR_IMAGE . $old_image);
            $image->cropsize($width, $height);
            if ($watermark) {
                $image->addwatermark($position);
            }

            $image->save(DIR_IMAGE . $new_image);
        }


        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {

            if (defined('HTTPS_IMAGE')) {
                return HTTPS_IMAGE . 'image/' . implode('/', array_map('rawurlencode', explode('/', $new_image)));
            } else {
                return $this->config->get('config_ssl') . 'image/' . implode('/', array_map('rawurlencode', explode('/', $new_image)));
                ;
            }
        } else {
            if (defined('HTTP_IMAGE')) {
                return HTTP_IMAGE . 'image/' . implode('/', array_map('rawurlencode', explode('/', $new_image)));
            } else {
                return $this->config->get('config_url') . 'image/' . implode('/', array_map('rawurlencode', explode('/', $new_image)));
                ;
            }
        }
    }

    public function propsize($filename, $width, $height, $type = "", $watermark = false, $position = 'middle') {


        if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
            return;
        }

        $info = pathinfo($filename);

        $extension = $info['extension'];

        $old_image = $filename;
        $new_image = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-ps-' . $width . 'x' . $height . $type . '.' . $extension;

        if (!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image)) || filesize(DIR_IMAGE . $new_image) < 1) {
            $path = '';

            $directories = explode('/', dirname(str_replace('../', '', $new_image)));

            foreach ($directories as $directory) {
                $path = $path . '/' . $directory;

                if (!file_exists(DIR_IMAGE . $path)) {
                    @mkdir(DIR_IMAGE . $path, 0777);
                }
            }

            list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $old_image);

            if ($width_orig != $width || $height_orig != $height) {
                $image = new Image(DIR_IMAGE . $old_image);
                $image->propsize($width, $height, $type);
                if ($watermark) {
                    $image->addwatermark($position);
                }


                $image->save(DIR_IMAGE . $new_image);
            } else {
                copy(DIR_IMAGE . $old_image, DIR_IMAGE . $new_image);
            }
        }



        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {

            if (defined('HTTPS_IMAGE')) {
                return HTTPS_IMAGE . 'image/' . implode('/', array_map('rawurlencode', explode('/', $new_image)));
            } else {
                return $this->config->get('config_ssl') . 'image/' . implode('/', array_map('rawurlencode', explode('/', $new_image)));
                ;
            }
        } else {
            if (defined('HTTP_IMAGE')) {
                return HTTP_IMAGE . 'image/' . implode('/', array_map('rawurlencode', explode('/', $new_image)));
            } else {
                return $this->config->get('config_url') . 'image/' . implode('/', array_map('rawurlencode', explode('/', $new_image)));
                ;
            }
        }
    }

    public function downsize($filename, $width, $height, $type = "", $watermark = false, $position = 'middle') {


        if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
            //return;
        }

        $info = pathinfo($filename);

        $extension = $info['extension'];

        $old_image = $filename;
        $new_image = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-ps-' . $width . 'x' . $height . $type . '.' . $extension;

        if (!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image)) || filesize(DIR_IMAGE . $new_image) < 1) {
            $path = '';

            $directories = explode('/', dirname(str_replace('../', '', $new_image)));

            foreach ($directories as $directory) {
                $path = $path . '/' . $directory;

                if (!file_exists(DIR_IMAGE . $path)) {
                    @mkdir(DIR_IMAGE . $path, 0777);
                }
            }

            list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $old_image);

            if ($width_orig != $width || $height_orig != $height) {
                $image = new Image(DIR_IMAGE . $old_image);
                $image->downsize($width, $height, $type);
                if ($watermark) {
                    $image->addwatermark($position);
                }


                $image->save(DIR_IMAGE . $new_image);
            } else {
                copy(DIR_IMAGE . $old_image, DIR_IMAGE . $new_image);
            }
        }



        $new_image = implode('/', array_map('rawurlencode', explode('/', $new_image)));

        //2 > 1 - .XML replace būtu savietojams ar šo.

        if (2 > 1 && isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {

            if (defined('HTTPS_IMAGE')) {
                return HTTPS_IMAGE . 'image/' . $new_image;
            } else {
                if (defined('HTTPS_IMAGE')) {
                    return HTTPS_IMAGE . 'image/' . implode('/', array_map('rawurlencode', explode('/', $new_image)));
                } else {
                    return $this->config->get('config_ssl') . 'image/' . implode('/', array_map('rawurlencode', explode('/', $new_image)));
                    ;
                }
            }
        } else {
            if (defined('HTTP_IMAGE')) {
                return HTTP_IMAGE . implode('/', array_map('rawurlencode', explode('/', $new_image)));
            } else {
                if (defined('HTTP_IMAGE')) {
                    return HTTP_IMAGE . 'image/' . implode('/', array_map('rawurlencode', explode('/', $new_image)));
                } else {
                    return $this->config->get('config_url') . 'image/' . implode('/', array_map('rawurlencode', explode('/', $new_image)));
                    ;
                }
            }
        }
    }

}