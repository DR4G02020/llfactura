<?php

namespace App\Livewire\Mesas;

use Livewire\Component;

use Livewire\WithPagination;
use Livewire\Attributes\Session;



use Illuminate\Support\Facades\Auth;

use App\Models\Mesa;
use App\Models\Inventario;


class ModificarMesa extends Component
{

    use WithPagination;



    public $mesa;

    public $seleccionPrecio;
    public $porcentaje=0;

    #[Session(key: 'mesaCarrito')] 
    public $mesaCarrito;

    #[Session(key: 'carrito')] 
    public $carrito;

    #[Session(key: 'cliente')] 
    public $cliente;


    public $razonSocial,$comentario,
            $tipoDocumento,
            $numeroDocumento,
            $tipoContribuyente,
            $domicilio,
            $correo,$datoBuscado,$cantidad=1,
            $data;



    public function finalizarMesa(){

        //BORRAMOS LAS SESSIONES POR LAS DUDAS 
        $this->carrito = null;
        $this->cliente = null;

        // dd($this->presupuesto);
        if(isset($this->razonSocial)){


            // $productos = ProductoPresupuesto::where('presupuesto_id',$this->presupuesto->id)->get();
            // dump($this->presupuesto);

            $this->mesa->razonSocial = $this->razonSocial;
            $this->mesa->tipoDocumento =$this->tipoDocumento;
            $this->mesa->numeroDocumento =$this->numeroDocumento;
            $this->mesa->tipoContribuyente =$this->tipoContribuyente;
            $this->mesa->domicilio =$this->domicilio;
            $this->mesa->correo =$this->correo;
            $this->mesa->comentario =$this->comentario;

            $this->cliente = array(
                'DocTipo'=> $this->mesa->tipoDocumento,
                'cuitCliente'=> $this->mesa->numeroDocumento,
                'razonSocial'=> $this->mesa->razonSocial,
                'tipoContribuyente'=> $this->mesa->tipoContribuyente,
                'domicilio'=> $this->mesa->domicilio,
                'leyenda'=> '',
                'idFormaPago'=> 1,
            );

            foreach ($this->data['mesaCarrito'] as $key => $value) {

                // dump($value);
                # code...
                $this->carrito['carrito'][] = array(
                    'codigo'=>$value['codigo'],
                    'detalle'=>$value['detalle'],

                    'porcentaje'=> $value['porcentaje'],
                    'precioLista'=> $value['precioLista'] ,
                    'descuento'=> $value['descuento'] ,
                    'precio'=> round( $value['precio'],2),

                    'costo'=> $value['costo'],

                    'iva'=>$value['iva'],
                    'cantidad'=>$value['cantidad'],
                    'rubro'=>$value['rubro'],
                    'proveedor'=>$value['proveedor'],
                    'marca'=>$value['marca'],

                    'controlStock'=>$value['controlStock'],
                    'subtotal'=> round( $value['precio'] * $value['cantidad'],2) ,
        
                    ) ;
            }

            // dd();



                $totalSubtotal = 0; // Inicializamos la variable para acumular los subtotales
                $cantidadArticulos = 0 ;

                // dd($this->carrito['carrito'] );

                foreach ($this->carrito['carrito'] as $item) {

                    // dd($this->carrito['carrito']);
                    $totalSubtotal += $item['subtotal'];
                    $cantidadArticulos += $item['cantidad'];

                }

                $this->carrito['total']=  round($totalSubtotal,2);
                $this->carrito['articulos']=  $cantidadArticulos;


                $this->cancelarMesa();

                $this->redirectRoute('nuevoComprobante');


        }else{
            session()->flash('mensaje', 'Falta Razon Social.');

        }



    }
        

    public function guardarPedidoMesa(){

        // dump($this->data);
        // dump($this->mesaCarrito);

        if(isset($this->data['mesaCarrito'])){

            // Combinar los elementos de mesaCarrito
            $mesaCarritoCombinado = array_merge($this->data['mesaCarrito'], $this->mesaCarrito['mesaCarrito']);
    
            // Sumar el total de los artículos
            $totalArticulos = $this->data['articulos'] + $this->mesaCarrito['articulos'];
    
            // Sumar el total de los subtotales
            $totalSubtotales = array_sum(array_column($mesaCarritoCombinado, 'subtotal'));
    
            // Crear el array resultante
            $resultado = [
                "total" => $totalSubtotales,
                "articulos" => $totalArticulos,
                "mesaCarrito" => $mesaCarritoCombinado
            ];
    
    
            // dd($resultado);
            $this->mesa->data = $resultado;

    
        }else{
            $this->mesa->data = $this->mesaCarrito;

        }


        $this->mesa->save();
        $this->mesaCarrito = null;

        $this->data = $this->mesa->data;

        session()->flash('mensaje', 'Guardado');



    }

