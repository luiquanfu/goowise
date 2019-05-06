<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertAdmins extends Migration
{
    public function up()
    {
        $data = array();
        $data['id'] = $this->unique_id();
        $data['name'] = 'Quanfu';
        $data['mobile'] = '90174663';
        $data['email'] = 'quanfu@hotmail.sg';
        $data['password'] = bcrypt('alienwarem14x');
        $data['created_at'] = time();
        $data['updated_at'] = time();
        $data['deleted_at'] = 0;
        DB::table('admins')->insert($data);

        $data = array();
        $data['id'] = $this->unique_id();
        $data['name'] = 'Alson';
        $data['mobile'] = '97486595';
        $data['email'] = 'alsonkong@goowise.com';
        $data['password'] = bcrypt('alsonkong123');
        $data['created_at'] = time();
        $data['updated_at'] = time();
        $data['deleted_at'] = 0;
        DB::table('admins')->insert($data);
    }

    public function down()
    {
        $query = DB::table('admins');
        $query->where('email', 'quanfu@hotmail.sg');
        $query->where('deleted_at', 0);
        $query->update(['deleted_at' => time()]);

        $query = DB::table('admins');
        $query->where('email', 'alsonkong@goowise.com');
        $query->where('deleted_at', 0);
        $query->update(['deleted_at' => time()]);
    }

    public function unique_id()
    {
        $random = str_pad(rand(1, 999999999), 9, 0, STR_PAD_LEFT);
        return time().$random;
    }
}
