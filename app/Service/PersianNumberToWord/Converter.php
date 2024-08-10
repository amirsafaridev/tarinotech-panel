<?php

namespace App\Service\PersianNumberToWord;

class Converter
{
    private Dictionary $dictionary;

    public function __construct(Dictionary $dictionary)
    {
        $this->dictionary = $dictionary;
    }

    protected bool $isNegative = false;

    public function convert(int $number, bool $initial = true): string
    {
        if ($number === 0 && $initial) {
            return $this->dictionary->zero();
        }

        $number = $this->setNegativeFlagAndGetAbsolute($number);

        [$chunk, $chunkLevel, $remainder] = $this->splitLargestChunk($number);

        $convertedRemainder = $this->convertRemainder($remainder);

        $convertedTriplet = $this->convertTriplet($chunk, $chunkLevel);

        $convertedNumber = $this->dictionary->separate($convertedTriplet, $convertedRemainder);

        return $this->applySign($convertedNumber, $initial);
    }

    protected function setNegativeFlagAndGetAbsolute(int $number): float|int
    {
        if ($number < 0) {
            $this->isNegative = true;
        }

        return abs($number);
    }

    protected function splitLargestChunk(int $number): array
    {
        $stringNumber = (string) $number;
        $length = strlen($stringNumber);

        $chunkIndex = $length % 3;
        $chunkIndex = $chunkIndex !== 0 ? $chunkIndex : 3;

        $chunkLevel = $length - $chunkIndex;
        $chunk = substr($stringNumber, 0, $chunkIndex);
        $remainder = substr($stringNumber, $chunkIndex);

        return [$chunk, $chunkLevel, $remainder];
    }

    public function convertRemainder(string $remainder): string
    {
        if ($remainder === '') {
            return '';
        }

        return $this->convert((int) $remainder, false);
    }

    protected function applySign(string $convertedNumber, bool $isInitial): string
    {
        if ($this->isNegative && $isInitial) {
            return $this->dictionary->addNegative($convertedNumber);
        }

        return $convertedNumber;
    }

    protected function convertTriplet(string $chunk, $chunkLevel): string
    {
        $triplet = (int) $chunk;

        $units = $triplet % 10;
        $tens = (int) ($triplet / 10) % 10;
        $hundreds = (int) ($triplet / 100) % 10;

        $result = [];
        if ($hundreds !== 0) {
            $result[] = $this->dictionary->unit($hundreds * 100);
        }

        if ($tens === 1) {
            $result[] = $this->dictionary->unit(10 + $units);
        } else {
            if ($tens !== 0) {
                $result[] = $this->dictionary->unit($tens * 10);
            }
            if ($units !== 0) {
                $result[] = $this->dictionary->unit($units);
            }
        }

        $result = $this->dictionary->separate(...$result);

        if ($chunkLevel !== 0) {
            $result = $this->dictionary->addSuffix($result, $chunkLevel);
        }

        return $result;
    }
}
