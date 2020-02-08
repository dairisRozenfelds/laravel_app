# Laravel lietotne

Lietotne ir būvēta uz docker un docker-compose. Tā tika testēta uz:
- docker v19.03.5
- docker-compose v1.24.0

## Instalācija

Lai uzbūvētu docker image un tās palaistu, tiek izmantots docker-compose.

1. Jāaiziet uz projekta saknes mapi
2. Jāielādē composer atkarības ```docker run --rm -v $(pwd)/src:/app composer install```
3. Visas projekta mapes tiesības jāpiešķir jūsu lietotājam ```sudo chown -R $USER:$USER .```
4. Jāizpilda komanda, kas uzbūvēs un palaidīs nepieciešamos konteinerus ```docker-compose up --build```
5. Jāpārkopē .env.local fails kā .env ```cp src/.env.local src/.env```
6. Jāuzģenerē Laravel atslēgas: ```docker-compose exec app php artisan key:generate```
7. Jāielādē node atkarības ```docker-compose exec app npm i```
8. Jānokompilē JS un SCSS faili (development režīms) ```docker-compose exec app npm run dev```
9. Jāizpilda migrācijas ```docker-compose exec app php artisan migrate```
10. Jāizpilda komanda ```docker-compose exec app chown -R www-data:www-data .```

Lietotne pieejama adresē ```127.0.0.1:8080```

## Valūtu kursu ielāde

Lai ielādētu valūtu kursus datubāzē, jāizpilda komanda 
```bash
docker-compose exec app php artisan currency:fetch
```

Komanda ```currency:fetch``` arī ir iestatīta kā "schedule", kas izpildās divas reizes dienā plkst. 00:00 un 12:00. Lai palaistu "schedule", ir nepieciešams uzstādīt Laravel crontab, kas iespējams ar komandu (komanda jāizpilda iekš app konteinera) 
```bash
* * * * * php artisan schedule:run >> /dev/null 2>&1
```

## Piekļuve konteinerim

Lai iekļūtu konteinerī, ir jāizpilda komanda
```bash
docker exec -it app /bin/bash
```
