<?php

namespace Session;

class Native extends \SessionHandler {

    public function create_sid(): string {
        return parent::create_sid();
    }

    public function open(string $path, string $name): bool {
        return parent::open($path, $name);
    }

    public function close(): bool {
        return parent::close();
    }

    public function read(string $id): string|false {
        return parent::read($id);
    }

    public function write(string $id, string $data): bool {
        return parent::write($id, $data);
    }

    public function destroy(string $id): bool {
        return parent::destroy($id);
    }

    public function gc(int $max_lifetime): int|false {
        return parent::gc($max_lifetime);
    }

}