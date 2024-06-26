<?php

declare(strict_types=1);

namespace WayOfDev\App\Response;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;

final class Item
{
    #[Groups(['default', 'private'])]
    #[SerializedName('emailAddress')]
    public string $email;

    #[Groups(['default', 'private'])]
    private readonly UuidInterface $id;

    #[Groups(['default'])]
    private string $key = 'magic_number';

    #[Groups(['default'])]
    private int $value = 12;

    #[Groups(['private'])]
    private string $onlyForAdmin = 'secret';

    #[Groups(['default', 'private'])]
    private readonly ?DateTimeImmutable $dateTime;

    public function __construct(?DateTimeImmutable $dateTime = null)
    {
        $this->id = Uuid::fromString('0cd74c72-8920-4e4e-86c3-19fdd5103514');
        $this->dateTime = $dateTime;
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function key(): string
    {
        return $this->key;
    }

    public function value(): int
    {
        return $this->value;
    }

    public function dateTime(): ?DateTimeImmutable
    {
        return $this->dateTime;
    }

    public function emailAddress(): string
    {
        return $this->email;
    }

    public function onlyForAdmin(): string
    {
        return $this->onlyForAdmin;
    }
}
