<?php 
declare(strict_types = 1);
namespace Tests\Unit;

session_start(); 
ob_start(); 

require_once './action/config.php';

// require_once __DIR__ . '\action\config.php';

use database;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class WorkerTest extends TestCase{
    private MockObject $dbmock;

    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::setUp();
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        $this->dbmock = $this->createMock(database::class);
    }

    /** @test */
    public function it_created_worker(): void{
        $_POST = [
            'save_worker' => true,
            'designation' => 'Manager',
            'name' => 'John Doe',
            'username' => 'johndoe',
            'password' => 'securepassword',
            'mobile' => '1234567890',
            'address' => '123 Main St',
            'aadhar' => '123412341234',
            'pan' => 'ABCDE1234F',
            'gst' => '12ABCDE1234F1Z5',
            'bank' => 'XYZ Bank',
            'account' => '9876543210',
            'ifsc' => 'XYZB0001234',
            'reference' => 'Ref Name',
            'payment_type' => 'Monthly',
            'payment' => '50000',
            'shop' => 'Shop1',
        ];

        

        $this->dbmock->method("insert")->willReturn(true);
        $this->dbmock->method("get_last_row")->willReturn(['id'=>1]);

        ob_start();
        include "./action/insertData.php";
        // ob_clean();
        
        $this->assertEquals("Successfully Added!",$_SESSION['msg']);
    }

    /** @test */
    public function get_worker(): void{
        
        $_POST = [
            'id' => 1,
            'update_worker' => true,
            'designation' => 'Manager',
            'name' => 'John Doe',
            'username' => 'johndoe',
            'password' => 'securepassword',
            'mobile' => '1234567890',
            'address' => '123 Main St',
            'aadhar' => '123412341234',
            'pan' => 'ABCDE1234F',
            'gst' => '12ABCDE1234F1Z5',
            'bank' => 'XYZ Bank',
            'account' => '9876543210',
            'ifsc' => 'XYZB0001234',
            'reference' => 'Ref Name',
            'payment_type' => 'Monthly',
            'payment' => '50000',
            'shop' => 'Shop1',
        ];

        $this->dbmock->method("update")->willReturn(true);
        $this->dbmock->method("get_last_row")->willReturn(['id'=>1]);

        ob_start();
        
        $_SESSION['shop'] = 1;
        include "./action/updateData.php";
        ob_clean();
        
        $this->assertEquals("Successfully Updated!",$_SESSION['msg']);
    }
}