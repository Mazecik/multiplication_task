<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class MultiplicationCache extends Model
{
    use HasFactory;
    protected $fillable = [
        'multiplication_limit',
        'response',
        'created_at'
    ];
    protected $table = 'miltiplication_cache';
    protected $lower_limit;
    protected $upper_limit;

    public function __construct(){
        $this->lower_limit = env('MULTIPLICATION_LOWER_LIMIT');
        $this->upper_limit = env('MULTIPLICATION_UPPER_LIMIT');
    }
    /*
     * get multiplication of $limit
     * 
     * 
     * @param int $limit - limit of Multiplication table.
     * 
     * @return string Multiplication table in json format.
    */
    public function getMultiplication($limit){
        $dbRes = $this::where('multiplication_limit', $limit)->first();
        if($dbRes){
            return $dbRes->response;
        }
        $res = $this->calcMultiplication($limit);
        $this->store($res, $limit);
        return $res;
        
    }
    /*
     * Calculate multiplication of $limit X $limit and return json formatted table.
     * 
     * @param int $limit - limit of Multiplication table.
     * 
     * @return string Multiplication table in json format.
    */
    public function calcMultiplication($limit){
        $startCol = 1;
        $returCol = [];
        $validate = $this->validateMultiplicationLimit($limit);
        if($validate !== true){
            throw ValidationException::withMessages($validate);
        }
        $limit = (int)$limit;
        while($startCol <= $limit){
            $startRow = 1;
            $returnRow = [];
            while($limit >= $startRow){
                $returnRow[$startRow] = $startCol * $startRow;
                $startRow++;
            }
            $returCol[$startCol]= $returnRow;
            $startCol++;
        }
        return json_encode($returCol); 
    }

    /*
     * Validate $limit. it must be Int type variable.
     * 
     * @param any $limit.
     * 
     * @return boolean or throw error.
    */
    public function validateMultiplicationLimit($limit){
        if((string)(int)$limit !== (string)$limit){
            throw ValidationException::withMessages(['limit' => 'Size is not a number!']);
        }
        if($limit > $this->upper_limit || $limit < $this->lower_limit){
            throw ValidationException::withMessages(['limit' => "Size is not in reach ( $this->lower_limit to $this->upper_limit )"]);
        }
        return true;
    }
    

    public function store($json, $size){
        $this->multiplication_limit = $size;
        $this->response = $json;
        $this->created_at = date("Y-m-d H:i:s");;
        $this->save();
    }
}
