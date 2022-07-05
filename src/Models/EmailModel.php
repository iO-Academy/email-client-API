<?php
namespace Emails\Models;

class EmailModel
{
    private \PDO $db;

    /**
     * @param \PDO $db
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function getInboxEmails(): array
    {
        $query = $this->db->query('SELECT `id`, `name`, `email`, `subject`, `date_created`, `read` FROM `messages` WHERE `deleted` <> 1 AND `sent` <> 1 ORDER BY `date_created` DESC');
        return $query->fetchAll();
    }

    public function searchEmails(string $searchQuery): array
    {
        $query = $this->db->prepare(
            'SELECT 
                        `id`, `name`, `email`, `subject`, `date_created`, `read` 
                    FROM 
                        `messages` 
                    WHERE 
                        (`name` LIKE :search OR `email` LIKE :search OR `subject` LIKE :search OR `body` LIKE :search) 
                        AND (`deleted` <> 1 AND `sent` <> 1)  
                    ORDER BY 
                        `date_created` DESC'
        );
        $query->execute([':search' => '%'.$searchQuery.'%']);
        return $query->fetchAll();
    }

    public function getEmailById(int $id): array
    {
        $query = $this->db->prepare('SELECT * FROM `messages` WHERE `id` = ?');
        $query->execute([$id]);
        return $query->fetch();
    }

    public function getReplies(int $id)
    {
        $query = $this->db->prepare('SELECT * FROM `messages` WHERE `reply_to` = ?');
        $query->execute([$id]);
        return $query->fetch();
    }

    public function createEmail(array $email): bool
    {
        $query = $this->db->prepare(
            "INSERT INTO `messages` 
                    (`name`, `email`, `subject`, `body`, `reply_to`, `sent`, `read`)
                    VALUES
	                (:name, :email, :subject, :body, :reply, 1, 1);
        ");
        return $query->execute([
            ':name' => $email['name'],
            ':email' => $email['email'],
            ':subject' => $email['subject'],
            ':body' => $email['body'],
            ':reply' => $email['reply'] ?? null
        ]);
    }
}