<p align="center">
    <a href="http://cuantic.com/es/" target="_blank">
        <img src="http://cuantic.com/es/img/logo.png" height="100px">
    </a>
    <h1 align="center">WE ARE TECHNOLOGY</h1>
    <br>
</p>

REQUIREMENT
------------
- PHP 7.4

INSTALATION
------------

### Install via Docker

If you haven't [Docker](https://www.docker.com/) installed yet, you can quickly install it following the [Docker Guide](https://www.docker.com/get-started).
You also need to install [Docker Compose](https://docs.docker.com/compose/install/) for create the containers.

Creating the image.

~~~
docker-compose build
~~~

Initializing the containers.

~~~
docker-compose up -d
~~~

USAGE
-------
Use the following commands into the console.

### Access to bash
~~~
docker exec -it cuantic-test-phpfpm-1 /bin/sh
~~~

### Run the test 1
~~~
php test.php
~~~


### Run the test 2
After running the test, you'll see the results files in "output" folder.
~~~
php index.php
~~~