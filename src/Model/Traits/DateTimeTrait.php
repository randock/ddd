<?php

declare(strict_types=1);

namespace Randock\Ddd\Model\Traits;

trait DateTimeTrait
{
    /**
     * @var \DateTimeImmutable
     */
    protected $createdAt;

    /**
     * @var \DateTimeImmutable|null
     */
    protected $updatedAt;

    /**
     * @var \DateTimeImmutable|null
     */
    protected $deletedAt;

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeImmutable $updatedAt
     *
     * @return self
     */
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    /**
     * @param \DateTimeImmutable $deletedAt
     *
     * @return self
     */
    public function setDeletedAt(\DateTimeImmutable $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }
}
