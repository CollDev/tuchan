<?php

Class log {
    const RUTA_LOG = 'log/';
    const VIDEO = '_video';
    const VIDEO_MAS_VISTOS = '_video_mas_vistos';
    const VIDEO_REPETIDO = '_repetido';
    const VIDEO_NO_EXISTE = 'video_no_existe';
    const MIGRACION_MONGODB = '_m_mongoDb';

    public function logVideoMasVistos($first, $msg, $accion) {

        foreach ($msg as $key => $value) {
            if ($value) {
                $date = date('d.m.Y h:i:s');
                $log = 'Video: ' . $first . "   |  Date:  " . $date . "  |  $key :         " . $value . "\n";
                error_log($log, 3, self::RUTA_LOG . date('d-m-Y') . self::VIDEO_MAS_VISTOS . '_.log');
            }
        }
    }

    public function logVideo($first, $msg) {
        foreach ($msg as $key => $value) {
            if (is_array($value)) {
                $value = array_values(array_diff($value, array('')));
                foreach ($value as $keyI => $valueI) {
                    if ($valueI) {
                        $date = date('d.m.Y h:i:s');
                        $log = 'VideoID: ' . $first . "   |  Date:  " . $date . "  |  $key :         " . $valueI . "\n";
                        error_log($log, 3, self::RUTA_LOG . date('d-m-Y') . self::VIDEO . '_.log');
                    }
                }
            } else {
                $date = date('d.m.Y h:i:s');
                $log = 'VideoID: ' . $first . "   |  Date:  " . $date . "  |  $key :         " . $value . "\n";
                error_log($log, 3, self::RUTA_LOG . date('d-m-Y') . self::VIDEO . '_.log');
            }
        }
    }

    public function logVideoRepetido($first, $msg) {

        foreach ($msg as $key => $value) {
            if (!is_array($value)) {
                if ($key != 'status') {
                    if ($key != 'descripcion') {
                        $date = date('d.m.Y h:i:s');
                        $log = 'VideoID: ' . $first . "   |  Date:  " . $date . "  |  $key :         " . $value . "\n";
                        error_log($log, 3, self::RUTA_LOG . date('d-m-Y') . self::VIDEO_REPETIDO . '_.log');
                    }
                }
            }
        }
    }

    public function logRegistros($msg, $id, $campo) {


        $date = date('d.m.Y h:i:s');
        $log = $msg . "   |  Date:  " . $date . "  |  $campo :         " . $id . "\n";
        error_log($log, 3, self::RUTA_LOG . date('d-m-Y') . self::MIGRACION_MONGODB . '.log');
    }

    public function logTabla($msg) {

        $logx = '========================================================================' . "\n";
        $date = date('d.m.Y h:i:s');
        $log = "\n" . $msg . "   |  Date:  " . $date . "  | " . "\n";
        error_log($log, 3, self::RUTA_LOG . date('d-m-Y') . self::MIGRACION_MONGODB . '.log');
        error_log($logx, 3, self::RUTA_LOG . date('d-m-Y') . self::MIGRACION_MONGODB . '.log');
    }

    public function logVideoNoExiste($id) {
        $date = date('d.m.Y h:i:s');
        $log = "Video_id de Liquid $id" . "   |  Date:  " . $date . "  |  video_id :         " . $id . "\n";
        error_log($log, 3, self::RUTA_LOG . date('d-m-Y') . self::VIDEO_NO_EXISTE . '.log');
    }
}
