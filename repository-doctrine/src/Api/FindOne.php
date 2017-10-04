<?php

namespace Reliv\PipeRat2\RepositoryDoctrine\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindOne implements \Reliv\PipeRat2\Repository\Api\FindOne
{
    /**
     * @var GetEntityRepository
     */
    protected $getEntityRepository;

    /**
     * @param GetEntityRepository $getEntityRepository
     */
    public function __construct(
        GetEntityRepository $getEntityRepository
    ) {
        $this->getEntityRepository = $getEntityRepository;
    }

    /**
     * @param array $criteria
     * @param null  $orderBy
     * @param array $options
     *
     * @return null|object
     */
    public function __invoke(
        array $criteria = [],
        $orderBy = null,
        array $options = []
    ) {
        $repository = $this->getEntityRepository->__invoke($options);

        return $repository->findOneBy(
            $criteria,
            $orderBy
        );
    }
}
