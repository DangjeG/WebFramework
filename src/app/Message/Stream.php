<?php

namespace Dangje\WebFramework\Message;

use Psr\Http\Message\StreamInterface;

class Stream implements StreamInterface
{
    private $stream;

    public function __construct(string $filename = '') {

        $this->stream = fopen($filename, 'rwb');
    }
    public function __toString(): string
    {
        return stream_get_contents($this->stream);
    }

    #[\Override] public function close(): void
    {
        fclose($this->stream);
    }

    #[\Override] public function detach()
    {
        $this->stream = null;
    }

    #[\Override] public function getSize(): ?int
    {
        $stats = fstat($this->stream);
        if($stats)
            return $stats['size'];
        return null;
    }

    #[\Override] public function tell(): int
    {
        return ftell($this->stream);
    }

    #[\Override] public function eof(): bool
    {
        return feof($this->stream);
    }

    #[\Override] public function isSeekable(): bool
    {
        return true;
    }

    #[\Override] public function seek(int $offset, int $whence = SEEK_SET): void
    {
        fseek($this->stream, $offset, $whence);
    }

    #[\Override] public function rewind(): void
    {
        rewind($this->stream);
    }

    #[\Override] public function isWritable(): bool
    {
        return fwrite($this->stream, '') !== false;
    }

    #[\Override] public function write(string $string): int
    {
        return fwrite($this->stream, $string);
    }

    #[\Override] public function isReadable(): bool
    {
        return fread($this->stream, 1) !== false;
    }

    #[\Override] public function read(int $length): string
    {
        return fread($this->stream, $length);
    }

    #[\Override] public function getContents(): string
    {
        return stream_get_contents($this->stream);
    }

    #[\Override] public function getMetadata(?string $key = null)
    {
        return stream_get_meta_data($this->stream);
    }
}