    public function borrarArticulo($index)
    {
            $array = $this->mesaCarrito['mesaCarrito'];

            session()->flash('mensaje', 'Artículo eliminado: ' . $this->mesaCarrito['mesaCarrito'][$index]['detalle']);

            unset($array[$index]);//esto borra el elemento 
            $array = array_values($array);//esto acomoda los index            

            $totalSubtotal = 0; // Inicializamos la variable para acumular los subtotales
            $cantidadArticulos = 0 ;

            foreach ($array as $item) {

                // dd($this->mesaCarrito['mesaCarrito']);
                $totalSubtotal += $item['subtotal'];
                $cantidadArticulos += $item['cantidad'];
            }

            $this->mesaCarrito['mesaCarrito']= $array;//asignamos los nuevos valores 
            $this->mesaCarrito['total']= round($totalSubtotal,2);
            $this->mesaCarrito['articulos']=  $cantidadArticulos;

            if($cantidadArticulos == 0){
                $this->borrarCarrito();

                // $this->dispatch('actualizarCarrito', total: 0 , articulos: 0);
                // $this->tamañoGrillaVenta(0);

            }else{
                // $this->dispatch('actualizarCarrito', total: $this->mesaCarrito['total'] , articulos: $this->mesaCarrito['articulos']);

                // $this->tamañoGrillaVenta(count($this->mesaCarrito['mesaCarrito']));
            
            }

            //dump($this->mesaCarrito);


    }

    public function borrarCarrito(){
        $this->mesaCarrito=null;
        $this->cliente=null;


        // $this->dispatch('actualizarCarrito', total: 0 , articulos: 0);
        // $this->tamañoGrillaVenta(0);

        $this->porcentaje=0;


    }


    public function crearmesaCarrito($articulo)
    {

        $validated = $this->validate([
            'cantidad' => 'required|numeric|min:0.01',

        ], [
            'cantidad.required' => 'El campo cantidad a enviar es obligatorio.',
            'cantidad.numeric' => 'El campo cantidad a enviar debe ser un número.',
            'cantidad.min' => 'El campo cantidad a enviar debe ser mayor que 0.01.',

            'porcentaje.required' => 'El campo porcentaje a enviar es obligatorio.',
            'porcentaje.numeric' => 'El campo porcentaje a enviar debe ser un número.',
        ]);



            $nuevoArticulo = array(
                'codigo'=>$articulo[0]->codigo,
                'detalle'=>$articulo[0]->detalle,

                'porcentaje'=> $this->porcentaje,
                'precioLista'=> $this->porcentaje < 0 ? $articulo[0]->precio :  round(($articulo[0]->precio * $this->porcentaje / 100 + $articulo[0]->precio),2),
                'descuento'=> $this->porcentaje < 0 ? round($articulo[0]->precio * $this->porcentaje / 100 ,2) : 0 ,

                'precio'=> round(($articulo[0]->precio * $this->porcentaje / 100 + $articulo[0]->precio),2),
                'costo'=>round($articulo[0]->costo,2),
                'iva'=>$articulo[0]->iva,
                'cantidad'=>$this->cantidad,
                'rubro'=>$articulo[0]->rubro,
                'proveedor'=>$articulo[0]->proveedor,
                'marca'=>$articulo[0]->marca,

                'controlStock'=>$articulo[0]->controlStock,
                'subtotal'=> round(($articulo[0]->precio * $this->porcentaje / 100 + $articulo[0]->precio) * $this->cantidad,2) ,

                ) ;

                if(!isset($this->mesaCarrito['mesaCarrito'])){
                    $this->mesaCarrito['mesaCarrito']=[];
                }

                array_unshift($this->mesaCarrito['mesaCarrito'], $nuevoArticulo);

                $totalSubtotal = 0; // Inicializamos la variable para acumular los subtotales
                $cantidadArticulos = 0 ;

                foreach ($this->mesaCarrito['mesaCarrito'] as $item) {

                    // dd($this->mesaCarrito['mesaCarrito']);
                    $totalSubtotal += $item['subtotal'];
                    $cantidadArticulos += $item['cantidad'];

                }

                $this->mesaCarrito['total']=  round($totalSubtotal,2);
                $this->mesaCarrito['articulos']=  $cantidadArticulos;

                // $this->mesaCarrito['mesaCarrito'] = array_reverse($this->mesaCarrito['mesaCarrito']);  // Invierte el orden
                // usort($this->mesaCarrito['mesaCarrito'], function($a, $b) {
                //     return $b <=> $a;  // Orden descendente
                // });
                // // Invertir el array
                // $this->mesaCarrito['mesaCarrito'] = array_reverse($this->mesaCarrito['mesaCarrito'],true);
                
                
                
                $this->porcentaje=0;
                
                
                // $this->tamañoGrillaVenta(count($this->mesaCarrito['mesaCarrito']));
                
                // $this->dispatch('actualizarmesaCarrito', total: $this->mesaCarrito['total'] , articulos: $this->mesaCarrito['articulos']);
                
                // dd($this->mesaCarrito['mesaCarrito']);
                

    }

