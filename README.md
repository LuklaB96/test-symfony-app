# Quick installation guide

1. Clone the repo:
```
git clone https://github.com/LuklaB96/test-symfony-app.git
```

2. Run composer installation command:
```
composer install
```

3. Create a local database using doctrine (Configured for SQLite):
```
php bin/console doctrine:database:create
```

4. Run all migrations to make database tables:
```
php bin/console doctrine:migrations:migrate 
```

Now run the server

There is a command to fill the database with records:
```
php bin/console app:create-posts-data
```

Avaible routes:
```
http://..host../login
http://..host../register
http://..host../lista - dashboard avaible after login with current posts list, every post has delete button, additionally there is a generate posts button. 
http://..host../posts - get json with all posts avaible in database
http://..host../posts/delete/{id} - delete any post from 1-100 range (current known https://jsonplaceholder.typicode.com/posts api limitation)
http://..host../posts/generate - fill database with posts data that are missing, avaible after login
```
   
