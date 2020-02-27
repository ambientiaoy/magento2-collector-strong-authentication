<?php


namespace Ambientia\CollectorStrongAuthentication\Model;


use Ambientia\CollectorStrongAuthentication\Api\Data\TokenInterface;

class Token implements TokenInterface
{
    /** @var array $data */
    private $data;

    public function getNationalId(): ?string
    {
        return isset($this->data['national_id']) ? $this->data['national_id'] : null;
    }

    /**
     * @inheritDoc
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }
}
