<template>
    <q-page padding>
    <div class=" q-pa-xs row">
        <div class="col-md-6 q-pa-xs"><q-input type="date" v-model="fecha" dense /> </div>
        <div class="col-md-6 q-pa-xs"><q-btn outline  color="primary" icon="search" label="GENERAR" @click="generar" />
            <q-btn color="info" icon="print" @click="impresion" v-if="datos.length>0"/></div>
    </div>
    <q-table
        title="LISTADO  DE SERVICIOS"
        :rows="datos"
        row-key="name"
    />
    <div id="myelement" class="hidden"></div>
    </q-page>
</template>
<script>
  import { date } from 'quasar'
      import { Printd } from 'printd'
      export default {
    name:'reportePage',
    data() {
        return {
            datos:[],
            fecha:date.formatDate(new Date(), 'YYYY-MM-DD'),
            fec:''
        }
    },
    mounted(){
        this.generar();
    },
    methods:{
        generar(){
            this.datos=[]
            if(this.fecha==undefined)
                return false
            this.$api.post('reporte/'+ this.fecha).then(res => {
                this.fec=this.fecha
                console.log(res.data)
                this.datos=res.data
            })
        },
        impresion(){
            if(this.datos.length<1)
                return false
            let contenido=''
            let tcant=0
            let ttotal=0
            this.datos.forEach(r => {
                contenido+='<tr><td>'+r.nombre+'</td><td>'+r.primero+'</td><td>'+r.ultimo+'</td><td>'+r.cantidad+'</td><td>'+r.subtotal+'</td></tr>'
                tcant+= parseInt(r.cantidad)
                ttotal+=parseFloat(r.subtotal)
            });
            let cadena=`
            <style>
            .cuerpo {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
}

.container {
  width: 100%;
  margin: 40px auto;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.header {
  text-align: center;
  margin-bottom: 5px;
}

.logo {
  width: 100px;
  height: 100px;
  margin: 2px auto;
}

.table1 {
  width: 100%;
  border-collapse: collapse;
}
.table2 {
  width: 100%;
  border-collapse: collapse;
}
.table2 td{
border:0px;
text-align:center;
}
th, td {
  border: 1px solid #ddd;
  padding: 10px;
  text-align: left;
}

th {
  background-color: #f0f0f0;
}

tfoot th {
  background-color: #fff;
}

tfoot th[colspan="3"] {
  text-align: right;
}
            </style>
    <div class='cuerpo'>
              <div class="container">
    <div class="header">
    <table class='table2'>
        <tr><td><img src="img/escudo.jpg" alt="Logo" class="logo"></td>
            <td>
                      <h1>REPORTE DE BALNEARIO DE CAPACHOS</h1>
      <p>Fecha: <span>`+this.fec+`</span></p>
                </td></tr>
    </table>
      

    </div>
    <table class='table1'>
      <thead>
        <tr>
          <th>SERVICIO</th>
          <th>PRIMERO</th>
          <th>ULTIMO</th>
          <th>CANTIDAD</th>
          <th>SUBTOTAL</th>
        </tr>
      </thead>
      <tbody id="tabla">
        `+contenido+`
      </tbody>
      <tfoot>
        <tr>
          <th colspan="3">Totales:</th>
          <th >`+tcant+`</th>
          <th >`+ttotal+`</th>
        </tr>
      </tfoot>
    </table>
  </div>
  </div>`
            document.getElementById('myelement').innerHTML = cadena
            const d = new Printd()
            d.print( document.getElementById('myelement') ) 
        }
    }
}
</script>