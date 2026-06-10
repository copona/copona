<?php

namespace Session;

class File extends \SessionHandler
{
    public function create_sid(): string
    {
        return parent::create_sid();
    }

    public function open(string $path, string $name): bool
    {
        return true;
    }

    public function close(): bool
    {
        return true;
    }

    public function read(string $id): string|false
    {
        $file = session_save_path() . '/sess_' . $id;

        if (is_file($file)) {
            $handle = fopen($file, 'r');

            flock($handle, LOCK_SH);

            $data = fread($handle, filesize($file));

            flock($handle, LOCK_UN);

            fclose($handle);

            return $data;
        }

        return false;
    }

    public function write(string $id, string $data): bool
    {
        $file = session_save_path() . '/sess_' . $id;

        $handle = fopen($file, 'w');

        flock($handle, LOCK_EX);

        fwrite($handle, $data);

        fflush($handle);

        flock($handle, LOCK_UN);

        fclose($handle);

        return true;
    }

    public function destroy(string $id): bool
    {
        $file = session_save_path() . '/sess_' . $id;

        if (is_file($file)) {
            unlink($file);
        }

        return true;
    }

    public function gc(int $max_lifetime): int|false
    {
        return parent::gc($max_lifetime);
    }
}
