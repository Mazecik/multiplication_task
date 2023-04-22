<?php

namespace Tests\Feature;

use App\Models\MultiplicationCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class MultiplicationCacheTest extends TestCase
{
    /**
     * Testing validate function
     */
    public function test_validate(): void
    {
        $cache = new MultiplicationCache();

        // Testing correct values
        $this->assertTrue($cache->validateMultiplicationLimit(100));
        $this->assertTrue($cache->validateMultiplicationLimit(10));
        $this->assertTrue($cache->validateMultiplicationLimit(1));

        // Testing incorrect values
        try {
            $cache->validateMultiplicationLimit('not a number');
            $this->fail('Expected ValidationException not thrown');
        } catch (ValidationException $ex) {
            $this->assertEquals('Size is not a number!', $ex->getMessage());
        }
        
        try {
            $cache->validateMultiplicationLimit(0);
            $this->fail('Expected ValidationException not thrown');
        } catch (ValidationException $ex) {
            $this->assertEquals('Size is not in reach ( 1 to 100 )', $ex->getMessage());
        }
        
        try {
            $cache->validateMultiplicationLimit(101);
            $this->fail('Expected ValidationException not thrown');
        } catch (ValidationException $ex) {
            $this->assertEquals('Size is not in reach ( 1 to 100 )', $ex->getMessage());
        }

        try {
            $cache->validateMultiplicationLimit(-2);
            $this->fail('Expected ValidationException not thrown');
        } catch (ValidationException $ex) {
            $this->assertEquals('Size is not in reach ( 1 to 100 )', $ex->getMessage());
        }
    }

     /**
     * Testing calcMultiplication
     */
    public function test_calcMultiplication(): void
    {
        $cache = new MultiplicationCache();

        // Testing correct values
        $this->assertJsonStringEqualsJsonString(
            json_encode($cache->calcMultiplication(1)),
            json_encode('{"1":{"1":1}}')
        );

        $this->assertJsonStringEqualsJsonString(
            json_encode($cache->calcMultiplication(10)),
            json_encode('{"1":{"1":1,"2":2,"3":3,"4":4,"5":5,"6":6,"7":7,"8":8,"9":9,"10":10},"2":{"1":2,"2":4,"3":6,"4":8,"5":10,"6":12,"7":14,"8":16,"9":18,"10":20},"3":{"1":3,"2":6,"3":9,"4":12,"5":15,"6":18,"7":21,"8":24,"9":27,"10":30},"4":{"1":4,"2":8,"3":12,"4":16,"5":20,"6":24,"7":28,"8":32,"9":36,"10":40},"5":{"1":5,"2":10,"3":15,"4":20,"5":25,"6":30,"7":35,"8":40,"9":45,"10":50},"6":{"1":6,"2":12,"3":18,"4":24,"5":30,"6":36,"7":42,"8":48,"9":54,"10":60},"7":{"1":7,"2":14,"3":21,"4":28,"5":35,"6":42,"7":49,"8":56,"9":63,"10":70},"8":{"1":8,"2":16,"3":24,"4":32,"5":40,"6":48,"7":56,"8":64,"9":72,"10":80},"9":{"1":9,"2":18,"3":27,"4":36,"5":45,"6":54,"7":63,"8":72,"9":81,"10":90},"10":{"1":10,"2":20,"3":30,"4":40,"5":50,"6":60,"7":70,"8":80,"9":90,"10":100}}')
        );

        // Testing incorrect values
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Size is not a number!');
        $cache->validateMultiplicationLimit('q4r124120afaskaskga');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Size is not a number!');
        $cache->validateMultiplicationLimit(true);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Size is not in reach ( 1 to 100 )');
        $cache->validateMultiplicationLimit(0);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Size is not in reach ( 1 to 10 )');
        $cache->validateMultiplicationLimit(11);
    }
}
