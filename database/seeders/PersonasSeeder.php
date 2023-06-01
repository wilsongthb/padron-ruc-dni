<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use function App\leerCsv;

class PersonasSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    // echo "Gaa";
    // print_r("Leendo" . PHP_EOL);
    //
    //
    // echo "Please wait" . PHP_EOL;
    // echo "Working..." . PHP_EOL;
    // sleep(5);
    // echo chr(27) . "[0G";
    // echo chr(27) . "[2A";
    // echo "Done".PHP_EOL;
    //
    //
    // echo "Progress :      ";  // 5 characters of padding at the end
    // for ($i = 0; $i <= 100; $i++) {
    //   echo "\033[5D";      // Move 5 characters backward
    //   echo str_pad($i, 3, ' ', STR_PAD_LEFT) . " %";    // Output is always 5 characters long
    //   sleep(1);           // wait for a while, so we see the animation
    // }
    // replaceCommandOutput(["Claro como no"]);
    // sleep(1);
    // replaceCommandOutput(["No puede ser loco"]);
    // //
    // exit;


    echo "Empezo" . PHP_EOL;

    $file_handle = fopen(base_path('storage/app/padron_reducido_ruc.txt'), 'r');
    $num = 1;
    $cantidadErrores = 0;
    $listaParaInsertar = [];

    function mostrarProgreso($num, $cantidadErrores)
    {
      $progress = $num / 15789156 * 100;
      echo "\rVamos: " . number_format($progress, 2) . " % - $num, Errores: $cantidadErrores" . PHP_EOL;
    }

    while (!feof($file_handle)) {
      if ($num > 1) {
        try {

          $linea = fgets($file_handle);
          $linea = mb_convert_encoding($linea, 'utf-8', 'iso-8859-1');
          $row = explode("|", $linea);
          // print_r($row);
          $tipoPer = substr($row[0], 0, 1) === '1' ? 'F' : 'J';
          $casoRaro = 0;
          $nombreCompleto = $row[1];
          if ($tipoPer === "F") {
            $nombrePartes = explode(" ", $nombreCompleto);
            if (strpos($nombreCompleto, "-")) {
              $nombrePartes = explode("-", $nombreCompleto);
            }
            if (count($nombrePartes) > 1) {
              $apPaterno = $nombrePartes[0];
              $apMaterno = $nombrePartes[1];
              $nombres = implode(" ", array_slice($nombrePartes, 2));
            } else {
              $apPaterno = $nombrePartes[0];
              $apMaterno = $nombrePartes[0];
              $nombres = $nombrePartes[0];
            }
            // print_r(
            //   compact('apPaterno', 'apMaterno', 'nombres', 'nombreCompleto')
            // );
            array_push($listaParaInsertar, [
              'ruc' => $row[0],
              'dni' => $tipoPer == 'F' ? substr($row[0], 2, 8) : null,
              'nombres' => substr($nombres, 0, 150),
              'ap_paterno' => substr($apPaterno, 0, 45),
              'ap_materno' => substr($apMaterno, 0, 45),
            ]);
          }
        } catch (\Throwable $error) {
          // handle error
          $cantidadErrores++;
        }
        // if ($num > 1000000) return;
      }
      $num++;
      if (count($listaParaInsertar) > 1 && count($listaParaInsertar) % 5000 === 0) {
        DB::table('personas')->insert($listaParaInsertar);
        $listaParaInsertar = [];
        mostrarProgreso($num, $cantidadErrores);
      }
    }
    if (count($listaParaInsertar) > 0) {
      DB::table('personas')->insert($listaParaInsertar);
      mostrarProgreso($num, $cantidadErrores);
    }

    // leerCsv(base_path('storage/app/PadronRUC_202305.csv'), function($row, $orden) {
    //   if($orden > 3) {
    //     exit;
    //   }
    //   print_r($row);
    // });

    // $file_handle = fopen(base_path('storage/app/PadronRUC_202305.csv'), 'r');
    // function get_all_lines($file_handle)
    // {
    //   while (!feof($file_handle)) {
    //     yield fgets($file_handle);
    //   }
    // }
    // $count = 0;
    // foreach (get_all_lines($file_handle) as $line) {
    //   $count += 1;
    //   if($count == 100) return;
    //   echo $count . ". " . $line;
    // }
    // fclose($file_handle);
  }
}

function replaceCommandOutput(array $output)
{
  static $oldLines = 0;
  $numNewLines = count($output) - 1;

  if ($oldLines == 0) {
    $oldLines = $numNewLines;
  }

  echo implode(PHP_EOL, $output);
  echo chr(27) . "[0G";
  echo chr(27) . "[" . $oldLines . "A";

  $numNewLines = $oldLines;
}

function determinarEstado($estado)
{
  if ($estado === "BAJA DEFINITIVA") return "B";
  if ($estado === "SUSPENSION TEMPORAL") return "S";
  return "A";
}
