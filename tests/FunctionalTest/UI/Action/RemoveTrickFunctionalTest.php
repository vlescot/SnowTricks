<?php
declare(strict_types=1);

namespace App\Tests\FunctionalTest\UI\Action;

use App\Domain\Entity\Interfaces\TrickInterface;
use App\Domain\Entity\Trick;
use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class RemoveTrickFunctionalTest extends WebTestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }


    // TODO
    // An exception occurred while executing
    // 'UPDATE st_user SET password = ?, token = ? WHERE id = ?' with params [null, null, "edc6efeb-2290-421b-9e5f-5a9a2e2d5452"]:\n
    // SQLSTATE[23000]: Integrity constraint violation: 1048 Le champ 'password' ne peut être vide (null)
//    public function testRemoveTrick()
//    {
//        $client = static::createClient([], [
//            'PHP_AUTH_USER' => 'root',
//            'PHP_AUTH_PW'   => 'root',
//        ]);
//
//
//        $trick = $this->entityManager
//            ->getRepository(Trick::class)
//            ->findOneBy(['title' => 'Spins'])
//        ;
//
//        static::assertInstanceOf(TrickInterface::class, $trick);
//
//
//        $c = $client->request('POST', '/figure/supprimer', ['id' => $trick->getId()]);
//
//        dump($c->filter('.exception-message ')->text());
//
//        $trick = $this->entityManager
//            ->getRepository(Trick::class)
//            ->findOneBy(['title' => 'Spins'])
//        ;
//
//        $r= new \ReflectionClass($trick);
//        dump($r->getShortName());
//
//    //        static::assertNull($trick);
//    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
