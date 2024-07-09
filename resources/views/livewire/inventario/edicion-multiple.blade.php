<div>
    <div class="container">

        <h3>Edicion Multiple</h3>
        <fieldset>
            <label>
              <input wire:model.live="precioFijo" name="terms" type="checkbox" role="switch" />
              Procentaje / Precio Fijo
            </label>

          </fieldset>
        <article>
            <div class="grid">
                @if (!$precioFijo)
                    <div class="col">
                        <fieldset role="group">
                        
                                <input wire:keyup="actualizar" wire:model="porcentajePrecio1"  type="text" placeholder="% Precio 1"/>
                        
                                <input wire:keyup="actualizar" wire:model="porcentajePrecio2" type="text" placeholder="% Precio 2" />
                        
                                <input wire:keyup="actualizar" wire:model="porcentajePrecio3"  type="text" placeholder="% Precio 3" />
                            
                                <button wire:click="modificarPrecio" wire:confirm="Serguro de Actualizar?">Porcentaje</button>
                        </fieldset>
                        @error('porcentajePrecio1') <p style="color: red;"> {{ $message }} </p><br> @enderror
                        @error('porcentajePrecio2') <p style="color: red;"> {{ $message }} </p> <br> @enderror
                        @error('porcentajePrecio3') <p style="color: red;"> {{ $message }} </p> <br> @enderror
                    </div>   
                    
                @else

                    <div class="col">
                        <fieldset role="group">
                        
                                <input wire:keyup="actualizar" wire:model="fijo1"  type="text" placeholder="% Precio 1"/>
                        
                                <input wire:keyup="actualizar" wire:model="fijo2" type="text" placeholder="% Precio 2" />
                        
                                <input wire:keyup="actualizar" wire:model="fijo3"  type="text" placeholder="% Precio 3" />
                            
                                <button wire:click="modificarPrecio" wire:confirm="Serguro de Actualizar?">PrecioFijo</button>
                        </fieldset>
                        @error('fijo1') <p style="color: red;"> {{ $message }} </p><br> @enderror
                        @error('fijo2') <p style="color: red;"> {{ $message }} </p> <br> @enderror
                        @error('fijo3') <p style="color: red;"> {{ $message }} </p> <br> @enderror
                    </div>  
                    
                @endif
            
  

            </div>
        </article>

        <article>
   
                <div class="grid">
                    <div class="col">
                        
                        <legend>Criterio de Filtro:</legend>

                        <select name="" id="" wire:model="criterioFiltro" wire:change="actualizarCriterio">
                            <option value="rubro">Rubro</option>
                            <option value="proveedor">Proveedor</option>
                            <option value="marca">Marca</option>
                        </select>
                            
                          
                    </div>
                    <div class="col">
                        <label for="">
                            Dato Filtrado
                            <select name="" id="" wire:model.live="datoFiltro" >
                                
                                @foreach ($opciones as $i)
                                    <option value="{{$i->nombre}}" >{{$i->nombre}}</option>                                
                                @endforeach
                            </select>
        
                        </label>

                    </div>
                    <div class="col">
                        <fieldset>
                            <label>
                              <input wire:model="imprimirReporte" type="checkbox" role="switch" />
                              Imprimir Reporte?
                            </label>
                          </fieldset>
                    </div>
                </div>

        </article>

        @if (session('mensaje'))
        
            <article>
                {{ session('mensaje') }}
            </article>
        @endif


        <div class="overflow-auto">
            <table class="striped">
                <thead>
                  <tr>
                    {{-- <th scope="col">id</th> --}}
                    <th scope="col">Codigo</th>
                    <th scope="col">Detalle</th>

                    @if (!$precioFijo)
                        
                        <th scope="col">Precio 1</th>
                        <th scope="col">X {{$porcentaje1}}%</th>
                        <th scope="col">Precio 2</th>
                        <th scope="col">X {{$porcentaje2}}%</th>
                        <th scope="col">Precio 3</th>
                        <th scope="col">X {{$porcentaje3}}%</th>
                    @else
                        <th scope="col">Precio 1</th>
                        <th scope="col">P.F. ${{$precioFijo1}}</th>
                        <th scope="col">Precio 2</th>
                        <th scope="col">P.F. ${{$precioFijo2}}</th>
                        <th scope="col">Precio 3</th>
                        <th scope="col">P.F. ${{$precioFijo3}}</th>
                    @endif
                    
                    <th scope="col">Costo</th>
                    <th scope="col">Iva</th>
                    <th scope="col">Rubro</th>
                    <th scope="col">Proveedor</th>
                    <th scope="col">Marca</th>


                  </tr>
                </thead>
                <tbody>

                    @foreach ($inventario as $i)
                        
                        <tr>
                        {{-- <th scope="row">{{$i->id}}</th> --}}
                        <td>{{$i->codigo}}</td>
                        <td>{{$i->detalle}}</td>
                        <td style="color: green;">${{$i->precio1}}</td>
                        <td style="color: red;">${{$i->NuevoPrecio1}}</td>
                        <td style="color: green;">${{$i->precio2}}</td>
                        <td style="color: red;">${{$i->NuevoPrecio2}}</td>
                        <td style="color: green;">${{$i->precio3}}</td>
                        <td style="color: red;">${{$i->NuevoPrecio3}}</td>                                                 
                        <td>{{$i->costo}}</td>
                        <td>{{$i->iva}}</td>
                        <td>{{$i->rubro}}</td>
                        <td>{{$i->proveedor}}</td>
                        <td>{{$i->marca}}</td>
                        
                        </tr>
                    @endforeach

            </table>
        </div>

        {{ $inventario->links('vendor.livewire.bootstrap') }}

    </div>
</div>
