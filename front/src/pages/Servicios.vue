<template>
    <div>
        <div class="row">
            <div class="col-12 q-pa-sm"><q-input type="number" v-model="cantidad" label="Cantidad" /></div>
            <div v-for="(servicio, index) in servicios" :key="index" class="col-md-3">
              <div class="card-container">
                <img :src="$url+'../imagen/'+servicio.imagen" alt="Imagen del servicio" >
                <div class="card-body">
                  <h5 class="card-title">{{ servicio.nombre }}</h5>
                  <p class="card-text">{{ servicio.monto }} Bs</p>
                </div>
              </div>
            </div>
        </div>
    </div>
  </template>
  
  <script>
  export default {
    name:'ServicioPage',
    data() {
      return {
        servicios: [],
        cantidad:1
      }
    },
    mounted() {
        this.$api.post('listServicio')
        .then(response => {
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