<?php


namespace App\Entity;


class Repository
{
    private $id;
    private $name;
    private $url;
    private $created;
    private $description;

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created): void
    {
        $time = strtotime($created);
        $date = strftime ('%d.%m.%Y', $time);
        $this->created = $date;
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url): void
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }



    public function createRepositories(array $repos)
    {
        $repositories = [];
        foreach ($repos as $repo) {
            $repositories[] = self::createRepository($repo);
        }
        return $repositories;
    }

    private function createRepository($repo)
    {
        $repository = new Repository();
        $repository->setId($repo['id']);
        $repository->setName($repo['name']);
        $repository->setCreated($repo['created_at']);
        $repository->setUrl($repo['url']);
        $repository->setDescription($repo['description']);
        return $repository;

    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

}