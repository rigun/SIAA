<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;
use App\Pegawai;
use App\Cabang;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['owner','cs','kasir'];
        $cabang = new Cabang();
        $cabang->nama = 'Babarsari';
        $cabang->save();
        foreach($roles as $role){
            $this->command->info('Creating Role '. strtoupper($role));
            
            $r = new Role();
            $r->name = $role;
            $r->save();
            
            $this->command->info('Creating User '. strtoupper($role));
            $user = new User();
            $user->username = $role;
            $user->password = bcrypt('password');
            $user->role_id = $r->id;
            $user->save();

            $pegawai = new Pegawai();
            $pegawai->nama = $role;
            $pegawai->alamat = $role;
            $pegawai->no_telp = $role;
            $pegawai->gaji = 1000000;
            $pegawai->user_id = $user->id;
            $pegawai->id_cabang = $cabang->id;
            $pegawai->save();    
        }
        $pegawai = new Pegawai();
        $pegawai->nama = 'montir';
        $pegawai->alamat = 'montir';
        $pegawai->no_telp = 'montir';
        $pegawai->gaji = 1000000;
        $pegawai->save();
    }
}
