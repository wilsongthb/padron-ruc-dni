<?php
/**
 * Author: Wilson Pilco Nuñez
 * Email: wilsonaux1@gmail.com
 * PHP Version: 7.4
 * Created at: 2023-05-29 17:09
 */
namespace App;

if (!function_exists('leerCsv')) {
  /**
   * @param string $path Ruta del archivo csv
   * @param callable $callback funcion a ejecutar por linea
   * @param string $separador separador de columna, ',' por defecto
   */
  function leerCsv(string $path, callable $callback, string $separador = ",")
  {
    if (($gestor = fopen($path, "r")) !== FALSE) {
      $num = 1;
      while (($datos = fgetcsv($gestor, 1000, $separador)) !== FALSE) {
        $callback($datos, $num);
        $num++;
      }
    }
  }
}

