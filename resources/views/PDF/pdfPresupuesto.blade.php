<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>{{$titulo}}</title>

<style type="text/css">
    * {
        font-family: Verdana, Arial, sans-serif;
    }
    table{
        font-size: x-small;
    }
    tfoot tr td{
        font-weight: bold;
        /* font-size: x-small; */

    }

    .gray {
        background-color: lightgray
    }

    .centered-cell {
        text-align: center; /* Centers content horizontally */
        margin: auto;
        
        /* border: 3px solid black; */
        }

        .centered-cell:nth-child(2) { /* Target the second td */
        display: flex;
        align-items: center;
        }


</style>

</head>
<body>

  <table width="100%">

    {{-- <tr >
        <td colspan="3" style="text-align: center;">
            <h1 class="centered-cell" style="border: 1px solid black; width: 100px;" >A</h1> 
            <small style="text-align: center;">Cod.11</small> 
        </td> 
    </tr> --}}
    <tr style="align-content: center; align-items: center;">

      <td  style="width: 250px; "  >
        <img src="{{$logo}}"  alt="" style="width: 300px; margin-top: -30px; margin-left: auto; background-size: cover;"/>

          <table style="text-align: center;">
            <tr>
              <td><h1>{{$empresaNombre}}</h1></td>              
            </tr>
            <tr>
               <td>{{$direccionEmpresa}}</td>
            </tr>
            <tr>
              <td> {{$telefonoEmpresa}}</td>
            </tr>
            <tr>
               <td>DE: {{$titularEmpresa}}</td>
            </tr>
          </table>

      </td>
        
      <td colspan="3" class="centered-cell" style="text-align: center; padding-top: 0%;">
        
          


      </td> 


      <td class="centered-cell">  
        <h2 style="font-size: 300%; margin-top: -30px; margin-left: auto;">Presupuesto</h2>    
          <ul style="list-style: none; font-size: 80%;  margin-left: auto;">
            <li>
              NRO: {{$numeroPresupuesto}}
            </li>
            <li>
              Cuit: {{$cuitEmpresa}}
            </li>
            <li>
              Ingresos Brutos: {{$cuitEmpresa}}
            </li>
            <li>
              Inicio Actividades: {{$inicioActividades}}
            </li>
          </ul>

          <div style="width: 100px;" style="height: 40px; " style="border: 1px solid black;" style="background-color:rgb(191, 182, 182)" >
            <small>Fecha:</small>
            <hr>
            <h3 style="margin: 5px;">{{$fechaPresupuesto}}</h3>
          </div>
      </td>
    </tr>    

  </table>
  <hr>
  

  <table width="100%" style="text-align: center;">
    <tr>
        <td align="left">  
            <ul style="list-style-type: none;">
                <li><small> <strong>Cliente: </strong>{{$nombreCliente}}</small></li>
                <li><small>Cuit: <strong>{{$cuitCliente}}</strong></small></li>
                <li><small>Domicilio; <strong>{{$domicilioCliente}}</strong></small></li>

            </ul>
        </td>
        <td align="left">  
            <ul style="list-style-type: none;">
                <li><strong>{{$tipoContribuyente}}</strong></li>
                <li>{{$leyenda}}</li>                

            </ul>
        </td>
    </tr>

  </table>

  <br/>

  <table width="100%">
    <thead style="background-color: lightgray;">
      <tr  style="">
        <th>Codigo</th>
        <th>Descripcion</th>
        <th>Cant.</th>
        <th>Precio U.$</th>
        <th>Bon.%</th>
        <th>Precio B.$</th>
        <th>Sub.Total.$</th>
      </tr>
    </thead>

    <tbody>

@if ($producto)
@foreach ($producto as $item)

<tr>
  <th scope="row">{{$item->codigo}}</th>
  <td>{{$item->detalle}}</td>
  <td align="right">{{$item->cantidad}}</td>

  <td align="right">{{number_format($item->precioLista,2)}}</td>
  <td align="right">
      {{$item->porcentaje < 0 ? $item->porcentaje : ''}}
  </td>
  <td align="right">{{number_format($item->precio,2)}}</td>
  <td align="right">{{ number_format($item->precio * $item->cantidad,2)}}</td>

</tr>
    
@endforeach
    
@else

<tr>
  <th scope="row">00123</th>
  <td>VARIOS</td>
  <td align="right">1</td>
  <td align="right">{{$totalVenta}}</td>
  <td align="right">{{$totalVenta}}</td>
</tr>
    
@endif


  
    </tbody>
    <tr>
      <td colspan="2"></td>
      <td colspan="5"><hr></td>
    </tr>
    <tfoot>

    <tr>
        <td colspan="5" align="right" >Importe sin Descuento: </td>

        <td colspan="2" align="right">$ {{$subTotalPrecioLista}}</td>

    </tr>

    <tr>
        <td colspan="5" align="right" >Importe Descuento: </td>
        <td colspan="2" align="right">$ {{$totalDescuento}}</td>

    </tr>

      <tr>
          <td colspan="5" align="right" >Importe con Descuento: </td>

          <td colspan="2" align="right">$ {{$totalVenta}}</td>

      </tr>
      <tr>
          <td colspan="7"><hr></td>
      </tr>
      <tr>
          <td colspan="3" align="right">Total :</td>
          <td colspan="4" align="right" class="gray">$ {{$totalVenta}}</td>
      </tr>

        <tr class="bill-row row-details">
          <td>
            <div>
              <div class="row">
                            <img src="{{$qr}}" alt="" width="100%">
              </div>
            </div>
          </td>
          <td>
            <div>
              <div class="row text-right margin-b-10">
                <strong>Fecha de Vencimiento</strong>
              </div>
              <div class="row text-right">
                <strong>{{$fechaVencimiento}}</strong>
              </div>
            </div>
          </td>
        </tr>


    </tfoot>
  </table>

  {{-- <img src="{{$qr}}" alt="" width="20%"> --}}

  <script type="text/php">
    if ( isset($pdf) ) {
        $pdf->page_script('
            $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
            $pdf->text(270, 820, "Pág $PAGE_NUM de $PAGE_COUNT", $font, 10);
        ');
    }
  </script>
  
</body>
</html>