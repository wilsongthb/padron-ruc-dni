# API para consultar nombre completo del padron de la SUNAT

# INSTALACION
```
# Obtener el padron reducido
wget http://www2.sunat.gob.pe/padron_reducido_ruc.zip

# Descomprimir el padron y guardarlo en /storage/app/padron_reducido_ruc.txt
# ejecutar
docker-compose run api php artisan migrate:f --seed

# ejecutar el stack
docker-compose up -d

# consultar el api con la ruta
http GET http://localhost:8080/dni/00000000

# El espacio ocupado de aprox 13mill de personas es de 5.3GiB
# la base de datos se guarda en mysql_store
```