<template>
    <div>
        <div class="row">
            <div class="col-12 q-pa-sm"><q-input type="number" v-model="cantidad" label="Cantidad" /></div>
            <div v-for="(servicio, index) in servicios" :key="index" class="col-md-3" @click="enviarSolicitud(servicio)">
              <div class="card-container">
                <img :src="$url+'../imagen/'+servicio.imagen" alt="Imagen del servicio" >
                <div class="card-body">
                  <h5 class="card-title">{{ servicio.nombre }}</h5>
                  <p class="card-text">{{ servicio.monto }} Bs</p>
                </div>
              </div>
            </div>
        </div>
        <div id="myelement" class="hidden"></div>
    </div>
  </template>

  <script>
      import { Printd } from 'printd'
import QRCode from 'qrcode'
  export default {
    name:'ventaPage',
    data() {
      return {
        servicios: [],
        cantidad:1,
        imgqr:{},
        opts : {
        errorCorrectionLevel: 'M',
        type: 'png',
        quality: 0.95,
        width: 100,
        margin: 1,
        color: {
          dark: '#000000',
          light: '#FFF',
        },
      }
      }
    },
    methods:{
      enviarSolicitud(servicio){
        if(this.cantidad<1)
          return false
        servicio.cantidad=this.cantidad
        this.$api.post('venta', servicio).then(res => {
          console.log(res.data);
          res.data.forEach(r => {
              this.imprimir(r)
          });
          this.cantidad=1
        })
      },

      async imprimir(ticket){
        let qr='Unid: '+ticket.servicio.unit.nombre+
        '\nTicket : '+ticket.numero+
        '\nFecha: '+ticket.fecha+' '+ticket.hora+
        '\nValido: '+ticket.servicio.nombre+
        '\nMonto: '+ticket.monto+' Bs'
        this.imgqr= await QRCode.toDataURL(qr,this.opts)
        let cadparrilla=''
        if(ticket.servicio.nombre=='USO PARRILLERO'){
            cadparrilla=`<div>
              <table class='tab5'>
                <tr><td colspan=2>Usuario</td></tr>
                <tr><td style='width:50%'>H. Inicio</td><td>H. Fin</td></tr>
              </table>
            </div>`}
        let cadena=`<style>
        .tab5{
        width:100%;
        font-size:8px;
        border-collapse: collapse;
        }
        .tab5 tr{
        height:20px;
        }
        .tab5 td{
        font-weight: bold;
          border: 0.2px solid;
          border-radius: 25px;
          vertical-align: top;
          text-align: left;
        }
.ticket-container {
    width: 100%; /* adjust width as needed */
    margin: 2px auto;
    padding: 2px;
    border: 1px solid #ccc;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.logo {
    width: 70px; /* adjust logo size as needed */
    height: 70px;
    margin: 2px auto;
}

.title {
    font-size: 16px;
    font-weight: bold;
    text-align:center;
}

.subtitle {
    font-size: 10px;
    text-align:center;
}

.ticket-info {
    margin-top: 2px;
    border-bottom: 1px solid #ccc;
    padding-bottom: 1px;
}

.ticket-info p {
    margin-bottom: 10px;
}

.ticket-info strong {
    font-weight: bold;
}

.ticket-number {
    font-size: 24px;
    font-weight: bold;
}

.service-info {
    margin-top: 10px;
            text-align:center;
}

.service-info p {
    margin-bottom: 10px;
}

.service-info strong {
    font-weight: bold;
}
            .detalle {
            font-size: 26px;
            font-weight: bold;       /* Hace que la letra sea gruesa */
            -webkit-text-stroke: 1px black; /* Añade un borde negro de 2px */
            color: transparent;      /* Hace el relleno del texto transparente */
        }
        .textpie{
        font-size:10px;
         font-style: oblique;
         text-align:center;
        }
        </style>
        <div class="ticket-container">
        <table><tr><td><img src="img/escudo.jpg" alt="Logo Gobierno Autonomo de Oruro" class="logo"></td><td> <h1 class="title">`+ticket.servicio.unit.nombre+`</h1></td></tr></table>


        <div class="ticket-info">
            <div style='text-align:center;font-size:18px'>Ticket No.: <span class="ticket-number">`+ticket.numero+`</span></div>
            <div style='text-align:center;font-size:10px'><b>Fecha:</b><span>`+ticket.fecha +' '+ticket.hora +`</span></div>
        </div>
        <div class="service-info">
          <table style='width:100%'>
            <tr>
              <td style='width:50%'><div>Valido para:<br><span class='detalle'>`+ticket.servicio.nombre+`</span></div>
              <h2> `+ticket.monto+` Bs</h2> </td>
              <td>
                <img src="`+this.imgqr+`" style='width:80px;height:80px;padding:0;margin:0; '/>
              </td>
            </tr>
          </table>
          `+ cadparrilla+`
          <div class='textpie'>Orureños trabajando para orureños</div>
        </div>
     </div>`
        document.getElementById('myelement').innerHTML = cadena
            const d = new Printd()
            d.print( document.getElementById('myelement') )

      }
    },
    mounted() {
        this.$api.post('listServicio').then(response => {
          console.log(response.data)
          this.servicios = response.data
        })
        .catch(error => {
          console.error(error)
        })
    }
  }
  </script>
  <style>
  .card-container {
    background-color: rgb(212, 232, 250);
    width: 250px;
    height: 200px;
    margin: 10px;
    float: left;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    cursor: pointer;
  }

  .card-container img {
    width: 100%;
    height: 100px;
    object-fit: cover;
    border-radius: 10px 10px 0 0;
    margin-bottom: 0; /* Agrega este estilo */
  }

  .card-container .card-body {
    padding: 5px;
    margin-top: 0; /* Agrega este estilo */
  }

  .card-container .card-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 1px;
  }

  .card-container .card-text {
    font-size: 14px;
    color: #666;
  }

  .row {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;

  }</style>
