<?php
declare(strict_types=1);

namespace App\Tests\Domain\DTO;

use PHPUnit\Framework\TestCase;
use App\Domain\DTO\VideoDTO;

class VideoDTOTest extends TestCase
{
    /**
     * @var VideoDTO
     */
    private $dto;

    public function setUp()
    {
        $this->dto = new VideoDTO(
            '<iframe width="560" height="315" src="https://www.youtube.com/embed/Qyclqo_AV2M" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>'
        );
    }

    public function testVideoDTOAttributeMustBeAString()
    {
        static::assertInternalType('string', $this->dto->iFrame);
    }
}
