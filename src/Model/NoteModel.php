<?php

declare(strict_types=1);

namespace App\Model;


use App\Exception\StorageException;
use App\Exception\NotFoundException;

use PDO;
use Throwable;

class NoteModel extends AbstractModel implements ModelInterface
{


    public function search(
        string $phrase,
        int    $pageNumber,
        int    $pageSize,
        string $sortBy,
        string $sortOrder
    ): array
    {
        return $this->findBy($phrase, $pageNumber, $pageSize, $sortBy, $sortOrder);
    }

    public function list(
        int    $pageNumber,
        int    $pageSize,
        string $sortBy,
        string $sortOrder
    ): array
    {
        return $this->findBy(null, $pageNumber, $pageSize, $sortBy, $sortOrder);
    }

    public function searchCount(string $phrase): int
    {
        $phrase = $this->conn->quote('%' . $phrase . '%', PDO::PARAM_STR);
        try {
            $idUser = $_SESSION['id'];
            $query = "SELECT count(*) as cn FROM notes3 WHERE title LIKE($phrase) and user_id = $idUser ";
            $result = $this->conn->query($query);

            $result = $result->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                throw new StorageException('Błąd przy próbie pobrania ilości notatek', 400);
            }
            return (int)$result['cn'];
        } catch (Throwable $e) {
            throw new StorageException("Nie udało się pobrać danych o liczbie notatek", 400, $e);
        }
    }

    public function count(): int
    {
        try
        {
            $idUser = $_SESSION['id'];
            $query = "SELECT count(*) as cn FROM notes3 WHERE user_id = $idUser";
            $result = $this->conn->query($query);

            $result = $result->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                throw new StorageException('Błąd przy próbie pobrania ilości notatek', 400);
            }
            return (int)$result['cn'];
        } catch (Throwable $e) {
            throw new StorageException("Nie udało się pobrać danych o liczbie notatek", 400, $e);
        }
    }

    public function get(int $id): array
    {
        try {
            $idUser = $_SESSION['id'];
            $query = "SELECT * FROM notes3 WHERE id = $id and user_id=$idUser";
            $result = $this->conn->query($query);
            $note = $result->fetch(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            dump($e);
            throw new StorageException('Nie udało się pobrać notatki', 400, $e);
        }
        if (!$note) {
            throw new NotFoundException("Notatka o id: $id nie istnieje");
        }

        return $note;
    }

    public function create(array $data): void
    {
        try {
            $idUser = $_SESSION['id'];
            $title = $this->conn->quote($data['title']);
            $description = $this->conn->quote($data['description']);
            $created = $this->conn->quote(date('Y-m-d H:i:s'));

            $query = "INSERT INTO notes3(user_id, title, description, created)
        VALUES($idUser, $title, $description, $created)
      ";

            $this->conn->exec($query);
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się utworzyć nowej notatki', 400, $e);
        }
    }

    public function edit(int $id, array $data): void
    {
        try {
            $idUser = $_SESSION['id'];
            $title = $this->conn->quote($data['title']);
            $description = $this->conn->quote($data['description']);

            $query = "UPDATE notes3 SET title = $title, description=$description WHERE id = $id and user_id = $idUser";
            $this->conn->exec($query);
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się zaktualizować notatki', 400, $e);
        }
    }

    public function delete(int $id): void
    {
        try {
            $idUser = $_SESSION['id'];
            $query = "DELETE FROM notes3 WHERE id = $id and user_id=$idUser LIMIT 1 ";
            $this->conn->exec($query);
        } catch (Throwable $e) {
            throw new StorageException("Nie udało się usunąć notatki", 400, $e);
        }
    }

    private function findBy(
        ?string $phrase,
        int     $pageNumber,
        int     $pageSize,
        string  $sortBy,
        string  $sortOrder
    ): array
    {

        try {
            $idUser = $_SESSION['id'];

            $limit = $pageSize;
            $offset = ($pageNumber - 1) * $pageSize;

            if (!in_array($sortBy, ['created', 'title'])) {
                $sortBy = 'title';
            }

            if (!in_array($sortOrder, ['asc', 'desc'])) {
                $sortBy = 'desc';
            }

            $wherePart = '';

            if ($phrase) {
                $phrase = $this->conn->quote('%' . $phrase . '%', PDO::PARAM_STR);
                $wherePart = "and title LIKE ($phrase)";
            }

            $query = "SELECT * FROM notes3 WHERE user_id = $idUser $wherePart ORDER BY $sortBy $sortOrder LIMIT $offset, $limit";
            $result = $this->conn->query($query);

            $notes = $result->fetchAll(PDO::FETCH_ASSOC);

            return $notes;
        } catch (Throwable $e) {
            throw new StorageException("Nie udało się pobrać notatek", 400, $e);
        }
        return [];
    }
}
