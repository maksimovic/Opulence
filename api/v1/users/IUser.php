<?php
/**
 * Copyright (C) 2014 David Young
 *
 * Defines the user interface
 */
namespace RamODev\API\V1\Users;

interface IUser
{
    /**
     * @return string
     */
    public function getEmail();

    /**
     * @return string
     */
    public function getFirstName();

    /**
     * @return string
     */
    public function getHashedPassword();

    /**
     * @return int
     */
    public function getID();

    /**
     * @return string
     */
    public function getLastName();

    /**
     * @return string
     */
    public function getUsername();

    /**
     * @param string $email
     */
    public function setEmail($email);

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName);

    /**
     * @param string $password
     */
    public function setHashedPassword($password);

    /**
     * @param int $id
     */
    public function setID($id);

    /**
     * @param string $lastName
     */
    public function setLastName($lastName);

    /**
     * @param string $username
     */
    public function setUsername($username);
} 