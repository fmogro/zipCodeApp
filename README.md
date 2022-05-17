
# Api Rest Simple de Correos de México✉️
Esta Api se alimenta de un archivo txt obtenido de aquí.

https://www.correosdemexico.gob.mx/SSLServicios/ConsultaCP/CodigoPostal_Exportar.aspx 

El archivo de texto se debe agregar a la carpeta 

    /resources/scripts/

Utilizando el nombre 

    CPdescarga.txt
Se debe editar el archivo import.php donde para colocar credenciales MySQL y nombre de base de datos para realizar la importación del archivo por consola. 

Luego ejecutar.

    php importar.php

Y listo, los datos se deberían haber importado de manera exitosa.

Para la creación de campos en la tabla, se utilizó lo que nos provee el sitio web
https://www.correosdemexico.gob.mx/SSLServicios/ConsultaCP/imagenes/Descrip.pdf

  

## Implementación 🔥
La implementación es sencilla utilizando Laravel 8 como Backend
En primer lugar, se debe clonar el repositorio y hacer:

    composer install

  Luego, una vez finalizado, ejecutar.
  

    php artisan serve

Y listo, se deberia estar ejecutando, si se ejecuta por defecto estaría en el puerto 8000 y para probar, deberíamos poder acceder así 

    http://127.0.0.1:8000/api/zip-codes/1000

Y la respuesta debería ser

    {
	    "zip_code": "1000",
	    "locality": "CIUDAD DE MÉXICO",
	    "federal_entity": {
		    "key": 9,
		    "name": "CIUDAD DE MÉXICO",
		    "code": null
		 },
		 "settlements": [
			 {
				 "key": 213,
				 "name": "SANTA LUCÍA",
				 "zone_type": "URBANO",
					 "settlement_type": {
					 "name": "Pueblo"
					 }
			  }
		   ],
			"municipality": {
				"key": 10,
				"name": "ÁLVARO OBREGÓN"
			}
	}
