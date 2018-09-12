<?php
declare(strict_types=1);

namespace App\Domain\Entity\Interfaces;

use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

interface TrickInterface
{
    /**
     * @param string $title
     * @param string $description
     * @param PictureInterface $mainPicture
     * @param array|null $pictures
     * @param array|null $videos
     * @param \ArrayAccess|null $groups
     */
    public function update(
        string $title,
        string $description,
        PictureInterface $mainPicture,
        array $pictures = null,
        array $videos = null,
        \ArrayAccess $groups = null
    ): void;


    /**
     * @param CommentInterface $comment
     */
    public function addComment(CommentInterface $comment): void;

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface;

    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @return string
     */
    public function getSlug(): string;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @return int
     */
    public function getCreatedAt(): int;

    /**
     * @return int
     */
    public function getUpdatedAt(): int;

    /**
     * @return UserInterface
     */
    public function getAuthor(): UserInterface;

    /**
     * @return PictureInterface
     */
    public function getMainPicture(): PictureInterface;

    /**
     * @return \ArrayAccess
     */
    public function getComments(): \ArrayAccess;

    /**
     * @return \ArrayAccess
     */
    public function getPictures(): \ArrayAccess;

    /**
     * @return \ArrayAccess
     */
    public function getVideos(): \ArrayAccess;

    /**
     * @return \ArrayAccess
     */
    public function getGroups(): \ArrayAccess;
}
