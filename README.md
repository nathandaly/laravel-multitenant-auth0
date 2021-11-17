# User API

## Installation & setup

---
**NOTE**

This project depends on the host running a MacOS, Linux or WSL2 environment with PHP.

---

#### Clone the repository and change into the project directory
```
git clone git@ssh.dev.azure.com:v3/juniper-education/Pupil%20Asset/prototype-user-api
cd user-api
```
Copy the example `.env`
```
cp .env.example .env
```
#### Installing dependencies
```
composer install
```

---
**NOTE**

If your host system is not running PHP 8 then you will need to add `--ignore-platform-reqs`. to the composer command.

---

Once the PHP and node dependencies have been resolved you can run the [Sail](https://laravel.com/docs/8.x/sail#introduction) containers.

```
export APP_PORT=8080 && export SAIL_DEBUG=true && ./vendor/bin/sail up -d
```
This command will normally take few minutes and will download and build the required containers.

### Database migration and seeding

The containers do not contain a database, you will have to either use `host.docker.internal` as the database host to allow the docker
container to "talk" to your host system and use an application such as [DBngin](https://dbngin.com/) or manually add a service definition to meet your requirement.

### Host entry

You will need to add a new entry to your hosts file for the SSL certificate to validate.

```
sudo vim /etc/hosts
```

Add the following line:

`127.0.0.1 pupilasset.test`

### :tada: Up and running :tada:
That should be all that is necessary for the application to be usable.
Just head over to `localhost::8080` and login with the following credentials:

### Testing

Tests can be found in the `tests/` directory and can be run with the following command:

##### Just tests
`sail artisan test`

##### Tests with coverage (min 80%)
`sail composer test`

### Todos:

If I had more time:
- [ ] Add a database seeder for a test user on director and any "shards" listed in the `shards.env` file.
- [ ] Add a unit test for the DbContext switcher trait.
- [ ] Check additional user grants and permission not just the user's relationship to the school.
