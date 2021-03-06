<?php
namespace App\Services;

use App\Http\Controllers\MeasurementsController;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class MeasurementService
{
    /**
     * @param String $type
     */
    public function getMeasurements(String $type)
    {

        $measurements = DB::table('measurements')->select('id','name','type','created_at')->where('type', $type)->get();
        return $measurements;
    }

    /**
     * @param String $type
     * @param int $measurementid
     */
    public function getMeasurementDetails(int $measurementid)
    {
        $measurement = DB::table('measurements')->select('type', 'name')->where('id',$measurementid)->first();
        $type = $measurement->type;
        $measurementDetails = null;
        if ($type == MeasurementsController::$BLOOD_PRESSURE) {
            $measurementDetails = DB::table('blood_pressure_measurements')
                ->select('id','pressure_lower', 'pressure_upper', 'measurement_taken_at')
                ->where('measurementid', $measurementid)
                ->get();
        } else if ($type == MeasurementsController::$PULSE) {
            $measurementDetails = DB::table('pulse_measurements')
                ->select('id','pulse', 'measurement_taken_at')
                ->where('measurementid', $measurementid)
                ->get();
        } else if ($type == MeasurementsController::$ECG_WAVES) {
            $measurementDetails = DB::table('ECG_waves_measurements')
                ->select('id','ECG_waves', 'measurement_taken_at')
                ->where('measurementid', $measurementid)
                ->get();
        }
        return $measurementDetails;

    }

    public function getAllMeasurements($userNumber) {
        $result = DB::table("measurements")
            ->select('name', 'type', 'created_at as createdAt', 'id')
            ->where("user_number", $userNumber)->get();
        return $result;
    }



    public function insertPulse($measurements, $userNumber) {
        $result = $this->selectLastId();
        $id = $result == null ? 1 :  $result->id+1;
        $title = "Meting " . $id;
        DB::table("measurements")->insert([
            "name" => $title,
            "created_at" => Carbon::now(),
            "type" => MeasurementsController::$PULSE,
            "user_number" => $userNumber
        ]);
        $dataSet = $this->convertToData("pulse", $measurements, $id);

        $result = DB::table('pulse_measurements')
            ->insert($dataSet);
        if($result){
            return $id;
        }
        else {
            return response("Insert went wrong", 500);
        }
    }

    public function insertEcg($measurements, $userNumber){
        $result = $this->selectLastId();
        $id = $result == null ? 1 :  $result->id+1;
        $id = $result == null ? 1 :  $result->id+1;
        $title = "Meting " . $id;
        DB::table("measurements")->insert([
            "name" => $title,
            "created_at" => Carbon::now(),
            "type" => MeasurementsController::$ECG_WAVES,
            "user_number" => $userNumber
        ]);
        $dataSet = $this->convertToData("ECG_waves", $measurements, $id);

        $result = DB::table('ECG_waves_measurements')
            ->insert($dataSet);
        if($result){
            return $id;
        }
        else {
            return response("Insert went wrong", 500);
        }
    }
    public function insertBloodPressure($measurements, $userNumber) {
        $result = $this->selectLastId();
        $id = $result == null ? 1 :  $result->id+1;
        $title = "Meting " . $id;
        DB::table("measurements")->insert([
            "name" => $title,
            "created_at" => Carbon::now(),
            "type" => MeasurementsController::$PULSE,
            "user_number" => $userNumber
        ]);
        $dataSet = $this->convertBloodPressureToData("pulse", $measurements, $id);

        $result = DB::table('blood_pressure_measurements')
            ->insert($dataSet);
        if($result){
            return $id;
        }
        else {
            return response("Insert went wrong", 500);
        }
    }
    private function convertToData(String $datatype, $doubleArray, $id){
        $array = [];
        foreach($doubleArray as $measurement ){
            $dataSet[] = [
                $datatype => $measurement,
                "measurement_taken_at" => Carbon::now(),
                "measurementid" => $id
            ];
            $array += $dataSet;
        }
        return $array;
    }

    private function convertBloodPressureToData(String $datatype, $doubleArray, $id){
        $array = [];
        foreach($doubleArray as $measurement ){
            $dataSet = [
                "pressure_upper" => $measurement[0],
                "pressure_lower" => $measurement[1],
                "measurement_taken_at" => Carbon::now(),
                "measurementid" => $id
            ];
            $array += $dataSet;
        }
        return $array;
    }


    private function selectLastId(){
            return DB::table('measurements')
                ->select('id')
                ->orderBy('id', 'desc')
                ->first();

    }
}