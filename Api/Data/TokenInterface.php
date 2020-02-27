<?php


namespace Ambientia\CollectorStrongAuthentication\Api\Data;

interface TokenInterface
{
    /**
     * @param array $data
     */
    public function setData(array $data): void;

    /**
     * @return string
     */
    public function getNationalId(): ?string;
}
