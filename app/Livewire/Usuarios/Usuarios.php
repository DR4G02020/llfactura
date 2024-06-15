<?php

namespace App\Livewire\Usuarios;

use Livewire\Component;

use Illuminate\Support\Facades\Auth;

// use App\Models\User;
// use App\Models\Empresa;
// use App\Models\Deposito;

use Illuminate\Support\Facades\DB;

class Usuarios extends Component
{

    // public $usuarios;
 
    // public $email;

    public $buscarUsuario;

    // public $deposito;
 
    public function mount()
    {
        // $this->usuarios = DB::select('SELECT a.id as usuarioId, c.nombre as rol, a.*,b.*,c.* FROM users a,empresas b, roles c WHERE a.empresa_id = b.id AND a.role_id = c.id');
 
        // $this->deposito = Deposito::where('empresa_id',Auth::user()->empresa_id)->get();
        
    }

    public function render()
    {
        return view('livewire.usuarios.usuarios',
            [
    
            'usuarios'=> DB::select("SELECT
                                        a.id AS usuarioId,
                                        c.nombre AS rol,
                                        a.*,
                                        b.*,
                                        c.*,
                                        d.nombre as nombreDeposito
                                    FROM
                                        users a,
                                        empresas b,
                                        roles c,
                                        depositos d
                                    WHERE
                                        a.empresa_id = b.id AND a.role_id = c.id  AND d.id = a.deposito_id AND
                                        (
                                            a.name LIKE '%$this->buscarUsuario%' OR
                                            a.email LIKE '%$this->buscarUsuario%' OR 
                                            b.razonSocial LIKE '%$this->buscarUsuario%' OR
                                            c.nombre LIKE '%$this->buscarUsuario%'
                                        )
                                    ORDER BY a.last_login DESC"
                                    ),

            
            ])        
        ->extends('layouts.app')
        ->section('main'); 
    }

}
