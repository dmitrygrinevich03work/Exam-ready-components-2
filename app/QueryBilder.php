<?php

namespace App;

use Aura\SqlQuery\QueryFactory;
use PDO;

class QueryBilder
{

    private $pdo;
    private $queryFactory;

    public function __construct(PDO $pdo)
    {

        $this->pdo = $pdo;
        $this->queryFactory = new QueryFactory('mysql');

    }

    public function getOne($data, $table, $id)
    {

        $select = $this->queryFactory->newSelect();

        $select->cols($data)->from($table)->where('id = :id')->bindValue('id', $id);

        $sth = $this->pdo->prepare($select->getStatement());

        $sth->execute($select->getBindValues());

        $result = $sth->fetch(PDO::FETCH_ASSOC);

        return $result;

    }

    public function getAll($table)
    {

        $select = $this->queryFactory->newSelect();

        $select->cols(['*'])->from($table);

        $sth = $this->pdo->prepare($select->getStatement());

        $sth->execute($select->getBindValues());

        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $result;

    }

    public function insert($data, $table)
    {

        $insert = $this->queryFactory->newInsert();

        $insert->into($table)->cols($data);

        $sth = $this->pdo->prepare($insert->getStatement());

        $sth->execute($insert->getBindValues());

    }

    public function update($data, $id, $table)
    {

        $update = $this->queryFactory->newUpdate();

        $update->table($table)->cols($data)->where('id = :id')->bindValue('id', $id);

        $sth = $this->pdo->prepare($update->getStatement());

        $sth->execute($update->getBindValues());

    }

    public function delete($table, $id)
    {

        $delete = $this->queryFactory->newDelete();

        $delete->from($table)->where('id = :id')->bindValue('id', $id);

        $sth = $this->pdo->prepare($delete->getStatement());

        $sth->execute($delete->getBindValues());

    }

    public function select_email($data, $table, $email)
    {
        $select = $this->queryFactory->newSelect();

        $select->cols([$data])->from($table)->where('email = :email')->bindValue('email', $email);;

        $sth = $this->pdo->prepare($select->getStatement());

        $sth->execute($select->getBindValues());

        $result = $sth->fetch(PDO::FETCH_ASSOC);

        return $result;
    }
}