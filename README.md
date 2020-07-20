# AppDB-as-a-Service

Ever wish if would be a simple RESTful call to setup and teardown database and users for those adhoc wordpress and other projects? Or build database setup into your ansible scripts ? Here is the little helper for you!
Driven by the need to not have root logins floating around the office, and still exposing automated self-service database creation and teardown, this projekt is a super simple script which allows RESTful-ish operation of AppDB

# What is an AppDB ??

It's those databses we all create, which has a single owner user with limited access to the similarly named database.

Example: You wordpress site has a database named `wp-site` and a restricted user, also conviniently names `wp-site` with a password which rarely, if ever, gets updated.

# Nice! how do I use it?!

It starts up simple, using environment variables for configuration:

    docker run -d -p 8080:8080 -e DB_HOST=db.localnet -e DB_USER=root DB_PASS=P@ssw0rd stixes/appdbaas 

* Create database with user:

    curl -d 'name=wp-site&password=moresecr3ts' http://localhost:8080/db

* Check database status:

    curl http://localhost:8080/db/wp-site

* List databases which has a user of the same name:

    curl http://localhost:8080/db

* Remove database and related user

    curl -XDELETE http://localhost:8080/db/wp-site

# Security

This improve security by removing the root account from the daily use. It does however expose the data in environment variables to anyone able to inspect the container.

This means that you should not be running this in an environment where database access is highly restricted, or container access is unrestricted!.

With that said, this app has been secured as well as a php application can be secured, runs as non-root and can run with read-only rootfs.
