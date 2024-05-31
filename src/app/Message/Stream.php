<?php

namespace Dangje\WebFramework\Message;

use Psr\Http\Message\StreamInterface;

class Stream implements StreamInterface
{
    private $stream;

    public function __construct(string $filename = '', $data = '')
    {
        if($filename == '')
            $this->stream = fopen('data://text/plain,' . $data, 'r');
        else
            $this->stream = fopen($filename, 'rwb');
    }
    public function __toString(): string
    {
        return stream_get_contents($this->stream);
    }

    public function close(): void
    {
        fclose($this->stream);
    }

    public function detach()
    {
        $this->stream = null;
    }

    public function getSize(): ?int
    {
        $stats = fstat($this->stream);
        if($stats)
            return $stats['size'];
        return null;
    }

    public function tell(): int
    {
        return ftell($this->stream);
    }

    public function eof(): bool
    {
        return feof($this->stream);
    }

    public function isSeekable(): bool
    {
        return true;
    }

    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        fseek($this->stream, $offset, $whence);
    }

    public function rewind(): void
    {
        rewind($this->stream);
    }

    public function isWritable(): bool
    {
        return fwrite($this->stream, '') !== false;
    }

    public function write(string $string): int
    {
        return fwrite($this->stream, $string);
    }

    public function isReadable(): bool
    {
        return fread($this->stream, 1) !== false;
    }

    public function read(int $length): string
    {
        return fread($this->stream, $length);
    }

    public function getContents(): string
    {
        return stream_get_contents($this->stream);
    }

    public function getMetadata(?string $key = null)
    {
        return stream_get_meta_data($this->stream);
    }
}