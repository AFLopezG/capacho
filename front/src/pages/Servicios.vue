<template>
  <q-page padding>
    <q-table
      title="LISTADO DE SERVICIOS Y PRECIO"
      :rows="servicios"
      :columns="columns"
      row-key="name"
    />
  </q-page>
</template>
<script>
export default {
  name:'servicioPage',
  data() {
    return {
      servicios:[],
      servicio:{},
      columns:[
        {name:'op',field:'op',label:'op'},
        {name:'nombre',field:'nombre',label:'NOMBRE'},
        {name:'estado',field:'estado',label:'estado'},
        {name:'monto',field:'monto',label:'monto'},
        {name:'imagen',field:'imagen',label:'imagen'},
      ]

      
    }
  },
  mounted(){
    this.getServicios()
  },
  methods:{
    getServicios(){
      this.$api.get('servicio').then(res => {
        this.servicios=res.data
      })

    }
  }
}
</script>