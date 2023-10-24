<?php

namespace services\connection;

use DomainException;
use Connection;

final class UserReaderRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getUserById(int $userId): array
    {
        $query = $this->connection->select()->from('utilisateur');

        $query->columns(['id_user','email_user']);
        $query->where('id_user', '=', $userId);

        $row = $query->execute()->fetch() ?: [];

        if(!$row) {
            throw new DomainException(sprintf('User not found: %s', $userId));
        }

        return $row;
    }

}