<?php
declare(strict_types = 1);

namespace App\Domain\Repository;

use App\Domain\Entity\Interfaces\TrickInterface;
use App\Domain\Entity\Trick;
use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class TrickRepository
 * @package App\Domain\Repository
 */
class TrickRepository extends ServiceEntityRepository implements TrickRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Trick::class);
    }


    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        $sql = '
          SELECT t.id, t.title, t.slug, t.description,p.path, p.file_name, p.alt,
              group_concat(DISTINCT g.name) as groups_names
          FROM st_trick as t
          INNER JOIN st_tricks_groups as tg ON t.id = tg.trick_id
          INNER JOIN st_group as g ON g.id = tg.group_id
          INNER JOIN st_picture as p ON p.id = t.main_picture_id
          GROUP BY t.id
          ';

        $conn = $this->_em->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $tricks = $stmt->fetchAll();


        foreach ($tricks as $key => $trick) {
            $trick['groups'] = explode(',', $trick['groups_names']);

            $trick['MainPicture'] = [
                'path' => $trick['path'],
                'filename' => $trick['file_name'],
                'webPath' => $trick['path']. '/' .$trick['file_name'] ,
                'alt' => $trick['alt']
            ];

            unset($trick['path']);
            unset($trick['file_name']);
            unset($trick['alt']);
            unset($trick['groups_names']);

            $tricks[$key] = $trick;
        }

        return $tricks;
    }

    /**
     * {@inheritdoc}
     */
    public function save(TrickInterface $trick): void
    {
        $this->_em->persist($trick);
        $this->_em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function remove(TrickInterface $trick): void
    {
        $this->_em->remove($trick);
        $this->_em->flush();
    }
}
