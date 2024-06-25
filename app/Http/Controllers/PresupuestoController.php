<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;


use App\Models\Empresa;
use App\Models\FormaPago;
use App\Models\Presupuesto;
use App\Models\ProductoPresupuesto;

use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 
use Barryvdh\DomPDF\Facade\Pdf; 
use chillerlan\QRCode\{QRCode, QROptions}; 


class PresupuestoController extends Controller
{
    //

    public function imprimir($presupuesto_id , $formato = 'A4'){

  
        $empresa = Empresa::find(Auth::user()->empresa_id);  

        //    return Auth::user();

        $presupuesto = Presupuesto::where('id',$presupuesto_id)->where('empresa_id',$empresa->id)->get();


        if ($presupuesto->count() == 0) {
            return redirect()->route('factura')->with('mensaje','Nada para Imprimir');
    
        }

        $productos = ProductoPresupuesto::where('presupuesto_id',$presupuesto_id)->get();

        //para saber si es negativo en las notas de credito y pasarlo a positivo
        $totalRevisado = floatval(($presupuesto[0]->total) > 0 ? $presupuesto[0]->total : ($presupuesto[0]->total * -1)); 
      
        // $importe_gravado_al21=0;
        // $importe_iva_al21=0;
        // $importe_gravado_al105=0;
        // $importe_iva_al105=0;

        // // dd(count($productos));

        // if(count($productos)== 0){
        //     $productos = null;
           
        // }else{
        //     foreach ($productos as $key => $value) {     
                
        //         //     #attributes: array:8 [▼
        //         //     "id" => 9
        //         //     "comprobante_id" => 12
        //         //     "comprobante_numero" => 101
        //         //     "codigo" => "71697413"
        //         //     "detalle" => "laudantium"
        //         //     "precio" => 30.61
        //         //     "iva" => 10.5
        //         //     "cantidad" => 1.0
        //         // ]

        //         if($value->iva == 21){
                    
        //             $importe_gravado_al21 += round($value->precio * $value->cantidad / 1.21,2);
        //             $importe_iva_al21 += round($value->precio * $value->cantidad - ($value->precio * $value->cantidad / 1.21),2);

        //             if($comprobante[0]->tipoComp == 1){
        //                 $productos[$key]->precio = round($value->precio / 1.21,2);
        //             }
                    
        //         }elseif($value->iva == 10.5){
                    
        //             $importe_gravado_al105 += round($value->precio * $value->cantidad / 1.105,2);
        //             $importe_iva_al105 += round($value->precio * $value->cantidad -($value->precio * $value->cantidad / 1.105),2);

        //             if($comprobante[0]->tipoComp == 1){
        //                 $productos[$key]->precio = round($value->precio / 1.105,2);
        //             }
        //         }
        //     }

            
        // }

        //para pruebas
        // $productos = null;

             
        if (Storage::disk('local')->exists( 'public/'.$empresa->cuit.'/logo/logo.png')) {
            // ...
            $url = Storage::url( 'public/'.$empresa->cuit.'/logo/logo.png');
            $path = Storage::path('public/'.$empresa->cuit.'/logo/logo.png');
            // $url = Storage::get('public/'.$empresa->cuit.'/logo/logo.png');
            // return  asset($url);
            // return $path;
            
        }else{
            $url = 'sin Imagen';
            $path ='sin Imagen';
        }

                
            $infoQR ='llfactura.com';           

            
            $qrcode = (new QRCode)->render($infoQR);


            switch ($presupuesto[0]->tipoContribuyente) {
                case 4:
                    $tipoContribuyenteCliente = 'Exento';
                    break;
                case 5:
                    $tipoContribuyenteCliente = 'Consumidor Final';
                    break;
                case 6:
                    $tipoContribuyenteCliente = 'Responsable Inscripto';
                    break;
                case 13:
                    $tipoContribuyenteCliente = 'Monotributista';
                    break;
            }
            $tipoComprobante = 'PRESUPUESTO';



            $nombreFormaPago = FormaPago::find($presupuesto[0]->idFormaPago);


            $info = [
                'logo'=>$path,
                'empresaNombre'=>$empresa->razonSocial,
                'numeroPresupuesto'=> $presupuesto[0]->numero,
                'cuitEmpresa'=>$empresa->cuit,

                'inicioActividades'=> date('d-m-Y', strtotime($empresa->inicioActividades)) ,
                'fechaPresupuesto'=>date('d-m-Y', strtotime($presupuesto[0]->fecha)),
                'fechaVencimiento'=>date('d-m-Y', strtotime($presupuesto[0]->fechaVencimiento)),



                'direccionEmpresa'=>$empresa->domicilio,
                'telefonoEmpresa'=>$empresa->telefono,
                'titularEmpresa'=>$empresa->titular,
                'tipoFactura'=>$tipoComprobante,
                
                'nombreCliente'=>$presupuesto[0]->razonSocial,
                'cuitCliente'=>$presupuesto[0]->cuitCliente,
                'domicilioCliente'=>$presupuesto[0]->domicilio,
                'tipoContribuyente'=>$tipoContribuyenteCliente,
                'leyenda'=>$presupuesto[0]->leyenda,
                'nombreFormaPago'=>$nombreFormaPago->nombre,
                'producto'=> $productos,
 
                'totalVenta'=> number_format($totalRevisado, 2) ,

                'qr'=>$qrcode,
                'titulo'=>$tipoComprobante .' N '.$presupuesto[0]->numero.' '. date('dmY', strtotime($presupuesto[0]->fecha)).' ' .$presupuesto[0]->razonSocial
            ];


            // return $info;

            if($formato == 'Ticket'){
                //tamaño custom, se especifica en puntos, lo que en CSS se escribe como pt
                
                $pdf = Pdf::loadView('PDF.pdfPresupuestoTicket',$info);
                $pdf->set_paper(array(0,0,250,(550 + (count($productos) * 25))), 'portrait');
            }else{

                $pdf = Pdf::loadView('PDF.pdfPresupuesto',$info);
            }

            

            // $pdf->getCanvas()->page_text(15,800, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0,0,0));

            $nombreArchivo= $tipoComprobante .' N '.$presupuesto[0]->numero.' '. date('dmY', strtotime($presupuesto[0]->fecha)).' ' .$presupuesto[0]->razonSocial.'.pdf';
            // return $pdf->download($nombreArchivo);
            return $pdf->stream($nombreArchivo);          
           



    }
}
