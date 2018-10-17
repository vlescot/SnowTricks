<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\Service\Image;

use App\App\Image\ImageUploadWarmer;
use App\App\Image\Interfaces\ImageUploadWarmerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class ImageUploaderWarmerTest extends KernelTestCase
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $publicFolder;

    /**
     * @var string
     */
    private $trickFolder;

    /**
     * @var string
     */
    private $userFolder;

    /**
     * @var ImageUploadWarmerInterface
     */
    private $imageWarmer;

    public function setUp()
    {
        static::bootKernel();

        $this->filesystem = new Filesystem();
        $this->publicFolder = $this::$kernel->getRootDir() . '/../public';
        $this->trickFolder = '/image/snowtrick';
        $this->userFolder = '/image/user';

        $this->imageWarmer = new ImageUploadWarmer(
            $this->filesystem,
            $this->publicFolder,
            $this->trickFolder,
            $this->userFolder
        );
    }

    public function testConstruct()
    {
        static::assertInstanceOf(ImageUploadWarmerInterface::class, $this->imageWarmer);
    }

    public function testInitializeCreateDirectory()
    {
        $this->imageWarmer->initialize('trick', 'New Trick');

        $newDirPath = $this->publicFolder . $this->trickFolder;
        $newDirPath .= '/' . strtolower(str_replace(' ', '_', 'New Trick'));

        static::assertTrue($this->filesystem->exists($newDirPath));
    }

    public function testGenerateInfoProvideGoodValues()
    {
        $file = $this->createMock(UploadedFile::class);
        $file->method('guessExtension')->willReturn('jpg');

        $imageWarmer = new ImageUploadWarmer(
            $this->filesystem,
            $this->publicFolder,
            $this->trickFolder,
            $this->userFolder
        );

        $imageWarmer->initialize('trick', 'New Trick');

        $fileInfo = $imageWarmer->generateImageInfo($file);

        static::assertInternalType('string', $fileInfo['filename']);
        static::assertInternalType('string', $fileInfo['path']);
        static::assertInternalType('string', $fileInfo['alt']);
    }

    public function testGetUpdateInfoProvideGoodValues()
    {
        $imageWarmer = new ImageUploadWarmer(
            $this->filesystem,
            $this->publicFolder,
            $this->trickFolder,
            $this->userFolder
        );

        $imageWarmer->initialize('trick', 'New Trick');

        $fileInfo = $imageWarmer->getUpdateImageInfo();

        static::assertInternalType('string', $fileInfo['path']);
        static::assertInternalType('string', $fileInfo['alt']);
    }
}
