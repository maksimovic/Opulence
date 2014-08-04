<?php
/**
 * Copyright (C) 2014 David Young
 *
 * Defines a data mapper that maps domain data to and from Redis
 */
namespace RDev\Models\ORM\DataMappers;
use RDev\Models;
use RDev\Models\Databases\NoSQL\Redis;
use RDev\Models\ORM\Exceptions as ORMExceptions;

abstract class RedisDataMapper implements ICacheDataMapper
{
    /** @var Redis\RDevRedis The RDevRedis object to use for queries */
    protected $redis = null;

    /**
     * @param Redis\RDevRedis $redis The RDevRedis object to use for queries
     */
    public function __construct(Redis\RDevRedis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($id)
    {
        $entityHash = $this->getEntityHashById($id);

        if($entityHash === null || $entityHash === [])
        {
            return null;
        }

        return $this->loadEntity($entityHash);
    }

    /**
     * Gets the hash representation of an entity
     *
     * @param int|string $id The Id of the entity whose hash we're searching for
     * @return array|null The entity's hash if successful, otherwise null
     */
    abstract protected function getEntityHashById($id);

    /**
     * Loads an entity from a hash of data
     *
     * @param array $hash The hash of data to load the entity from
     * @return Models\IEntity The entity
     */
    abstract protected function loadEntity(array $hash);

    /**
     * Performs the read query for entity(ies) and returns any results
     * This assumes that the Ids for all the entities are stored in a set
     *
     * @param string $keyOfEntityIds The key that contains the Id(s) of the entities we're searching for
     * @param bool $expectSingleResult True if we're expecting a single result, otherwise false
     * @return array|mixed|null The list of entities or an individual entity if successful, otherwise null
     */
    protected function read($keyOfEntityIds, $expectSingleResult)
    {
        if($expectSingleResult)
        {
            $entityIds = $this->redis->get($keyOfEntityIds);

            if($entityIds === false)
            {
                return null;
            }

            // To be compatible with the rest of this method, we'll convert the Id to an array containing that Id
            $entityIds = [$entityIds];
        }
        else
        {
            $entityIds = $this->redis->sMembers($keyOfEntityIds);

            if(count($entityIds) == 0)
            {
                return null;
            }
        }

        $entities = [];

        // Create and store the entities associated with each Id
        foreach($entityIds as $entityId)
        {
            $hash = $this->getEntityHashById($entityId);

            if($hash === null)
            {
                return null;
            }

            $entities[] = $this->loadEntity($hash);
        }

        if($expectSingleResult)
        {
            return $entities[0];
        }
        else
        {
            return $entities;
        }
    }
} 