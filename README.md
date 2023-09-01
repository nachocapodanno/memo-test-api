<h1 align="center" id="title">Memo Test Api</h1>

<p id="description">An API for a memo test game.</p>

<h2>📝 Requirements:</h2>
<p>The backend should provide a GraphQL API developed with https://lighthouse-php.com/ and using any SQL database, which provides:</p>
<b>Mutations:</b>
<ul>
<li>
Create a memo test with an id (pk), name (string), and a list of images received
</li>
<li>
Create a memo test with an id (pk), name (string), and a list of images received
</li>
<li>
Delete a memo test
<li>
Create a game session (id, memoTestId, retries, numberOfPairs, state) - state is an enum with the following values: Started, Completed
</li>
<li>
End game session
</li>
<li>
Update the game session with the number of times that a user has selected a pair (either there was a match or a miss)
</li>
</ul>
<b>Queries:</b>
<ul>
<li>
List memo tests
</li>
<li>
Get memo test by id with all its images
</li>
<li>
Get session by id
</li>
</ul>

<p><i>Create a migration script to create two memo tests examples with 4 images</i></p>

<h2>🛠️ Installation Steps:</h2>

<p>1. Run docker container in order to get php & compoer</p>

```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

<p>2. Create an .env file in project root using .env.example</p>

```
cp .env.example .env
```

<p>3. Start docker image</p>

```
./vendor/bin/sail up
```

<p>3. Run migrations (in other tab while container is up)</p>

```
./vendor/bin/sail php artisan migrate
```

<p>4. Run seeders (in other tab while container is up)</p>

```
./vendor/bin/sail php artisan db:seed
```

<h2>✅ Tests</h2>

```
./vendor/bin/sail php artisan test
```

<h2>🛝 Playground</h2>

```
http://localhost/graphiql
```

<h2>💖Like my work?</h2>

Feel free to reach me out - icapodanno@gmail.com
