<?php

namespace App\Models;

use CodeIgniter\Model;
use DateTime;
use PhpParser\Node\Expr\Cast;

class PemantauanModel extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getWaktuPemantauanTerakhir()
    {
        $builder =  $this->db->table('sense');
        $query = $builder->select('waktu')
            ->orderBy('waktu', 'desc')
            ->limit(1)
            ->get();
        return $query->getResult();
    }

    public function getSensingData($namaNode)
    {
        $builder = $this->db->table('hidroponik');
        $query = $builder->select('*')
                ->join('nodeSensor', 'hidroponik.idHidroponik = nodeSensor.idHidroponik', 'left')
                ->join('sensor', 'nodeSensor.idNode = sensor.idNode', 'left')
                ->orderBy('waktu', 'desc')
                ->orderBy('sensor.idSensor','asc')
                ->where(['nodeSensor.namaNode' => $namaNode])
                ->get();
        return $query->getResult();
    }

    public function getHistoryData($awal, $akhir, $node)
    {
        $builder =  $this->db->table('sense');
        $query = $builder->select('sense.waktu,suhuUdara,suhuAir,pH,kelembaban,TDS,
        Status,namaNode,batasAtas,batasBawah,namaHidroponik,lokasi')
            ->join('nodeSensor', 'sense.idNode = nodeSensor.idNode')
            ->join('sensor', 'sense.idNode = sensor.idNode')
            ->join('hidroponik', 'hidroponik.idHidroponik = nodeSensor.idHidroponik', 'left')
            ->orderBy('sense.waktu', 'asc')
            ->where('DATE(sense.waktu) BETWEEN "' . date('Y-m-d', strtotime($awal)) . ' "AND" ' . date('Y-m-d', strtotime($akhir)) . '"')
            ->having(['nodeSensor.namaNode' => $node])
            ->get();
        return $query->getResult();
    }

    public function getStatisticsData($namaNode, $parameter)
    {
        $namaSensor = "";
        if(strcasecmp($parameter,"suhuUdara") == 0){
            $namaSensor = "Suhu Udara";
        }
        else if (strcasecmp($parameter,"suhuAir") == 0){
            $namaSensor = "Suhu Air";  
        }
        else{
            $namaSensor = $parameter;
        }

        $builder =  $this->db->table('sense');
        $query = $builder->select('sense.waktu, DATE_FORMAT(DATE_ADD(DATE_FORMAT("1000-01-01 00:00:00", "
        %Y-%m-%d %H:00:00"),Interval FLOOR(TIMESTAMPDIFF(minute,DATE_FORMAT("1000-01-01 00:00:00",
         "%Y-%m-%d %H:00:00"),sense.waktu)/15)*15 minute),"%H:%i") as xLabel,nodeSensor.status, batasAtas, 
         batasBawah, ROUND(AVG('.$parameter.'),2) as rata2Value')
            ->join('nodeSensor', 'sense.idNode = nodeSensor.idNode','left')
            ->join('sensor','sense.idNode = sensor.idNode','left')
            ->groupBy('DATE_ADD(DATE_FORMAT("1000-01-01 00:00:00", "%Y-%m-%d %H:00:00"),
            Interval FLOOR(TIMESTAMPDIFF(minute,DATE_FORMAT("1000-01-01 00:00:00", "%Y-%m-%d %H:00:00"),sense.waktu)/15)*15 minute)')
            ->orderBy('sense.waktu', 'asc')
            ->where('sense.waktu >= NOW() - INTERVAL 1 DAY AND nodeSensor.namaNode LIKE"'.$namaNode.'" AND 
            sensor.namaSensor LIKE "'.$namaSensor.'"')
            ->get();
        return $query->getResult();
    }

    public function getAllNode()
    {
        $builder = $this->db->table('nodeSensor');
        $query = $builder->select('namaNode')
            ->get();
        return $query->getResult();
    }
}
