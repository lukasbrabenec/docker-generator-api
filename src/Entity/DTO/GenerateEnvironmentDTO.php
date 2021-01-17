<?php


namespace App\Entity\DTO;


use Symfony\Component\Validator\Constraints as Assert;

class GenerateEnvironmentDTO
{
    /**
     * @var int
     * @Assert\NotBlank()
     */
    private int $id;

    /**
     * @var string
     */
    private string $code;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private string $value;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->code,
            'value' => $this->value
        ];
    }
}