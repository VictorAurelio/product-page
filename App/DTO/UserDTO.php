<?php

namespace App\DTO;

use App\DTO\DTOInterface;

/**
 * Summary of UserDTO
 */
class UserDTO implements DTOInterface
{
    /**
     * Summary of email
     * @var
     */
    private $email;
    /**
     * Summary of password
     * @var
     */
    private $password;
    /**
     * Summary of name
     * @var
     */
    private $name;
    private $id;
    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * Summary of getEmail
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * Summary of setEmail
     * @param mixed $email
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
    /**
     * Summary of getPassword
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * Summary of setPassword
     * @param mixed $password
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
    /**
     * Summary of getName
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Summary of setName
     * @param mixed $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    /**
     * Summary of toArray
     * @return array
     */
    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
            'name' => $this->name,
        ];
    }
}