    public function cargar($id){

        $articulo = Inventario::select('codigo','detalle',$this->seleccionPrecio.' as precio','iva','rubro','proveedor','controlStock','costo','marca')
        ->where('id', $id)
        ->get();

        // dd($articulo);
        // "codigo" => "9373374570051"
        // "detalle" => "Almohadas"
        // "precio" => 96.26
        // "iva" => 10.5
        // "rubro" => "General"
        // "proveedor" => "Vital"
        // "controlStock" => "no"
        // "costo" => 160.25
        // "marca" => "Ford"
        
        $this->crearmesaCarrito($articulo);
        $this->cantidad = 1;
        
        // if(!$this->bloquearDetalle){
        //     $this->datoBuscado= '';
            
        // }
        
    }

    public function mount(Mesa $mesa){


        $this->seleccionPrecio ='precio1';

        $this->mesa = $mesa;
        $this->razonSocial = $mesa->razonSocial;

        $this->tipoDocumento =$mesa->tipoDocumento;
        $this->numeroDocumento =$mesa->numeroDocumento;
        $this->tipoContribuyente =$mesa->tipoContribuyente;
        $this->domicilio =$mesa->domicilio;
        $this->correo =$mesa->correo;
        $this->comentario =$mesa->comentario;


        $this->datos= $mesa->datos;

        $this->data = json_decode($mesa->data,true);

        // dd(json_decode($mesa->data,true));

        
    }


    public function guardarrazonSocial(){

        $validated = $this->validate([
            'numeroDocumento' => 'required|numeric|min:0|max:99999999999',
            'razonSocial' => 'required|min:1',
        ], [
            'numeroDocumento.required' => 'El campo CUIT a enviar es obligatorio.',
            'numeroDocumento.numeric' => 'El campo CUIT a enviar debe ser un número.',
            'numeroDocumento.min' => 'El campo CUIT a enviar debe ser mayor que 0.',
            'numeroDocumento.max' => 'El campo CUIT a enviar debe ser menor que 11.',


            'razonSocial.required' => 'El campo Razon Social a enviar es obligatorio.',
            'razonSocial.min' => 'El campo Razon Social a enviar debe ser mayor que 0.',
        ]);



        $this->mesa->razonSocial = $this->razonSocial;
        $this->mesa->tipoDocumento =$this->tipoDocumento;
        $this->mesa->numeroDocumento =$this->numeroDocumento;
        $this->mesa->tipoContribuyente =$this->tipoContribuyente;
        $this->mesa->domicilio =$this->domicilio;
        $this->mesa->correo =$this->correo;
        $this->mesa->comentario =$this->comentario;


        $this->mesa->save();


        session()->flash('mensaje', 'Cliente Guardado.');




    }


    public function cancelarMesa(){

        $this->mesa->razonSocial = null;
        $this->mesa->tipoDocumento =null;
        $this->mesa->numeroDocumento =null;
        $this->mesa->tipoContribuyente =null;
        $this->mesa->domicilio =null;
        $this->mesa->correo =null;
        $this->mesa->comentario =null;
        $this->mesa->data =null;



        $this->mesa->save();

        $this->data= null;


        $this->razonSocial = '';
        $this->tipoDocumento ='';
        $this->numeroDocumento ='';
        $this->tipoContribuyente ='';
        $this->domicilio ='';
        $this->correo ='';
        $this->comentario ='';
        $this->data ='';



    }




    public function restarCantidad(){

        $this->cantidad --;

        if($this->cantidad <= 0){

            $this->cantidad =1;


        }

    }

    public function sumarCantidad(){

        $this->cantidad ++;

    }
    
    public function render()
    {
    

        $this->resetPage();

        
        return view('livewire.mesas.modificar-mesa',[
            
            
            'inventario'=> Inventario::
            select('id','codigo','detalle',$this->seleccionPrecio .' as precio')
            ->where('empresa_id', Auth::user()->empresa_id)
            ->whereAny([
                'codigo',
                'detalle',
                'rubro',
                'proveedor'
            ], 'LIKE', "%{$this->datoBuscado}%")     
            ->orderBy('created_at','DESC')                           
            ->paginate(5),

        ])
        ->extends('layouts.app')
        ->section('main');;
    }
}
